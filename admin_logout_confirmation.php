<?php
session_start();

// Check if the user is an admin (optional, for security)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logout Confirmation</title>
    <style>
        /* General Styles */
        body {
            margin: 0;/*Margin creates space outside an element */
            padding: 0;/*Margin creates space inside an element */
            font-family: Arial, sans-serif;
            display: flex; /*for centering */
            justify-content: center; /* Centers the content horizontally */
            align-items: center;/* Centers the content verticallyally */
            height: 100vh; /* Full viewport height */
            background-color: #f8f9fa; /* Light background color */
        }

        .logout-container {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px; /* Space between buttons */
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button.yes {
            background-color: #dc3545;
            color: white;
        }

        .button.yes:hover {
            background-color: #c82333;
        }

        .button.cancel {
            background-color: #6c757d;
            color: white;
        }

        .button.cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Are you sure you want to log out?</h1>

         <!-- POST request is an HTTP method used to send data from the client (browser) to the server.
         commonly used in forms when submitting user input -->
        <div class="button-container">
            <form action="admin_logout.php" method="POST">
                <button type="submit" name="confirm_logout" class="button yes">Yes, Logout</button>
            </form>
            <form action="admin_cancel_logout.php" method="POST">
                <button type="submit" name="cancel_logout" class="button cancel">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>