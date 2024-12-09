<?php

require_once('functionstash.php');
if (isset($_POST["id"])) {
    $_SESSION["resident"] = getresident($conn, $_POST["id"]);
} elseif (!isset($_SESSION["resident"])) {
    header("Location: index.php");
}

if (isset($_POST["Updated"])) {
    $activities = Getactivities($conn, "");
    foreach ($activities as $activitie) {
        if (strstr($activitie["Deelnemers"], $_SESSION["resident"]['naam'])) {
            Removeactivitie($conn, $activitie["Activiteit"], $_SESSION["resident"]['naam']);
        }
    }
    if (isset($_POST["checked"])) {
        foreach ($_POST["checked"] as $actadd) {
            Add2activitie($conn, $actadd, $_POST["naam"]);
        }
    }
    UpdateDetails($conn, $_SESSION["resident"]["id"], $_POST);
    $_SESSION["resident"] = getresident($conn, $_SESSION["resident"]["id"]);
}

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
            <button type="submit" name="Terug" class="logo">
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
                <form action="index.php" method="POST">
                    <input class="back" type="submit" value="<" name="Terug"></input>
                </form>
                <h2 class="text2">Infomatie bewoner:<h2>
            </div>
            <div class="bewoner-container">
    <div class="bewoner">
        <?php Echodetailres($conn, $_SESSION["resident"]) ?>
        <div class="VerijderRes">
            <form action="edit.php" method="POST">
                <input class="Bewerk" type="submit" value="Bewerk Bewoner" name="Bewerk">
            </form>
            <form action="index.php" method="POST" onsubmit="return submitForm(this);">
                <input class="Delete" type="submit" value="Verwijder Bewoner" name="DeleteRes">
                <input type="hidden" value="<?= $_SESSION["resident"]['naam'] ?>" name="delres">
            </form>
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

    // Close the dropdown menu if the user clicks outside of it
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

    function submitForm(form) {
        swal({
                title: "Weet u het zeker?",
                text: value = "Verwijder: <?= $_SESSION["resident"]['naam'] ?>",
                dangerMode: true,
                buttons: {
                    cancel: {
                        text: "Annuleer",
                        className: "Annuleer",
                        visible: true,
                    },
                    delete: {
                        text: "Verwijder",
                        value: "delete",
                        className: "VerwijderBewoner",
                    }

                }
            })
            .then((isOkay) => {
                if (isOkay) {
                    form.submit();
                }
            });
        return false;
    }
</script>