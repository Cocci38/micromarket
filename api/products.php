<?php
// // var_dump($_SERVER['REQUEST_METHOD']);
// // require 'database/Database.php';
// // require_once 'models/Produits.php';

// // $_SERVER['REQUEST_METHOD'] => Méthode de requête utilisée pour accéder à la page (par exemple 'GET', 'HEAD', 'POST', 'PUT')
// $request_method = $_SERVER['REQUEST_METHOD'];

//     switch ($request_method) {
//         case 'GET':
//             if (!empty($_GET["id"])) {
//                 // Récupérer un seul produit
//                 $id = intval($_GET["id"]);
//                 // $stmt = $pdo->prepare("SELECT * From produits WHERE id = $id");
//                 // $stmt->execute();
//                 // $resultat = $stmt->fetchAll();
//                 // return $resultat;
//                 //var_dump($resultat);
//             } else {
//                  // On instancie la base de données
//                 $database = new Database();
//                 $db = $database->getConnection();

//                 // On instancie les produits
//                 $produit = new Produits($db);
//                 var_dump($produit);
//                 // On récupère les données
//                 $stmt = $produit->read();

//                 // On vérifie si on a au moins 1 produit
//                 if($stmt->rowCount() > 0){
//                     // On initialise un tableau associatif
//                     $tableauProduits = [];
//                     $tableauProduits['produits'] = [];

//                     // On parcourt les produits
//                     while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                         extract($row);

//                         $prod = [
//                             //"id_produit" => $id_produit,
//                             "code" => $code,
//                             "description" => $description,
//                             "price" => $price,
//                             "name_category" => $name_category,
//                             "name_statut" => $name_statut,
//                             "name_supplier" => $name_supplier,
//                             "adresse_supplier" => $adresse_supplier,
//                             "purchase_date" => $purchase_date,
//                             "expiration_date" => $expiration_date
//                         ];

//                         $tableauProduits['produits'][] = $prod;
//                     }

//                     // On envoie le code réponse 200 OK
//                     http_response_code(200);

//                     // On encode en json et on envoie
//                     echo json_encode($tableauProduits);

//             }else{
//                 // On gère l'erreur
//                 http_response_code(405);
//                 echo json_encode(["message" => "La méthode n'est pas autorisée"]);
//             }
//             }
//             break;
//             case 'POST':

//                 break;
//                 case 'PUT':

//                     break;
//                     case 'DELETE':
 
//                         break;
//                         case 'COPY':
//                             $COPY = array(); //tableau qui va contenir les données reçues
//                             parse_str(file_get_contents('php://input'), $COPY);
//                             //var_dump($COPY);

//     // On inclut les fichiers de configuration et d'accès aux données
//     // include_once 'database/Database.php';
//     // include_once 'models/Produits.php';
    
//     // On instancie la base de données
//     $database = new Database();
//     $db = $database->getConnection();

//     // On instancie les produits
//     $produit = new Produits($db);

//     //var_dump($produit);
//     // if(!empty($donnees->id_produit)){
//     //     //var_dump($donnees); die();
//     //     $produit->id_produit = $donnees->id_produit;

//         if ($produit->duplicate($COPY)) {
//             // Ici la création a fonctionné
//             // On envoie un code 201
//             //var_dump($produit);
//             http_response_code(201);
//             echo json_encode(["message" => "L'ajout a été effectué"]);
//         }else{
//             // Ici la création n'a pas fonctionné
//             // On envoie un code 503
//             http_response_code(503);
//             echo json_encode(["message" => "L'ajout n'a pas été effectué"]); 
//         }
// // }
//                             break;
        
//         default:
//             // Requête invalide
//             header("HTTP/1.0 405 Method Not Allowed");
//             break;
//     }




?>