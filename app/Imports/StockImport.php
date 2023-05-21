<?php

namespace App\Imports;

use App\Stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

class StockImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     *
     * @throws ValidationException
     */
    public function collection(Collection $rows)
    {
        $failures = [];
        $headerRow = null;

        foreach ($rows as $row) {
            try {
                // Validate the row data
                $this->validateRow($row, $headerRow);

                // Process the row data and create/update the stock entry
                $this->processRow($row);
            } catch (Throwable $e) {
                // Catch any exceptions or validation errors that occurred during processing
                $failures[] = [
                    'row' => $row->toArray(),
                    'errors' => $e instanceof ValidationException ? $e->errors() : [$e->getMessage()],
                ];
            }

            // Store the header row to use it for subsequent row validations
            if ($headerRow === null) {
                $headerRow = $row->keys()->toArray();
            }
        }

        // Throw a validation exception if any failures occurred
        if (!empty($failures)) {
            throw ValidationException::withMessages($failures);
        }
    }

    /**
     * Validate the row data.
     *
     * @param Collection $row
     * @param array|null $headerRow
     *
     * @throws Throwable
     */
    private function validateRow(Collection $row, ?array $headerRow)
    {
        // Perform your validation logic here
        // You can use Laravel's Validator class or custom validation rules

        // Example validation:
        $validator = validator($row->toArray(), [
            'warehouse_id' => 'required',
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|string',
            'year' => 'required|string',
            'remark' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages([
                $row->toArray() => $validator->errors()->all(),
            ]);
        }
    }

    /**
     * Process the row data and create/update the stock entry.
     *
     * @param Collection $row
     *
     * @throws Throwable
     */
    private function processRow(Collection $row)
    {
        // Process the row data and create/update the stock entry
        // You can access the row data using $row['column_name']

        // Example:
        Stock::updateOrCreate([
            'warehouse_id' => $row['warehouse_id'],
            'bay_id' => $row['bay_id'],
            'owner_id' => $row['owner_id'],
            'garden_id' => $row['garden_id'],
            'grade_id' => $row['grade_id'],
            'package_id' => $row['package_id'],
            'invoice' => $row['invoice'],
            'year' => $row['year'],
        ], [
            'qty' => $row['qty'],
            'remark' => $row['remark'],
        ]);
    }
}
