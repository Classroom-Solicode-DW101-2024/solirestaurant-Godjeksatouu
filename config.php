<?php
session_start();
$host = 'localhost';
$dbname = 'restausoli';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database";
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

function generateOrderId() {
    global $pdo;
    $sql = "SELECT MAX(idCmd) AS maxId FROM commande";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxId = $result['maxId'] ?? 0;
    return str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
}


function getLastIdClient() {
    global $pdo;
    $sql = "SELECT MAX(idClient) AS maxId FROM client";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($result['maxId'])) {
        $MaxId = 0;
    } else {
        $MaxId = $result['maxId'];
    }
    return $MaxId;
}

function tel_existe($tel) {
    global $pdo;
    $sql = "SELECT * FROM client where telCl=:tel";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tel', $tel);
    $stmt->execute();
    $rusult = $stmt->fetch(PDO::FETCH_ASSOC);
    return $rusult;
}

function getdishesByType($type) {
    global $pdo;
    $sql = "SELECT * FROM plat WHERE TypeCuisine=:type";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rusult;
}

function getKitchenType() {
    global $pdo;
    $sql = "SELECT distinct TypeCuisine FROM plat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rusult;
}

function getAllCuisineTypes() {
    global $pdo;
    
    if ($pdo === null) {
        return []; 
    }
    
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT TypeCuisine FROM plat ORDER BY TypeCuisine");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function searchDishes($searchTerm) {
    global $pdo;
    $searchTerm = "%$searchTerm%";
    $sql = "SELECT * FROM plat 
            WHERE nomPlat LIKE :search 
            OR categoryPlat LIKE :search 
            OR TypeCuisine LIKE :search";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDishById($id) {
    global $pdo;
    $sql = "SELECT * FROM plat WHERE idPlat = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>