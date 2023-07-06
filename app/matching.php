<?php

use Illuminate\Support\Facades\DB;

function reconcileStock()
{
    // Find missing invoices
    $missingInvoices = DB::select("
        SELECT id, invoice_number, 'current_stock' AS missing_from_table
        FROM current_stock
        WHERE invoice_number NOT IN (
            SELECT invoice_number
            FROM physical_stock
        )
        UNION
        SELECT id, invoice_number, 'physical_stock' AS missing_from_table
        FROM physical_stock
        WHERE invoice_number NOT IN (
            SELECT invoice_number
            FROM current_stock
        )
    ");

    // Update mismatch and comment columns for missing invoices
    foreach ($missingInvoices as $missingInvoice) {
        $tableToUpdate = ($missingInvoice->missing_from_table === 'current_stock') ? 'current_stock' : 'physical_stock';

        DB::table($tableToUpdate)
            ->where('id', $missingInvoice->id)
            ->update([
                'mismatch' => 1,
                'comment' => 'Invoice not in ' . $missingInvoice->missing_from_table . ' stock records',
            ]);
    }

    // Find quantity mismatches
    $quantityMismatches = DB::select("
        SELECT cs.id, cs.invoice_number, cs.quantity AS current_quantity, ps.quantity AS physical_quantity
        FROM current_stock AS cs
        INNER JOIN physical_stock AS ps ON cs.invoice_number = ps.invoice_number
        WHERE cs.quantity != ps.quantity
    ");

    // Update mismatch and comment columns for quantity mismatches
    foreach ($quantityMismatches as $mismatch) {
        DB::table('current_stock')
            ->where('id', $mismatch->id)
            ->update([
                'mismatch' => 1,
                'comment' => 'Mismatch: Current quantity is ' . $mismatch->current_quantity . ', Physical quantity is ' . $mismatch->physical_quantity,
            ]);

        DB::table('physical_stock')
            ->where('id', $mismatch->id)
            ->update([
                'mismatch' => 1,
                'comment' => 'Mismatch: Current quantity is ' . $mismatch->physical_quantity . ', System quantity is ' . $mismatch->current_quantity,
            ]);
    }
}

// Call the function to reconcile the stock
reconcileStock();