<?php

require_once("queries.php");

function Process_login($conn, $submit)
{
    $ids = getids($conn);
    $username = Getusername($conn);
    $userpassword = Getuserpassword($conn);
    if (isset($submit["submit"])) {
        $_SESSION["gebruikersnaam"] = $_POST["username"];
        $_SESSION["wachtwoord"] = $_POST["psw"];
        foreach ($ids as $key => $value) {
            $entry = $value["ID"] - 1;  
            if ($_SESSION["gebruikersnaam"] === $username[$entry]["Gebruikersnaam"]) {
                if ($_SESSION["wachtwoord"] === $userpassword[$entry]["Wachtwoord"]) {
                    echo 'welkom ' . $_SESSION["gebruikersnaam"];
                    $_SESSION["acces"] = "approved";
                    $userid = Getuserid($conn, $_POST["gebruikersnaam"]);
                    $_SESSION["loggedInUser"] = $userid;
                    header("Location: index.php");
                } else {
                    echo 'username or password invalid';
                }
            } else {
                echo 'username or password invalid';
            }
        }
    }
}

function Process_changepass($conn, $newinfo, $user, $password)
{
    if ($password === $newinfo["OP"]) {
        UpdatePass($conn, $user, $newinfo["NP"]);
    }
}

function Echoresident($residents)
{
    echo '<div class="personen">'; //allemaal
    foreach ($residents as $resident) {
        echo '<div class="persoon">'; // individueel
        echo '<div class="persoonnaam">';
        echo $resident['naam'] . ':' . "<br><br>";
        echo "<td><form action='detail.php' method='POST'>";
        echo '<button class="infobutton" name="id" value=' . $resident["id"] . '>';
        echo '<i class="fa-solid fa-circle-info" style="display: flex;   color: #e46eca; font-size: xx-large;"></i>';
        echo '</button>';
        echo "</form></td>";
        echo "</div>";
        echo '<span class="dikgedrukt">Woning: </span>';
        echo $resident['woningnummer'] . "<br>";
        echo '<span class="dikgedrukt">Activiteiten: </span>';
        echo $resident['activiteiten'] . "<br>";
        echo '<span class="dikgedrukt">Bijzonderheden: </span>';
        if (isset($resident['bijzonderheden'])) {
            echo 'Ja';
        } else {
            echo 'Nee';
        }
        echo "</div>";
    }
    echo "</div>";
}

function Echodetailres($conn, $resident)
{
    echo'<div class="infopersoon">';
    echo '<div class="infopersoonnaam">';
    echo $resident['naam'] . ':' . "<br><br>";
    echo "</div>";
    echo '<div class="gap">';
    echo "</div>";
    echo '<div class="infocontent">';
    echo '<span class="dikgedrukt">Woning: </span>';
    echo $resident['woningnummer'] . "<br>";
    echo '<div class="gap">';
    echo "</div>";
    echo '<span class="dikgedrukt">Activiteiten: </span>';
    $activities = Getresact($conn, $resident['naam']);
    if (!empty($activities)) {
        foreach ($activities as $onnodig => $activitie) {
            echo "- " . $activitie[0] . " ";
        }
        echo "<br>";
    } else {
        echo "Niet van toepassing<br>";
    }
    echo '<div class="gap">';
    echo "</div>";
    echo '<span class="dikgedrukt">Bijzonderheden: </span>';
    if (isset($resident['bijzonderheden'])) {
        echo $resident['bijzonderheden'] . "<br><br><br>";
    } else {
        echo 'NVT' . "<br><br>";
    }
    // echo '<div class="zorgbehoefteinfo">';
    // echo '<span class="dikgedrukt">Behoeftes:</span>' . "<br>";
    // echo '<textarea class="zorgbehoeftecontent" name="behoefte"></textarea>';
    // echo"</div>";
    echo"</div>";
}

function Add2activitie($conn, $act, $player)
{
    $data = $conn->query("SELECT * FROM `Activiteiten` WHERE Activiteit = '$act'")->fetch();
    $deelnemers = json_decode($data['Deelnemers']);
    $deelnemers[] = $player;
    $jsonencode = json_encode($deelnemers);
    $sqlUpdate = "UPDATE Activiteiten SET deelnemers = '$jsonencode' WHERE Activiteit = '$act'";
    $conn->query($sqlUpdate);
}

function Removeactivitie($conn, $act, $player)
{
    $data = $conn->query("SELECT * FROM `Activiteiten` WHERE Activiteit = '$act'")->fetch();
    $deelnemers = json_decode($data['Deelnemers']);
    $key = array_search($player, $deelnemers);
    unset($deelnemers[$key]);
    $deelnemers = array_values($deelnemers);
    $jsonencode = json_encode($deelnemers);
    $sqlUpdate = "UPDATE Activiteiten SET deelnemers = '$jsonencode' WHERE Activiteit = '$act'";
    $conn->query($sqlUpdate);
}

?>