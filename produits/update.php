<?php

// Entête HTTP nécessaire au bon fonctionnement de l'API
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: PUT");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/* ATTENTION !!! La requête "PUT" utilisée exactement comme décrit dans le standard HTTP doit :
    - Mettre à jour l'enregistrement si il existe
    - Créer l'enregistrement si il n'existe pas*/

// On vérifie qu'il s'agit de la méthode PUT. Si ce n'est pas le cas on envoie un message d'erreur
if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    // La bonne méthode est utilisée
    include_once '../database/Database.php';
    include_once '../models/Produits.php';

    $database = new Database();
    $db = $database->getConnection();

    $produit = new Produits($db);

    // On récupère les données reçues
    $donnees = json_decode(file_get_contents("php://input"));

    // On vérifie qu'on a bien toutes les données
    if(!empty($donnees->id_produit) && !empty($donnees->name_statut)){

        // On hydrate l'objet
        $produit->id_produit = $donnees->id_produit;
        $produit->name_statut = $donnees->name_statut;

        if($produit->update()){
            // Ici la modification a fonctionné
            // On envoie un code 200
            http_response_code(200);
            echo json_encode(["message" => "La modification a été effectuée"]);
        }else{
            // Ici la création n'a pas fonctionné
            // On envoie un code 503
            http_response_code(503);
            echo json_encode(["message" => "La modification n'a pas été effectuée"]);         
        }
    }
}else{
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}


?>