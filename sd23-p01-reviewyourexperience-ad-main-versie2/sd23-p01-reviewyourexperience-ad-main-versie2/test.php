<?php
try {
    include_once('classes/Fiets.php');
    $db=new PDO("mysql:host=localhost;dbname=ad_bikes",
        "root", "");
} catch(PDOException $e) {
    die('Geen database server actief');
}

$query = $db->prepare('SELECT * FROM fietsen');
$query->execute();
$fietsen=$query->fetchALL(PDO::FETCH_CLASS,'Fiets' );
// var_dump($fietsen);

foreach ($fietsen as $fiets):?>
    <?=$fiets->category?>
    <img src="img/racefietsen/<?=$fiets->img?>" alt="image not visible">
    <br>
<?php endforeach;?>