<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if (isset($_GET['registered_already']) && $_GET['registered_already'] == 1): ?>
        <div id="alert" class="alert alert-danger mx-4 text-center" role="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
            <strong>Error!</strong> Email is already registered.
        </div>
    <?php endif; ?>
    <script>
        // Set a timeout to hide the alert after 5 seconds (5000 ms)
        setTimeout(function() {
            var alertElement = document.getElementById('alert');
            if (alertElement) {
                alertElement.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 seconds
    </script>

</body>

</html>