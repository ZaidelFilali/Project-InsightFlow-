<?php
require_once('functionstash.php');
if (isset($_POST["Terug"])) {
    unset($_SESSION["resident"]);
}
if (isset($_SESSION["acces"])) {
    if ($_SESSION["acces"] === "approved") {
    } else {
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}
Checkact($conn);
if (isset($_POST["order"])) {
    $order = $_POST["order"];
} else {
    $order = "woning";
}
if ($order === "woning") {
    $orderquery = "ORDER BY woningnummer ASC";
} elseif ($order === "Naam") {
    $orderquery = "ORDER BY naam ASC";
}
if (isset($_POST["delres"])) {
    Deleteres($conn, $_POST["delres"]);
    $activities = Getactivities($conn, "");
    foreach ($activities as $activitie) {
        if (strstr($activitie["Deelnemers"], $_POST["delres"])) {
            Removeactivitie($conn, $activitie["Activiteit"], $_POST["delres"]);
        }
    }
    unset($_POST);
    unset($_SESSION["resident"]);
}
$resident = getresidents($conn, $orderquery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightFlow Bewoners Dashboard</title>
    <link rel="stylesheet" href="stylesheetindex.css">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/flowlogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h1>InsightFlow bewoners dashboard</h1>
        <div class="dropdown" onclick="toggleDropdown()">
            <i class="fa-solid fa-user" style="display: flex; background-color: #e46eca; cursor: pointer; color: #ffffff; font-size: xl-large; padding: 10px; border-radius: 50%;"></i>
            <div class="dropdown-content" id="myDropdown">
                <form action="changepass.php" method="POST">
                    <input class="Change" type="submit" value="VeranderWachtwoord" name="Change"></input>
                </form>
                <form action="index.php" method="POST">
                    <input class="Logout" type="submit" value="Uitloggen" name="logout"></input>
                </form>
            </div>
        </div>
        <?php
        if (isset($_POST["logout"])) {
            $_SESSION["acces"] = "nada";
            header("Location: login.php");
        }
        ?>
    </div>
    <div class="container">
        <div style="display: flex;">
            <div class="sidebar">
                <div class="sidebar-content">
                    <h2>Filter:</h2>
                    <form action="index.php" method="POST">
                        <label for="Order">Volgorde:</label><br>
                        <select id="Order" name="order" class="form-control" onchange="this.form.submit()">
                            <option value="woning" <?php echo ($order == 'woning') ? 'selected' : ''; ?>>Woningnummer</option>
                            <option value="Naam" <?php echo ($order == 'Naam') ? 'selected' : ''; ?>>Naam</option>
                        </select>
                    </form>
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <label for="Naam">Naam:</label>
                            <input type="text" class="form-control" placeholder="" name="username">
                        </div>
                        <div class="form-group">
                            <label for="woningnummer">Woningnummer:</label>
                            <input type="number" class="form-control" placeholder="" name="roomnum">
                        </div>
                        <div class="form-group">
                            <label for="activiteiten">Activiteiten:</label><br>
                            <select id="activiteiten" name="activiteiten" class="form-control">
                                <option value=""></option>
                                <option value="ja">Ja</option>
                                <option value="nee">Nee</option>
                            </select>
                        </div>
                        <input type="submit" class="zoeken" value="Zoek op" name="submit">
                    </form>
                </div>
            </div>
            <div class="content">
                <div class="toevoegen">
                    <form action="activiteit.php" method="POST">
                        <input class="buttons" type="submit" value="Hobby/Intresses" name="sent"></input>
                    </form>
                    <form action="form.php" method="POST">
                        <input class="buttons" type="submit" value="+ Bewoner toevoegen" name="sent"></input>
                    </form>
                </div>
                <div class="bewonersbox">
                    <?php
                    if (isset($_POST["submit"])) {
                        if (!empty($_POST["username"]) || !empty($_POST["roomnum"]) || !empty($_POST["form-control"])) {
                            $resident = Filterresidents($conn, $_POST);
                        }
                    }
                    if (!empty($resident)) {
                        Echoresident($resident);
                    } else {
                        echo '<div class="ifempty">Geen Bewoners om te laten zien</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

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

<style>
    input[type="text"],
    input[type="number"],
    select {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    .toevoegen {
        position: fixed;
        top: 10px; 
        right: 80px; 
        display: flex;
        gap: 10px;
    }

    .bewonersbox {
        margin-top: 100px; 
    }
</style>
