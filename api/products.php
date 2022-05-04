<?php

require 'database.php';

// $_SERVER['REQUEST_METHOD'] => Méthode de requête utilisée pour accéder à la page (par exemple 'GET', 'HEAD', 'POST', 'PUT')
$request_method = $_SERVER['REQUEST_METHOD'];

    switch ($request_method) {
        case 'GET':
            if (!empty($_GET["id"])) {
                // Récupérer un seul produit
                $id = intval($_GET["id"]);
                // $stmt = $pdo->prepare("SELECT * From produits WHERE id = $id");
                // $stmt->execute();
                // $resultat = $stmt->fetchAll();
                // return $resultat;
                var_dump($resultat);
            } else {
                // Récupérer tous les produits
                // $stmt = $pdo->prepare("SELECT * From produits");
                // $stmt->execute();
                // $resultat = $stmt->fetchAll();
                // return $resultat;
                var_dump($resultat);
            }
            break;
            case 'POST':

                break;
                case 'PUT':

                    break;
                    case 'DELETE':
 
                        break;
        
        default:
            // Requête invalide
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }




?>