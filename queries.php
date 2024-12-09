<?php

session_start();
$dbhost = "localhost";
$dbname = "isselwearde";
$dbuser = "Isselwearde";
$dbpass = "URsMW9Uiq0NIIw*k";

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
} catch (PDOException $error) {
    echo "Er is een verbindingsprobleem met de database: " . $error->getMessage();
    exit();
}

function getresidents($conn, $order)
{
    $stmt = $conn->prepare("SELECT * FROM `bewoners` $order");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getresident($conn, $resid)
{
    $stmt = $conn->prepare("SELECT * FROM `bewoners` WHERE `ID` = ?");
    $stmt->execute([$resid]);
    return $stmt->fetch();
}

function getresidentsnamesonly($conn, $order)
{
    $stmt = $conn->prepare("SELECT `naam` FROM `bewoners` $order");
    $stmt->execute();
    return $stmt->fetchAll();
}

function Getuserid($conn, $loggedinname)
{
    $stmt = $conn->prepare("SELECT ID FROM `gebruikers` WHERE `Gebruikersnaam` = ?");
    $stmt->execute([$loggedinname]);
    return $stmt->fetch();
}

function Getids($conn)
{
    $stmt = $conn->prepare("SELECT ID FROM `gebruikers`");
    $stmt->execute();
    return $stmt->fetchAll();
}

function Getusername($conn)
{
    $stmt = $conn->prepare("SELECT Gebruikersnaam FROM `gebruikers`");
    $stmt->execute();
    return $stmt->fetchAll();
}

function Getuserpassword($conn)
{
    $stmt = $conn->prepare("SELECT Wachtwoord FROM `gebruikers`");
    $stmt->execute();
    return $stmt->fetchAll();
}

function Filterresidents($conn, $post, $From = "bewoners")
{
    $sql = "SELECT * FROM $From WHERE 1=1";
    $params = [];

    if (!empty($post['username'])) {
        $sql .= " AND naam LIKE ?";
        $params[] = '%' . $post['username'] . '%';
    }

    if (!empty($post['roomnum'])) {
        $sql .= " AND woningnummer = ?";
        $params[] = $post['roomnum'];
    }

    if (!empty($post['activiteiten'])) {
        $sql .= " AND activiteiten = ?";
        $params[] = $post['activiteiten'];
    }

    if (!empty($post['geboorte'])) {
        $sql .= " AND geboorte_datum = ?";
        $params[] = $post['geboorte'];
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function Getactivities($conn, $select)
{
    return $conn->query("SELECT * FROM `Activiteiten` $select")->fetchall();
}

function Checkact($conn)
{
    $Actnames = [];
    $Allnames = $conn->prepare("SELECT `naam` FROM `bewoners`");
    $Allnames->execute();
    $Allnames = $Allnames->fetchAll();
    $act = Getactivities($conn, "");
    foreach ($act as $merger) {
        $merg = json_decode($merger['Deelnemers']);
        $Actnames = array_merge($Actnames, $merg);
    }
    foreach ($Allnames as $name) {
        $name = $name['naam'];
        foreach ($Actnames as $actname) {
            if ($name == $actname) {
                Updateact($conn, $name, 'Ja');
                break;
            } else {
                Updateact($conn, $name, 'Nee');
            }
        }
    }
}

function Getresact($conn, $res)
{
    $stmt = $conn->prepare("SELECT Activiteit FROM `Activiteiten` WHERE Deelnemers LIKE ?");
    $stmt->execute(['%' . $res . '%']);
    return $stmt->fetchAll();
}

function Updateact($conn, $resident, $state)
{
    $stmt = $conn->prepare("UPDATE `bewoners` SET `activiteiten` = ? WHERE `naam` = ?");
    $stmt->execute([$state, $resident]);
}

function UpdatePass($conn, $user, $newpassword)
{
    $stmt = $conn->prepare("UPDATE `Gebruikers` SET `Wachtwoord` = ? WHERE `Gebruikersnaam` = ?");
    $stmt->execute([$newpassword, $user]);
}

function Deleteact($conn, $actname)
{
    $stmt = $conn->prepare("DELETE FROM activiteiten WHERE Activiteit = ?");
    $stmt->execute([$actname]);
}

function addact($conn, $actname, $players)
{
    $jsonencode = json_encode($players);
    $stmt = $conn->prepare("INSERT INTO `activiteiten`(`Activiteit`, `Deelnemers`) VALUES (?, ?)");
    $stmt->execute([$actname, $jsonencode]);
}

function Deleteres($conn, $resident)
{
    $stmt = $conn->prepare("DELETE FROM bewoners WHERE naam = ?");
    $stmt->execute([$resident]);
}
function addPatient($conn, $naam, $woning, $activiteiten, $geboortedatum, $bijzonderheden)
{
    $query = "INSERT INTO `bewoners` (`naam`, `woning`, `activiteiten`, `geboortedatum`, `bijzonderheden`) 
    VALUES (:naam, :woning, :activiteiten, :geboortedatum, :bijzonderheden)";
    $query_run = $conn->prepare($query);
    $query_run->bindParam(':naam', $naam);
    $query_run->bindParam(':woning', $woning);
    $query_run->bindParam(':activiteiten', $activiteiten);
    $query_run->bindParam(':geboortedatum', $geboortedatum);
    $query_run->bindParam(':bijzonderheden', $bijzonderheden);
    $query_run->execute();
}

function removeActivity($conn, $id)
{
    $query = "DELETE FROM `activiteiten` WHERE `activiteit_id` = :id";

    $query_run = $conn->prepare($query);

    $query_run->bindParam(':id', $id);

    $query_run->execute();
}

function UpdateDetails($conn, $id, $Data)
{
    $sql = "UPDATE Bewoners SET naam = :naam, woningnummer = :woningnummer, bijzonderheden = :bijzonderheden WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':naam' => $Data["naam"],
        ':woningnummer' => $Data["woningnummer"],
        ':bijzonderheden' => $Data["bijzonderheden"],
        ':id' => $id
    ]);
}
