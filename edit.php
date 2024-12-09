<?php

require_once('functionstash.php');
if (isset($_POST["id"])) {
    $_SESSION["resident"] = getresident($conn, $_POST["id"]);
} else {
    // header("Location: index.php");
}
$activities = Getactivities($conn, "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="detailstylesheet.css">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,700,0,0" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title><?= $_SESSION["resident"]['naam'] ?> detail</title>
</head>

<body>

    <div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/flowlogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h1>InsightFlow bewoner</h1>

        <div class="dropdown" onclick="toggleDropdown()">
            <i class="fa-solid fa-user" style="display: flex; background-color: #e46eca; cursor: pointer; color: #ffffff; font-size: xl-large; padding: 10px; border-radius: 50%; margin-bottom: 7px;"></i>
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
        <div class="infobewonerbox">
            <div class="text">
                <form action="detail.php" method="POST">
                    <input class="back" type="submit" value="<" name="Terug"></input>
                </form>
                <h2 class="text2">Infomatie bewoner:<h2>
            </div>
            <div class="bewoner">
                <div class="infopersoon">
                    <form action="detail.php" method="post">
                        <div class="infopersoonnaam">
                            <input type="text" name="naam" value="<?= $_SESSION["resident"]['naam'] ?>"><br><br>
                        </div>
                        <div class="infocontent">
                            <span class="dikgedrukt">Woning: </span>
                            <input type="text" name="woningnummer" value="<?= $_SESSION["resident"]['woningnummer'] ?>"><br>
                            <span class="dikgedrukt">Activiteiten: </span><br>
                            <div class="ceckbox-container">
                                <div class="item">
                            <?php foreach ($activities as $activity) {
                                if (strpos($activity["Deelnemers"], $_SESSION["resident"]['naam'])) {
                            ?></div>
                            <div class="item2">
                                    <input type="checkbox" checked name="checked[]" value="<?= $activity['Activiteit'] ?>"><?= $activity['Activiteit'] ?><br>
                                <?php } else { ?>
                                    <input type="checkbox" name="checked[]" value="<?= $activity['Activiteit'] ?>"><?= $activity['Activiteit'] ?><br>
                                <?php } ?>
                            <?php } ?>
                            </div>
                            </div>
                            <br>
                            <span class="dikgedrukt">Bijzonderheden: </span>
                            <input type="text" name="bijzonderheden" value="<?= $_SESSION["resident"]['bijzonderheden'] ?>"><br><br>
                            <!-- <div class="zorgbehoefteinfo">
                                <span class="dikgedrukt">Zorgbehoefte:</span><br>
                                <textarea class="zorgbehoeftecontent" name="behoefte"></textarea>
                            </div> -->
                        </div>
                        <div class="VerijderRes">
                            <input class="Bewerk" type="submit" value="Opslaan" name="Updated">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    .ceckbox-container {
    display:inline-flex;
    flex-wrap: wrap;
    height: 205px;
    flex-direction: column;
}

.item{
   margin-right: 20px;
   margin-bottom: 5px;
}

.item2{
    margin-right: 20px;
    margin-bottom: 5px;
}
</style>
</html>