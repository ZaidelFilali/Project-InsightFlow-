<?php

require_once('queries.php');
require_once('functionstash.php')

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightFlow</title>
</head>

<body>
<div class="nav-container">
<div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/InsightFlowlogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h1 id="h1">Verander wachtwoord</h1>
        <Div class="dropdown" onclick="toggleDropdown()">
            <i class="fa-solid fa-user" style="display: flex; background-color: #e46eca; cursor: pointer; color: #ffffff; font-size: xl-large; padding: 10px; border-radius: 50%;"></i>
            <div class="dropdown-content" id="myDropdown">
                <form action="index.php" method="POST">
                    <input class="Logout" type="submit" value="Uitloggen" name="logout"></input>
                </form>
            </div>
        </Div>
</div>
        <?php
        if (isset($_POST["logout"])) {
            $_SESSION["acces"] = "nada";
            header("Location: login.php");
        }
        ?>
    </div>
    <div class="back-btn">
            <a href="index.php">Terug</a>
        </div>

    <div class="login">
        <h1>Isselwaerde</h1>
        <form class="form" action="changepass.php" method="POST">
            <label for="OP">Oud wachtwoord</label>
            <input type="password" placeholder="Oud wachtwoord" name="OP" required>
            <label for="NP">Nieuw wachtwoord</label>
            <input type="password" placeholder="Nieuw wachtwoord" name="NP" required>
            <label for="RNP">Herhaal nieuw wachtwoord</label>
            <input type="password" placeholder="Herhaal nieuw wachtwoord" name="RNP" required>
            <input class="field" type="submit" value="Verander wachtwoord" name="submit">
        </form>

        <?php
        if (isset($_POST["NP"]) & isset($_POST["RNP"])) {
            if ($_POST["NP"] === $_POST["RNP"]) {
                Process_changepass($conn, $_POST, $_SESSION["gebruikersnaam"], $_SESSION["wachtwoord"]);
                $_SESSION["acces"] = "changed";
                header("Location: login.php");
            } else {
                echo "Vul hetzelfde wachtwoord in bij nieuw wachtwoord en bij herhaal wachtwoord";
            }
        }
        ?>
    </div>
</body>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "block";
        }
    }

    window.onclick = function(event) {
        if (!event.target.matches('.fa-user')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }
</script>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
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
        margin-bottom: 20px;
        text-align: center;
    }

    .form {
        text-align: center;
    }

    .form label {
        display: block;
        margin-bottom: 6px;
    }

    .form input[type="text"],
    .form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #e46eca;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    #h1{
            margin-right: 70px;
        }

        .nav-container{
            width: 100%;
            height: 100px;
        }

        .navbar{
    background-color:white;
    position: absolute;
    top: 0;
    margin-top: 0px;
    height: 100px;
    width: 100%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
    flex-direction: row;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown {
    position: relative;
    display: inline-block;     
    margin-right: 30px;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 12px 16px;
    z-index: 1;
    right: 0;
    border: solid black 1px;
    margin-top: 5px;
  }
  .Logout{
    color: red;
    padding: 5px;
    margin-top: 15px;
    cursor: pointer;
    width: 160px;
}

.Change{
    padding: 5px;
    cursor: pointer;
    background-color: #e46eca;
    border: none;
    color: #fff;
    width: 160px;
}

        .logo{
        font-size: xx-large;
        margin-left: 30px;
        display: flex;
        cursor: pointer;
        background-color: #fff;
        border: none;
        }

        .back-btn a {
        padding: 10px 20px;
        background-color: #e46eca;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .back-btn a:hover {
        background-color: #0056b3;
    }

    .back-btn {
        margin-top: 20px;
        margin-left: 35px;
    }
</style>