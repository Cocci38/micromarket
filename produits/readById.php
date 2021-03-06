<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../database/Database.php';
    include_once '../models/Produits.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les produits
    $produit = new Produits($db);

    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->id_produit)){
        $produit->id_produit = $donnees->id_produit;

        // On récupère le produit
        $produit->readById();

        // On vérifie si le produit existe
        if($produit->code != null){

            $prod = [
                "id_produit" => $produit->id_produit,
                "code" => $produit->code,
                "description" => $produit->description,
                "price" => $produit->price,
                "name_category" => $produit->name_category,
                "name_statut" => $produit->name_statut,
                "name_supplier" => $produit->name_supplier,
                "adresse_supplier" => $produit->adresse_supplier,
                "purchase_date" => $produit->purchase_date,
                "expiration_date" => $produit->expiration_date
            ];
            // On envoie le code réponse 200 OK
            http_response_code(200);
            // On encode en json et on envoie
            echo json_encode($prod);
        }else{
            // 404 Not found
            http_response_code(404);

            echo json_encode(array("message" => "Le produit n'existe pas."));
        }
    }
}else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>