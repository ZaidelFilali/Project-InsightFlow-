<?php
require_once('functionstash.php');
if (!isset($_SESSION['activitiename'])) {
    $_SESSION["activitiename"] = $_POST['edit'];
}
if (!isset($activitiename)) {
    $activitiename = $_SESSION['activitiename'];
}
if (isset($_POST["add"])) {
    if (isset($_SESSION["Lastadded"])) {
        if ($_POST["add"] !== $_SESSION["Lastadded"]) {
            Add2activitie($conn, $activitiename, $_POST["add"]);
        }
    } else {
        Add2activitie($conn, $activitiename, $_POST["add"]);
    }
    $_SESSION["Lastadded"] = $_POST["add"];
}
if (isset($_POST["remove"])) {
    if (isset($_SESSION["Lastremoved"])) {
        if ($_POST["remove"] !== $_SESSION["Lastremoved"]) {
            Removeactivitie($conn, $activitiename, $_POST["remove"]);
        }
    } else {
        Removeactivitie($conn, $activitiename, $_POST["remove"]);
    }
    $_SESSION["Lastremoved"] = $_POST["remove"];
}
$activitiedata = Getactivities($conn, "WHERE `Activiteit` = '$activitiename'");
$Allres = getresidentsnamesonly($conn, "ORDER BY naam ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <title><?= $activitiename; ?></title>
</head>

<body>
<div class="nav-container">
<div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/InsightFlowlogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h1 id="h1">Bewerken</h1>
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
    <form action="activiteit.php" method="POST">
        <input class="back-btn" type="submit" value="Terug" name="Terug"></input>
    </form>
    <div class="activiteit-container">
        <div class="activiteit">
            <h2 style="color:darkgray;">Activiteit:</h2>
            <h1><?= $activitiename; ?></h1>
        </div>
    </div>

    <div class="contianer">
        <div style="display: flex; justify-content: space-around;">
            <!---toegevoegde bewoners filter--->
            <div class="sidebar">
                <div class="sidebar-content">
                    <h2 style="display: flex; justify-content: center;">Filter:</h2>
                    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                        <label for="Naam">Naam:</label>
                        <input type="text" placeholder="" name="leftfilter">
                        <input type="submit" value="toggle filter" name="Lsubmit">
                    </form>
                </div>
            </div>
            <!---beschrikbare bewoners filter--->
            <div class="sidebar">
                <div class="sidebar-content">
                    <h2 style="display: flex; justify-content: center;">Filter:</h2>
                    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                        <label for="Naam">Naam:</label>
                        <input type="text" placeholder="" name="username">
                        <input type="submit" value="toggle filter" name="Rsubmit">
                    </form>
                </div>
            </div>
            <?php
            if (isset($_POST["Rsubmit"])) {
                $Allres = Filterresidents($conn, $_POST);
            }
            ?>
        </div>
        <?php
        $players = json_decode($activitiedata[0]['Deelnemers']);
        $Allres = array_column($Allres, 'naam');
        $non_players = array_diff($Allres, $players);
        if (isset($_POST["leftfilter"])) {
            if ($_POST["leftfilter"] != "") {
                $temparray = [];
                foreach ($players as $player) {
                    if (strpos(strtolower($player), strtolower($_POST["leftfilter"])) !== false) {
                        $temparray[] = $player;
                    }
                }
                $players = $temparray;
            }
        }
        echo '<div class="bewonersbox">';
        echo '<div class="toegevoegd">';
        foreach ($players as $player) {
        ?>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <input type="submit" value="<?= $player ?>" name="remove">
            </form>
        <?php
        }
        echo '</div>';
        echo '<div class="beschikbaar">';
        foreach ($non_players as $non_player) {
        ?>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <input type="submit" value="<?= $non_player ?>" name="add">
            </form>
        <?php
        }
        echo '</div>';
        echo '</div>';
        if (isset($_POST["Delete"])) {
        ?>
            <div class="confirmationcontainer">
                <h1>Weet je zeker dat je deze activiteit wil verwijderen?</h1>
                <form action="activiteit.php" method="POST">
                    <input type="submit" value="Verwijder <?= $activitiename ?>" name="DefDelete">
                </form>
                <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                    <input type="submit" value="Annuleren" name="NVM">
                </form>
            </div>
        <?php
        }
        ?>
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
        display: grid;
        grid-template-columns: 250px auto;
        gap: 20px;
        margin: 20px;
    }

    .sidebar {
        background-color: #fff;
        padding: 20px;
        width: 300px;
        height: 150px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
        margin-bottom: 10px;
        text-align: center;
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

    .sidebar form {
        text-align: center;
    }

    .sidebar label {
        display: block;
        margin-bottom: 5px;
    }

    .sidebar input[type="text"] {
        width: calc(100% - 10px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .sidebar input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #e46eca;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    h2 {
        margin: 0px;
    }


    .sidebar input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .bewonersbox {
        display: flex;
        width: 100%;
        justify-content: space-around;
        gap: 80px;
        margin-top: 30px;
    }

    .bewonersbox div {
        flex: 1;
    }

    .confirmationcontainer {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .confirmationcontainer h1 {
        margin-bottom: 20px;
        text-align: center;
    }

    .confirmationcontainer form {
        display: flex;
        justify-content: space-around;
    }

    .beschikbaar {
        background-color: white;
        border-radius: 10px;
        display: flex;
        justify-content: flex-end;
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

    .toegevoegd {
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

    .activiteit-container {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-bottom: 30px;
    }

    .activiteit {
        display: flex;
        background-color: white;
        width: 300px;
        padding: 20px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        height: 150px;
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

    .back-btn a:hover {
        background-color: #0056b3;
        cursor: pointer;
    }

    .back-btn {
        margin-top: 20px;
        margin-left: 35px;
        padding: 10px 20px;
        background-color: #e46eca;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }
</style>