<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Example</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
</head>

<body>

    <div class="card mb-4">

        <div class="card-body p-4">












            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Order date</th>
                        <th>Total amount</th>
                        <th>Payment type</th>

                        <th>Status</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>


                    <!-- Table header remains the same -->

                    <tr>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>Tiger Nixon</td>
                        <td>Edinburgssssssssssssssssssssssssssssssssssssssh</td>
                    </tr>

                </tbody>
            </table>


        </div>
    </div>


    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgssssssssssssssssssssssssssssssssssssssh</td>
            </tr>

        </tbody>
    </table>

    <!-- jQuery (required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            new DataTable('#myTable');
        });
    </script>
</body>

</html>