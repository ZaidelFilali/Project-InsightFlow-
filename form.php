<?php
require_once('functionstash.php');

if (isset($_POST["submit"]) || isset($_POST["subter"])) {
    $naam = $_POST["naam"];
    $woningnummer = $_POST["woningnummer"];
    $activiteiten = isset($_POST["activiteiten"]) ? $_POST["activiteiten"] : NULL;
    $bijzonderheden = $_POST["bijzonderheden"];
    if (isset($_POST["checked"])) {
        foreach ($_POST["checked"] as $actadd) {
            Add2activitie($conn, $actadd, $naam);
        }
    }
    if (!ctype_digit($woningnummer)) {
        $message = "Woningnummer moet alleen uit cijfers bestaan!";
    } else {
        $query = "INSERT INTO bewoners (naam, woningnummer, activiteiten, bijzonderheden) 
        VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt->execute([$naam, $woningnummer, $activiteiten, $bijzonderheden])) {
            if(isset($_POST["subter"])) {
                header("Location: index.php");
                exit(); 
            } else {
                $message =  $_POST["naam"] . " is toegevoegd";
            }
        } else {
            $message = "Er is iets misgegaan!";
        }
    }
}
    $activities = Getactivities($conn, "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/7a27c8d597.js" crossorigin="anonymous"></script>
    <title>Nieuwe bewoner</title>
</head>

<body>
<div class="nav-container">
<div class="navbar">
        <form action="index.php" method="POST">
            <button type="submit" name="submit" class="logo">
                <img src="afbeeldingen/flowlogo.png" alt="logo" height="91px" width="110px">
            </button>
        </form>
        <h1>Bewoner toevoegen</h1>
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
            <a href="index.php">Terug</a>
        </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Nieuwe Bewoner</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($message)) { ?>
                            <div class="message"><?php echo $message; ?></div>
                        <?php } ?>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="naam">Naam</label>
                                <input type="text" id="naam" name="naam" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="woningnummer">Woningnummer</label>
                                <input type="text" id="woningnummer" name="woningnummer" class="form-control" required pattern="\d+" title="Voer alstublieft alleen cijfers in" />
                            </div>
                            <div class="form-group">
                                <span class="dikgedrukt">Activiteiten: </span><br>
                                <?php foreach ($activities as $activity) { ?>
                                        <input type="checkbox" name="checked[]" value="<?= $activity['Activiteit'] ?>"><?= $activity['Activiteit'] ?><br>
                                <?php } ?>
                                <br>
                            </div>
                            <div class="form-group">
                                <label for="bijzonderheden">Bijzonderheden</label>
                                <input type="text" id="bijzonderheden" name="bijzonderheden" class="form-control" />
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Toevoegen</button>
                                <button type="submit" name="subter" class="btn btn-primary">Toevoegen en terug</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

    h1{
            margin-right: 30px;
        }

    button{
    border: none;
    height: 100px;
}

    .back-btn {
        margin-top: 20px;
        margin-left: 35px;
    }

    .nav-container{
            width: 100%;
            height: 100px;
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

    .container {
        width: 400px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .container h3 {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }

    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #e46eca;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .btn-primary {
        background-color: #e46eca;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
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
</style>