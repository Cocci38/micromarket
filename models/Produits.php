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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " SET code=:code, description=:description, price=:price, statut_id=:statut_id, supplier_id=:supplier_id, purchase_date=:purchase_date, expiration_date=:expiration_date");

        // Protection contre les injections : 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->statut_id=htmlspecialchars(strip_tags($this->statut_id));
        $this->supplier_id=htmlspecialchars(strip_tags($this->supplier_id));
        $this->purchase_date=htmlspecialchars(strip_tags($this->purchase_date));
        $this->expiration_date=htmlspecialchars(strip_tags($this->expiration_date));
        $this->name_category=htmlspecialchars(strip_tags($this->name_category));

error_log("cat :".print_r($this->name_category,1));

        // Ajout des données protégées : 
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":statut_id", $this->statut_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);
        $stmt->bindParam(":purchase_date", $this->purchase_date);
        $stmt->bindParam(":expiration_date", $this->expiration_date);
        $stmt->execute();

        $produitId = $this->conn->lastInsertId();

        $cat = $this->conn->prepare("SELECT id_category FROM category WHERE name_category LIKE :name_category");
        $cat->bindParam(":name_category",  $this->name_category); 
        $cat->execute();
        $resultCat = $cat->fetch(PDO::FETCH_ASSOC);
        extract($resultCat);
        var_dump($this->name_category);
        error_log(print_r($this->name_category,1));
        var_dump($id_category);
        $sql = $this->conn->prepare("INSERT INTO produit_category SET produit_id = :produit_id, category_id = :category_id");

        $sql->bindParam(':produit_id', $produitId);
        $sql->bindParam(':category_id', $id_category);

        if ($sql->execute()) {
            return true;
        }
        return false;
    }

    public function read()
    {
        $stmt = $this->conn->prepare("SELECT * FROM produits");
        $stmt->execute();
        return $stmt; // Il n'a pas de fetch, il sera effectué dans le fichier de l'Api (read.php)
    }

    public function readById()
    {
        $stmt = $this->conn->prepare("SELECT * FROM produits WHERE id_produit = ?");
        // --JOIN category ON category_id = category.id  JOIN statut ON statut_id = statut.id 
        $stmt->bindParam(1, $this->id_produit);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hydradation
        $this->code = $result['code'];
        $this->description = $result['description'];
        $this->price = $result['price'];
        // $this->name_category = $result['name_category'];
        // $this->name = $result['name'];
        $this->statut_id = $result['statut_id'];
        $this->supplier_id = $result['supplier_id'];
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
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":statut_id", $this->statut_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);
        $stmt->bindParam(":purchase_date", $this->purchase_date);
        $stmt->bindParam(":expiration_date", $this->expiration_date);
        $stmt->bindParam(':id_produit', $this->id_produit);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function delete()
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id_produit = ?");
        $this->id_produit=htmlspecialchars(strip_tags($this->id_produit));
        $stmt->bindParam(1, $this->id_produit);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}


?>