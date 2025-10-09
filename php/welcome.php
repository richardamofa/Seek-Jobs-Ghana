<?php
// Start the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php"); // redirect back to login if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Seek Jobs Ghana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        p {
            margin: 20px 0;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #6C63FF;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }
        a:hover {
            background: #574bdf;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["fullname"]); ?> 👋</h1>
        <p>You are now logged in to <strong>Seek Jobs Ghana</strong>.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
