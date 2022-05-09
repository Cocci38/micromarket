<?php
// Pour insérer les images stocker dans un dossier dans la bdd 

// Connexion à la base de donnée
require_once "./database/Database.php";
$connection = new Database;
$pdo = $connection->getConnection();

// scandir() => Pour scanner un répertoire
$directory=scandir("assets/");

// Insertion des images dans la base de donnée
for ($i=2; $i < count($directory); $i++) { 
    $sth=$pdo->prepare("INSERT INTO assets (chemin, name_asset) VALUES ('assets/', :name_asset)");
    $sth->bindParam(":name_asset",$directory[$i],PDO::PARAM_STR);
    $sth->execute();
}
?>