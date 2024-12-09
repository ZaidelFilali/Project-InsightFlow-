<?php

require_once('queries.php');
require_once('functionstash.php')

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightFlow</title>
</head>

<body>
    <div class="navbar">
        <img src="afbeeldingen/flowlogo.png" alt="logo" height="95px" width="110px">
    </div>

    <div class="login">
        <h1>InsightFlow</h1>
        <form class="form" action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" placeholder="Enter Username" name="username" required>
            <label for="psw">Password</label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <input class="field" type="submit" value="Login" name="submit">
            <?php
            if (isset($_SESSION["acces"])) {
                if ($_SESSION["acces"] === "changed") {
                    echo "wachtwoord veranderd";
                }
            }
            ?>
        </form>

        <?php
        $resident = Process_login($conn, $_POST);
        ?>
    </div>
</body>

</html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #E5E7EB; 
    }

    .navbar {
        background-color: white;
        color: #cf3e87;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
        height: 100px;
        width: 100%;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .login {
        width: 300px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .login h1 {
        font-size: 24px;
        margin-bottom: 40px;
        text-align: center;
    }

    .form {
        text-align: center;
        height: 300px;
    }

    .form label {
        display: block;
        margin-bottom: 6px;
    }

    .form input[type="text"],
    .form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form input[type="submit"] {
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background-color: #e46eca;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form input[type="submit"]:hover {
        background-color: plum;
    }

    .logo{
    font-size: xx-large;
    cursor: pointer;
}
</style>