<?php

require_once '../database/Database.php';
class Produits{
    
    protected $conn;
    protected $table = "produits";

    public $id;
    public $code;
    public $description;
    public $price;
    public $category_id;
    public $statut_id;
    public $supplier_id;
    public $purchase_date;
    public $expiration_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " SET code=:code, description=:description, price=:price, category_id=:category_id, statut_id=:statut_id, supplier_id=:supplier_id, purchase_date=:purchase_date, expiration_date=:expiration_date");

        // Protection contre les injections : 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->statut_id=htmlspecialchars(strip_tags($this->statut_id));
        $this->supplier_id=htmlspecialchars(strip_tags($this->supplier_id));
        $this->purchase_date=htmlspecialchars(strip_tags($this->purchase_date));
        $this->expiration_date=htmlspecialchars(strip_tags($this->expiration_date));

        // Ajout des données protégées : 
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":statut_id", $this->statut_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);
        $stmt->bindParam(":purchase_date", $this->purchase_date);
        $stmt->bindParam(":expiration_date", $this->expiration_date);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read()
    {
        $stmt = $this->conn->prepare("SELECT * From produits");
        $stmt->execute();
        return $stmt; // Il n'a pas de fetch, il sera effectué dans le fichier de l'Api (read.php)
    }

    public function readById(int $id)
    {
        $stmt = $this->conn->prepare("SELECT * From produits WHERE id = ?", [$id]);
        $stmt->execute();
        return $stmt;

        // Hydradation
        // $this->code = $result['code'];
        // $this->description = $result['description'];
        // $this->price = $result['price'];
        // $this->category_id = $result['category_id'];
        // $this->statut_id = $result['statut_id'];
        // $this->supplier_id = $result['supplier_id'];
        // $this->purchase_date = $result['purchase_date'];
        // $this->expiration_date = $result['expiration_date'];
    }

    public function update()
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET code = :code, description = :description, price = :price, category_id = :category_id, statut_id = :statut_id, supplier_id = :supplier_id, purchase_date = :purchase_date, expiration_date = :expiration_date WHERE id= :id");

        // Protection contre les injections : 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->statut_id=htmlspecialchars(strip_tags($this->statut_id));
        $this->supplier_id=htmlspecialchars(strip_tags($this->supplier_id));
        $this->purchase_date=htmlspecialchars(strip_tags($this->purchase_date));
        $this->expiration_date=htmlspecialchars(strip_tags($this->expiration_date));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // Ajout des données protégées : 
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":statut_id", $this->statut_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);
        $stmt->bindParam(":purchase_date", $this->purchase_date);
        $stmt->bindParam(":expiration_date", $this->expiration_date);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function delete()
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}


?>