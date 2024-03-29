<?php
// Database connection parameters
$host = 'localhost';
$username = 'u379477280_root2';
$password = 'Root@123';
$database = 'u379477280_stock';

// Establish a database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper function to extract the sys invoice number without separators
function extractSysInvoice($sysInvoice)
{
    if (strpos($sysInvoice, '.') !== false) {
        return strtok($sysInvoice, '.');
    } elseif (strpos($sysInvoice, '/') !== false) {
        return strtok($sysInvoice, '/');
    } elseif (strpos($sysInvoice, '-') !== false) {
        return strtok($sysInvoice, '-');
    } else {
        return $sysInvoice;
    }
}

// SQL query to fetch data from legacies table
$legaciesQuery = "SELECT invoice, qty, garden, grade FROM legacies";
$legaciesResult = mysqli_query($conn, $legaciesQuery);

// SQL query to fetch data from stocks table
$stocksQuery = "SELECT s.invoice AS stock_invoice, s.qty AS stock_qty, g.name AS garden_name, gd.name AS grade_name
                FROM stocks s
                JOIN gardens g ON s.garden_id = g.id
                JOIN grades gd ON s.grade_id = gd.id";
$stocksResult = mysqli_query($conn, $stocksQuery);

// Create an array to store the legacies data
$legaciesData = array();
while ($row = mysqli_fetch_assoc($legaciesResult)) {
    $legaciesData[] = $row;
}

// Create an array to store the stocks data
$stocksData = array();
while ($row = mysqli_fetch_assoc($stocksResult)) {
    $stocksData[] = $row;
}

// Group the legacies data by sys invoice, garden, and grade
$groupedLegaciesData = array();
foreach ($legaciesData as $legacy) {
    $sysInvoice = extractSysInvoice($legacy['invoice']);
    $garden = $legacy['garden'];
    $grade = $legacy['grade'];

    $key = $sysInvoice . '_' . $garden . '_' . $grade;
    if (!isset($groupedLegaciesData[$key])) {
        $groupedLegaciesData[$key] = array(
            'invoice' => $sysInvoice,
            'qty' => $legacy['qty'],
            'garden' => $garden,
            'grade' => $grade
        );
    } else {
        $groupedLegaciesData[$key]['qty'] += $legacy['qty'];
    }
}

// Group the stocks data by sys invoice, garden, and grade
$groupedStocksData = array();
foreach ($stocksData as $stock) {
    $sysInvoice = extractSysInvoice($stock['stock_invoice']);
    $garden = $stock['garden_name'];
    $grade = $stock['grade_name'];

    $key = $sysInvoice . '_' . $garden . '_' . $grade;
    if (!isset($groupedStocksData[$key])) {
        $groupedStocksData[$key] = array(
            'stock_invoice' => $sysInvoice,
            'stock_qty' => $stock['stock_qty'],
            'garden_name' => $garden,
            'grade_name' => $grade
        );
    } else {
        $groupedStocksData[$key]['stock_qty'] += $stock['stock_qty'];
    }
}

// Perform matching and prepare data for the DataTable
$matchedData = array();
$count = 1;
foreach ($groupedLegaciesData as $legacy) {
    $legacyInvoice = $legacy['invoice'];
    $legacyQty = $legacy['qty'];
    $legacyGarden = $legacy['garden'];
    $legacyGrade = $legacy['grade'];

    $matched = false;

    foreach ($groupedStocksData as $stock) {
        $stockInvoice = $stock['stock_invoice'];
        $stockQty = $stock['stock_qty'];
        $stockGarden = $stock['garden_name'];
        $stockGrade = $stock['grade_name'];

        $sysInvoice = extractSysInvoice($legacyInvoice);

        if ($sysInvoice == $stockInvoice && $legacyGarden == $stockGarden && $legacyGrade == $stockGrade) {
            $matchedData[] = array(
                '#' => $count,
                'sys' => $legacyInvoice,
                'phys' => $stockInvoice,
                'sys_Qty' => $legacyQty,
                'phys_Qty' => $stockQty,
                'Garden' => $stockGarden,
                'Grade' => $stockGrade,
                'Status' => ($legacyQty == $stockQty) ? 'match' : 'mismatch'
            );

            $matched = true;
        }
    }

    if (!$matched) {
        $matchedData[] = array(
            '#' => $count,
            'sys' => $legacyInvoice,
            'phys' => '',
            'sys_Qty' => $legacyQty,
            'phys_Qty' => 0,
            'Garden' => $legacyGarden,
            'Grade' => $legacyGrade,
            'Status' => 'mismatch'
        );
    }

    $count++;
}

// Iterate over $groupedStocksData and add unmatched entries to $matchedData
foreach ($groupedStocksData as $stock) {
    $stockInvoice = $stock['stock_invoice'];
    $stockQty = $stock['stock_qty'];
    $stockGarden = $stock['garden_name'];
    $stockGrade = $stock['grade_name'];

    // Check if stock invoice exists in $matchedData
    if (!in_array($stockInvoice, array_column($matchedData, 'phys'))) {
        $matchedData[] = array(
            '#' => $count,
            'sys' => '',
            'phys' => $stockInvoice,
            'sys_Qty' => 0,
            'phys_Qty' => $stockQty,
            'Garden' => $stockGarden,
            'Grade' => $stockGrade,
            'Status' => 'unmatched'
        );
        $count++;
    }
}

// Calculate totalSysQty and totalPhysQty
$totalSysQty = array_sum(array_column($legaciesData, 'qty'));
$totalPhysQty = array_sum(array_column($stocksData, 'stock_qty'));

// Calculate missingBagsQty
$missingBagsQty = $totalSysQty - $totalPhysQty;

// Prepare the response data
$response = array(
    'data' => $matchedData,
    'stats' => array(
        'totalSysQty' => $totalSysQty,
        'totalPhysQty' => $totalPhysQty,
        'totalMismatchInvoices' => count(array_filter($matchedData, function ($item) {
            return $item['Status'] == 'mismatch';
        })),
        'missingBagsQty' => $missingBagsQty
    )
);

// Send the response as JSON
// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
