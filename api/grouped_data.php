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
function extractSysInvoice($sysInvoice) {
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
            'sys' => $sysInvoice,
            'sys_Qty' => $legacy['qty'],
            'Garden' => $garden,
            'Grade' => $grade
        );
    } else {
        $groupedLegaciesData[$key]['sys_Qty'] += $legacy['qty'];
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
            'sys' => $sysInvoice,
            'sys_Qty' => $stock['stock_qty'],
            'Garden' => $garden,
            'Grade' => $grade
        );
    } else {
        $groupedStocksData[$key]['sys_Qty'] += $stock['stock_qty'];
    }
}

// Close the database connection
mysqli_close($conn);

// Prepare the grouped legacies data for the data table
$legaciesTableData = array();
foreach ($groupedLegaciesData as $data) {
    $legaciesTableData[] = array(
        $data['sys'],
        $data['sys_Qty'],
        $data['Garden'],
        $data['Grade']
    );
}

// Prepare the grouped stocks data for the data table
$stocksTableData = array();
foreach ($groupedStocksData as $data) {
    $stocksTableData[] = array(
        $data['sys'],
        $data['sys_Qty'],
        $data['Garden'],
        $data['Grade']
    );
}

// Prepare the JSON response
$response = array(
    'legaciesData' => $legaciesTableData,
    'stocksData' => $stocksTableData
);

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
