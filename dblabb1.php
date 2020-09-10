<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>dblabb1</title>
</head>
<body>

<?php
/* dblabb1.php */

// inkludera pdoext där vi angav mysql-uppgifter
include 'dblabb2.php';

// skapa en ny instans av DB
$db = new DB();

// En enkel query
$query = "SELECT * FROM user";

// Om vi får ett resultat, loopa då igenom detta och skriv ut varje rad
if ($result = $db->query($query)) {
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        // resultatet är en array, vi formaterar med <pre> och skriver ut
        // med print_r
        echo "<pre>" . print_r($row, 1) . "</pre>";
    }
}

// Mer avancerad query
$query = "SELECT username, firstname, lastname, birthdate, address 
          FROM user LEFT JOIN userdata ON userdata.userid = user.id WHERE user.id=(:id)";

// Om query är ok
if ($sth = $db->prepare($query)) {

    // kör query med värden utbytta 
    $sth->execute(array(':id' => '1'));
    
    // resultat som en associativ array
    $result = $sth->fetch(PDO::FETCH_ASSOC);

    // skriv ut
    echo $result['username'] . "<br>";
    echo $result['firstname'] . " " .$result['lastname'] . "<br>";
    echo $result['birthdate'];
}