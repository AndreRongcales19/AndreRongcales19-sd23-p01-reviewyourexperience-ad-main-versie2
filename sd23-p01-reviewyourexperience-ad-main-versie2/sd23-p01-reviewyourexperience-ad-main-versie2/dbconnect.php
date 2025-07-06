<?php
try {
    include_once('classes/Fiets.php');
    $db=new PDO("mysql:host=localhost;dbname=ad_bikes",
        "root", "");
} catch(PDOException $e) {
    die('Geen database server actief');
}
?>