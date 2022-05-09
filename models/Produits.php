<?php

require_once '../database/Database.php';
class Produits{
    
    protected $conn;
    protected $table = "produits";

    public $id;
    public $code;
    public $description;
    public $price;
    public $statut_id;
    public $supplier_id;
    public $purchase_date;
    public $expiration_date;
    public $name_category;
    public $name_statut;
    public $name_supplier;
    public $adresse_supplier;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        // Je sélectionne l'id_statut grâce au nom pour ensuite l'insérer dans la table produits
        $statut = $this->conn->prepare("SELECT 	id_statut  FROM statut WHERE name_statut LIKE :name_statut");
        $statut->bindParam(":name_statut",  $this->name_statut, PDO::PARAM_STR); 
        $statut->execute();
        $resultstatut = $statut->fetch(PDO::FETCH_ASSOC);
        extract($resultstatut);

        // Je sélectionne l'id_supplier grâce au nom et l'adresse pour ensuite l'insérer dans la table produits
        $supplier = $this->conn->prepare("SELECT id_supplier  FROM suppliers WHERE name_supplier LIKE :name_supplier AND adresse_supplier LIKE :adresse_supplier");
        $supplier->bindParam(":name_supplier",  $this->name_supplier, PDO::PARAM_STR);
        $supplier->bindParam(":adresse_supplier",  $this->adresse_supplier, PDO::PARAM_STR);
        $supplier->execute();
        $resultsupplier = $supplier->fetch(PDO::FETCH_ASSOC);

        /* extract() => Importe les variables dans la table des symboles. 
            Vérifie chaque clé afin de contrôler si elle a un nom de variable valide. 
            Elle vérifie également les collisions avec des variables existantes dans la table des symboles.*/
        extract($resultsupplier);
        //error_log(print_r($resultsupplier,1));
        //var_dump($resultsupplier); die();

        // J'insère dans la table produits
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " SET code=:code, description=:description, price=:price, statut_id=:statut_id, supplier_id=:supplier_id, purchase_date=:purchase_date, expiration_date=:expiration_date");

        // Protection contre les injections : 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->name_statut=htmlspecialchars(strip_tags($this->name_statut));
        $this->name_supplier=htmlspecialchars(strip_tags($this->name_supplier));
        $this->adresse_supplier=htmlspecialchars(strip_tags($this->adresse_supplier));
        $this->purchase_date=htmlspecialchars(strip_tags($this->purchase_date));
        $this->expiration_date=htmlspecialchars(strip_tags($this->expiration_date));
        $this->name_category=htmlspecialchars(strip_tags($this->name_category));

        //error_log("cat :".print_r($this->name_category,1));

        // Ajout des données protégées : 
        $stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":price", $this->price, PDO::PARAM_INT);
        $stmt->bindParam(":statut_id", $id_statut, PDO::PARAM_INT);
        $stmt->bindParam(":supplier_id", $id_supplier, PDO::PARAM_INT);
        $stmt->bindParam(":purchase_date", $this->purchase_date, PDO::PARAM_STR);
        $stmt->bindParam(":expiration_date", $this->expiration_date, PDO::PARAM_STR);
        $stmt->execute();

        // lastInsertId() => Retourne l'identifiant de la dernière ligne insérée ou la valeur d'une séquence
        $produitId = $this->conn->lastInsertId();

        // Je sélectionne l'id_category grâce au nom pour ensuite l'insérer dans la table produit_category
        $cat = $this->conn->prepare("SELECT id_category FROM category WHERE name_category LIKE :name_category");
        $cat->bindParam(":name_category",  $this->name_category, PDO::PARAM_STR); 
        $cat->execute();
        $resultCat = $cat->fetch(PDO::FETCH_ASSOC);
        extract($resultCat);

        // error_log(print_r($this->name_category,1));
        // var_dump($id_category);

        // // J'insère dans la table produit_category l'id du produit et l'id de la catégorie
        $sql = $this->conn->prepare("INSERT INTO produit_category SET produit_id = :produit_id, category_id = :category_id");

        $sql->bindParam(':produit_id', $produitId, PDO::PARAM_INT);
        $sql->bindParam(':category_id', $id_category, PDO::PARAM_INT);

        if ($sql->execute()) {
            return true;
        }
        return false;
    }

    public function read()
    {
        $stmt = $this->conn->prepare("SELECT code, description, price,name_category, name_statut, name_supplier, adresse_supplier, purchase_date, expiration_date FROM produits 
        JOIN statut ON produits.statut_id = statut.id_statut
        JOIN suppliers ON produits.supplier_id = suppliers.id_supplier
        JOIN produit_category ON produits.id_produit = produit_category.produit_id
        JOIN category ON produit_category.category_id = category.id_category");
        $stmt->execute();
        return $stmt; // Il n'a pas de fetch, il sera effectué dans le fichier de l'Api (read.php)

        // $cat = $this->conn->prepare("SELECT name_category FROM category 
        // JOIN produit_category ON produit_category.category_id = category.id_category
        // WHERE produit_category.produit_id = ?");
        // $cat->execute();
        // return $cat;
    }

    public function readById()
    {
        $stmt = $this->conn->prepare("SELECT code, description, price,name_category, name_statut, name_supplier, adresse_supplier, purchase_date, expiration_date FROM produits 
        JOIN statut ON produits.statut_id = statut.id_statut
        JOIN suppliers ON produits.supplier_id = suppliers.id_supplier
        JOIN produit_category ON produits.id_produit = produit_category.produit_id
        JOIN category ON produit_category.category_id = category.id_category WHERE id_produit = ?");
        // --JOIN category ON category_id = category.id  JOIN statut ON statut_id = statut.id 
        $stmt->bindParam(1, $this->id_produit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hydradation
        $this->code = $result['code'];
        $this->description = $result['description'];
        $this->price = $result['price'];
        $this->name_category = $result['name_category'];
        $this->name_statut = $result['name_statut'];
        $this->name_supplier = $result['name_supplier'];
        $this->adresse_supplier = $result['adresse_supplier'];
        $this->purchase_date = $result['purchase_date'];
        $this->expiration_date = $result['expiration_date'];
    }

    public function update()
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET code = :code, description = :description, price = :price, statut_id = :statut_id, supplier_id = :supplier_id, purchase_date = :purchase_date, expiration_date = :expiration_date WHERE id_produit = :id_produit");

        // Protection contre les injections : 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->statut_id=htmlspecialchars(strip_tags($this->statut_id));
        $this->supplier_id=htmlspecialchars(strip_tags($this->supplier_id));
        $this->purchase_date=htmlspecialchars(strip_tags($this->purchase_date));
        $this->expiration_date=htmlspecialchars(strip_tags($this->expiration_date));
        $this->id_produit=htmlspecialchars(strip_tags($this->id_produit));

        // Ajout des données protégées : 
        $stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":price", $this->price, PDO::PARAM_INT);
        $stmt->bindParam(":statut_id", $this->statut_id, PDO::PARAM_INT);
        $stmt->bindParam(":supplier_id", $this->supplier_id, PDO::PARAM_INT);
        $stmt->bindParam(":purchase_date", $this->purchase_date, PDO::PARAM_STR);
        $stmt->bindParam(":expiration_date", $this->expiration_date, PDO::PARAM_STR);
        $stmt->bindParam(':id_produit', $this->id_produit, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function delete()
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id_produit = ?");
        $this->id_produit=htmlspecialchars(strip_tags($this->id_produit));
        $stmt->bindParam(1, $this->id_produit, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>