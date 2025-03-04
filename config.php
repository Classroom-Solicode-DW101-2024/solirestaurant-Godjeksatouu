<?php
session_start();
$host ='localhost';
$dbname ='restausoli';
$username ='root';
$password ='';
try {
    
    $pdo= new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database";
}
catch (PDOException $e) {
    die ("ERROR: Could not connect. " . $e->getMessage());
}

function getLastIdClient() {
global $pdo;
    $sql = "SELECT MAX(idClient) AS maxId FROM client";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result['maxId'])) {
        $MaxId = 0;
    } else {
        $MaxId = $result['maxId'];
    }
    return $MaxId;
}

function tel_existe($tel){
    global $pdo;
    $sql = "SELECT * FROM client where telCl=:tel";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tel', $tel);
    $stmt->execute();
    $rusult = $stmt->fetch(PDO::FETCH_ASSOC);
    return $rusult;
}


function getdishesByType($type){
    global $pdo;
    $sql = "SELECT * FROM plat WHERE TypeCuisine=:type";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rusult;
}

function getKitchenType(){
    global $pdo;
    $sql = "SELECT distinct TypeCuisine  FROM plat ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rusult;
}
function getAllCuisineTypes() {
    global $conn; // Access the global connection variable
    
    if ($conn === null) {
        return []; // Return empty array if no connection
    }
    
    try {
        $stmt = $conn->prepare("SELECT DISTINCT TypeCuisine FROM typecuisine ORDER BY TypeCuisine");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
?>