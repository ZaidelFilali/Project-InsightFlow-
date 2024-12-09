<?php
require_once('functionstash.php');
if (!isset($_SESSION["players"])) {
    $_SESSION["players"] = [];
}
if (isset($_POST["add"])) {
    if (isset($_SESSION["Lastadded"])) {
        if ($_POST["add"] !== $_SESSION["Lastadded"]) {
            $_SESSION["players"][] = $_POST["add"];
        }
    } else {
        $_SESSION["players"][] = $_POST["add"];
    }
    $_SESSION["Lastadded"] = $_POST["add"];
}
if (isset($_POST["remove"])) {
    if (isset($_SESSION["Lastremoved"])) {
        if ($_POST["remove"] !== $_SESSION["Lastremoved"]) {
            $key = array_search($_POST["remove"], $_SESSION["players"]);
            unset($_SESSION["players"][$key]);
            $_SESSION["players"] = array_values($_SESSION["players"]);
        }
    } else {
        $key = array_search($_POST["remove"], $_SESSION["players"]);
        unset($_SESSION["players"][$key]);
        $_SESSION["players"] = array_values($_SESSION["players"]);
    }
    $_SESSION["Lastremoved"] = $_POST["remove"];
}
if (isset($_POST["newactname"])) {
    $_SESSION["newactname"] = $_POST["newactname"];
}
$Allres = getresidentsnamesonly($conn, "ORDER BY naam ASC");

if (isset($_POST['add']) || isset($_POST['remove'])) {
    $shouldClickLink = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewAct</title>
</head>

<body>
<div class="nav-container">
<div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/InsightFlowlogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h1 id="h1">Toevoegen</h1>
        <Div class="dropdown" onclick="toggleDropdown()">
            <i class="fa-solid fa-user" style="display: flex; background-color: #e46eca; cursor: pointer; color: #ffffff; font-size: xl-large; padding: 10px; border-radius: 50%;"></i>
            <div class="dropdown-content" id="myDropdown">
                <form action="changepass.php" method="POST">
                    <input class="Change" type="submit" value="VeranderWachtwoord" name="Change"></input>
                </form>
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
            <a href="activiteit.php">Terug</a>
        </div>
    <div class="container">
        <div class="sidebar">
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <h2>Hobby/Intressen</h2>
                <label for="Naam">Naam:</label>
                <input type="text" value="<?php if (isset($_SESSION['newactname'])) {
                                                echo $_SESSION['newactname'];
                                            } ?>" name="newactname" required>
                <input class="namebtn" type="submit" value="Submit" name="actname" style="background-color: #e46eca;"></input>
            </form>
        </div>
    </div>
        <div class="flex-container">
            <div class="sidebar">
            <h3 style="display: flex; justify-content: center; color: gray;">Toegevoegd</h3>
            <h2 style="display: flex; justify-content: center;">Zoeken</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <label for="Naam">Naam:</label>
                <input type="text" placeholder="" name="username">
                <input type="submit" value="Zoek" name="Zoektoe" style="background-color: #e46eca;">
            </form>
            </div>
    <?php if (isset($_SESSION['newactname'])): ?>
    <div class="newnamecontainer">
    <div class="newname">
            <h3 style="color: gray">Activiteit:</h3>
            <h1 style="margin-top: 5px;"><?php echo $_SESSION['newactname']; ?></h1>
            <form action="activiteit.php" method="POST">
        <input class="activiteitenbtn" type="submit" value="Voeg toe" name="addact" style="background-color: #e46eca;"></input>
    </form>   
    </div>
    </div>
    <?php endif; ?> 
    <div class="sidebar">
            <h3 style="display: flex; justify-content: center; color: gray;">Beschikbaar</h3>
            <h2 style="display: flex; justify-content: center;">Zoeken</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <label for="Naam">Naam:</label>
                <input type="text" placeholder="" name="username">
                <input type="submit" value="Zoek" name="Zoekbes" style="background-color: #e46eca;">
            </form>
            </div>
            </div>
    <?php
    if (isset($_POST["Zoekbes"])) {
        $Allres = Filterresidents($conn, $_POST);
    }
    ?>
    <div class="bewonersbox" id="BWB">
        <?php
        $Allres = array_column($Allres, 'naam');
        $non_players = array_diff($Allres, $_SESSION["players"]);
        ?>
        <div id="toegevoegd">
            <?php if (isset($_POST["Zoektoe"])) { ?>
                <?php foreach ($_SESSION["players"] as $player) : ?>
                    <?php if (strstr(strtolower($player), strtolower($_POST["username"]))) { ?>
                        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                            <input type="submit" value="<?= $player ?>" name="remove">
                        </form>
                    <?php } ?>
                <?php endforeach; ?>
            <?php } else { ?>
                <?php foreach ($_SESSION["players"] as $player) : ?>
                    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                        <input type="submit" value="<?= $player ?>" name="remove">
                    </form>
                <?php endforeach; ?>
            <?php } ?>
        </div>
        <div id="beschikbaar">
            <?php foreach ($non_players as $non_player) : ?>
                <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                    <input type="submit" value="<?= $non_player ?>" name="add">
                </form>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="#BWB" id="autoLink"></a>
    <?php if (isset($shouldClickLink)) : ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("autoLink").click();
            });
        </script>
    <?php endif; ?>
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
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .newname {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

        .newnamecontainer{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: white;
            width: 400px ;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

    .sidebar {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    form {
        display: inline-block;
        margin: 0px;
        max-width: 200px;
    }

        .flex-container{
            display: flex;
            justify-content: space-around; 
            margin-bottom: 20px;
        }

    .sidebar h2 {
        margin-bottom: 10px;
        text-align: center;
    }

    .sidebar form {
        text-align: center;
    }

    .sidebar label {
        display: block;
        margin-bottom: 5px;
    }

    .sidebar input[type="text"] {
        width: 93%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .sidebar input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .sidebar input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .bewonersbox div {
        flex: 1;
    }

    .bewonersbox form input[type="submit"] {
        width: 200px;
        padding: 10px;
        background-color: white;
        color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 5px;
        display: flex;
        justify-content: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        margin-left: 10px;
        margin-top: 10px;
    }


    .bewonersbox {
        display: flex;
        justify-content: space-around;
        gap: 80px;
    }

    #beschikbaar {
        background-color: white;
        border-radius: 10px;
        padding: 10px;
        box-sizing: border-box;
        max-width: auto;
        /* Adjust the width as needed */
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        /* 5 items per row */
        grid-auto-flow: row;
        /* Items will flow into new rows */
        margin-right: 80px;
        grid-row-gap: 5px;
        /* Adjust the gap between rows */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 1500px) {
        .bewonersbox form input[type="submit"] {
            width: 150px;
            padding: 10px;
            background-color: white;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 5px;
            display: flex;
            justify-content: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            margin-left: 10px;
            margin-top: 10px;
        }
    }

    #toegevoegd {
        background-color: white;
        border-radius: 10px;
        padding: 10px;
        box-sizing: border-box;
        max-width: auto;
        /* Adjust the width as needed */
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        /* 5 items per row */
        grid-row-gap: 5px;
        margin-left: 80px;
        align-self: flex-start;
        min-height: 65px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }


    .activiteittoevoegen {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .container {
        width: 100%;
    }

    .activiteitenbtn {
        width: 200px;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 40px;
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