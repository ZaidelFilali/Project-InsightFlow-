<?php

require_once('functionstash.php');
// var_dump($_POST);
if (isset($_POST["Terug"])) {
    unset($_SESSION['activitiename']);
    unset($activitiename);
    unset($_SESSION["players"]);
    unset($_SESSION["Lastadded"]);
    unset($_SESSION["Lastremoved"]);
    unset($_SESSION['newactname']);
}
if (isset($_POST["DefDelete"])) {
    Deleteact($conn, $_SESSION['activitiename']);
    unset($_SESSION['activitiename']);
    unset($activitiename);
    unset($_POST);
}
if (isset($_POST["addact"])) {
    addact($conn, $_SESSION["newactname"], $_SESSION["players"]);
    unset($_SESSION["players"]);
    unset($_SESSION["newactname"]);
}

if (isset($_POST["Delete"])) {
    removeActivity($conn, $_POST["Delete"]);
}
// Haal de activiteiten op
$activities = Getactivities($conn, "");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activiteiten</title>
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E5E7EB;
            padding-top: 0px;
            margin: 0px;
            background-color: #E5E7EB; 
            font-family: 'Roboto', sans-serif;
        }

        p{
    margin: 0px;
}


        .navbar {
        position: fixed;
        background-color: white;
        height: 100px;
        width: 100%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
        flex-direction: row;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0px;
        padding: 0px;
        font-size: small;
        z-index: 999;
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

  input{
    width: 150px;
    margin: 5px 0px;
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
        cursor: pointer;
        background-color: #fff;
        border: none;
        }

        .navbar-brand img {
            height: 80px;
            width: auto;
        }

        .navbar-text {
            margin: 0 auto;
            font-size: 24px;
            font-weight: bold;
        }

        .activity-card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .activity-card {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            background-color: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .activity-card h4 {
            color: #721c24;
            font-size: 18px;
        }
        

        .btn {
            background-color: #e46eca;
            border-color: #e46eca;
            color: #fff;
        }

        .btn2 {
            background-color: #e46eca;
            border: none;
            color: #fff;
            width: auto;
        }

        .btn2:hover{
            background-color: #d640b2;
            border-color: #d640b2;
        }

        .btn1{
            background-color: whitesmoke;
            border: none;
            color: red;
            width: auto;
        }

        .btn1:hover{
            background-color: #f0ecec;
            color: red;
        }

        .btn:hover {
            background-color: #d640b2;
            border-color: #d640b2;
        }
        .nav-container{
            width: 100%;
            height: 100px;
        }

        h1{
            margin-right: 30px;
        }
    </style>
</head>

<body>
    <div class="nav-container">
        <div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/issweardelogo.png" alt="logo" height="95px" width="110px">
            </button>
        </form>
        <h2 style="font-weight: 600;">Hobby/Intresses</h2>
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
        <?php
        if (isset($_POST["logout"])) {
            $_SESSION["acces"] = "nada";
            header("Location: login.php");
        }
        ?>
    </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <form action="index.php" method="POST">
                    <button class="btn btn-secondary" type="submit" name="Terug">Terug</button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="addact.php" method="POST">
                    <button class="btn btn-primary float-right" type="submit" name="New">+ Toevoegen</button>
                </form>
            </div>
        </div>
        <div class="activity-card-container mt-4">
            <?php
            if (isset($activities) && !empty($activities)) :
                foreach ($activities as $activity) { ?>
                    <div class="activity-card">
                        <div class="row">
                            <div class="col-md-8">
                                <h4><?= $activity['Activiteit'] ?></h4>
                                <?php
                                $participants = json_decode($activity['Deelnemers']);
                                foreach ($participants as $participant) {
                                    echo $participant . "<br>";
                                }
                                ?>
                            </div>
                            <div class="col-md-4">
                                <form action="activiteit.php" method="POST">
                                    <button class="btn1 btn-info btn-sm  float-right mr-1" type="submit" name="Delete">Verwijder</button>
                                    <input type="hidden" value="<?= $activity['activiteit_id'] ?>" name="Delete" id="Delete">
                                </form>
                                
                                    <form action="actedit.php" method="POST">
                                    <button class="btn2 btn-info btn-sm float-right mr-2" type="submit" name="submit">Bewerk</button>
                                    <input type="hidden" value="<?= $activity['Activiteit'] ?>" name="edit">
                                </form>

                            </div>
                        </div>
                    </div>
                <?php
                }
            else : ?>
                <p style="display: flex; justify-content: center;">Er zijn geen activiteiten beschikbaar.</p>
            <?php endif; ?>
        </div>
    </div>
    </div>

    <script>
        function removeActivityCard(card) {
            card.parentNode.removeChild(card);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var card = button.closest('.activity-card');
                    removeActivityCard(card);
                });
            });
        });

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
</body>

</html>