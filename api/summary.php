<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-mismatch,
        .status-match {
            border-radius: 50px;
            color: #fff;
            font-weight: bolder;
            text-align: center;
        }

        .status-mismatch {
            background-color: #e33232;
        }

        .status-match {
            background-color: #549868;
        }
        .bg-green, .bg-dark {
            background-color:#00a65a!important;
        }
    </style>

    <script>
        $(document).ready(function () {
            // Fetch data from the server
            $.ajax({
                url: "https://api.betta.co.ke/data.php",
                dataType: "json",
                success: function (response) {
                    // Update the statistics in the Bootstrap cards
                    $("#totalSysQty").text(response.stats.totalSysQty);
                    $("#totalPhysQty").text(response.stats.totalPhysQty);
                    $("#totalMismatchInvoices").text(response.stats.totalMismatchInvoices);
                    $("#missingBagsQty").text(response.stats.missingBagsQty);

                    // Initialize the DataTable with the matched data
                    var table = $('#matchingTable').DataTable({
                        data: response.data,
                        pageLength: 50, // Set the default number of rows to display
                        lengthMenu: [50, 100, 250], // Provide options for different row lengths
                        dom: 'Blfrtip',
                        buttons: ['excel'],
                        columns: [
                            { data: "#" },
                            { data: "sys" },
                            { data: "phys" },
                            { data: "sys_Qty" },
                            { data: "phys_Qty" },
                            { data: "Garden" },
                            { data: "Grade" },
                            {
                                data: "Status",
                                render: function (data, type, row) {
                                    var className = (data === "mismatch") ? "status-mismatch" : "status-match";
                                    return '<div class="' + className + '">' + data + '</div>';
                                }
                            }
                        ],
                    });
                }
            });
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">UEA Stock Reconciliation</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://betta.co.ke/home">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betta.co.ke/legacies">System Stock</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betta.co.ke/stocks">Physical Stock</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-2">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">System Stock</h5>
                        <p class="card-text" id="totalSysQty">--</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Physical Stock</h5>
                        <p class="card-text" id="totalPhysQty">--</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mismatch Invoices</h5>
                        <p class="card-text" id="totalMismatchInvoices">--</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Missing Bags (Qty)</h5>
                        <p class="card-text" id="missingBagsQty">--</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <table id="matchingTable" class="table display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>sys</th>
                            <th>phys</th>
                            <th>sys_Qty</th>
                            <th>phys_Qty</th>
                            <th>Garden</th>
                            <th>Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
