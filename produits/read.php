<?php

// Entête HTTP nécessaire au bon fonctionnement de l'API
// Accès depuis n'importe quel site ou appareil (*) (Pour un accès restreint, mettre l'adresse d'un site à la place de *)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: GET");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie qu'il s'agit de la méthode GET. Si ce n'est pas le cas on envoie un message d'erreur
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

    // On récupère les données
    $stmt = $produit->read();

    // On vérifie si on a au moins 1 produit
    if($stmt->rowCount() > 0){
        // On initialise un tableau associatif
        $tableauProduits = [];
        $tableauProduits['produits'] = [];

        // On parcourt les produits
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $prod = [
                "id_produit" => $id_produit,
                "code" => $code,
                "description" => $description,
                "price" => $price,
                "statut_id" => $statut_id,
                "supplier_id" => $supplier_id,
                "purchase_date" => $purchase_date,
                "expiration_date" => $expiration_date
            ];

            $tableauProduits['produits'][] = $prod;
        }

        // On envoie le code réponse 200 OK
        http_response_code(200);

        // On encode en json et on envoie
        echo json_encode($tableauProduits);
    }

}else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

?>