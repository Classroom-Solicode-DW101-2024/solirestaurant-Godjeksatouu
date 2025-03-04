<?php include 'navbar.php'; ?>
<?php 
require "config.php";

// Récupérer les types de cuisine même si aucun client n'est connecté
$typeCuisine = getKitchenType() ?? []; 

if(isset($_SESSION["client"])) {
    echo "Le nom du client: ".$_SESSION["client"]["nomCl"];
    print_r($typeCuisine);
}

$cuisineTypes = [];
try {
    if (function_exists('getAllCuisineTypes')) {
        $cuisineTypes = getAllCuisineTypes();
    }
} catch (Exception $e) {
    // Silently fail and use empty array
    $cuisineTypes = [];
}
?>
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Foodes - Home</title>
</head>
<body>

    <div class="hero-section">
        <div class="hero-content">
            <h1>Discover Delicious Food</h1>
            <p>Explore our wide variety of cuisines from around the world</p>
            <a href="shop.php" class="cta-button">Browse Menu</a>
        </div>
        <div class="hero-image">
            <img src="img\hero.png" alt="Burger">
        </div>
    </div>

    <div class="content">
        <?php if (!empty($typeCuisine)): ?>
            <?php foreach($typeCuisine as $type): ?>
                <div class="typeCuisineSection">
                    <h2><?= htmlspecialchars($type['TypeCuisine']) ?></h2>
                    <div class="Cards">
                        <?php
                        $Dishes = getdishesByType($type['TypeCuisine']);
                        foreach($Dishes as $dish): ?>
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-image-container">
                                        <img src="<?= htmlspecialchars($dish['image']) ?>" alt="<?= htmlspecialchars($dish['nomPlat']) ?>">
                                    </div>
                                    <h4><?= htmlspecialchars($dish['nomPlat']) ?></h4>
                                    <p><?= htmlspecialchars($dish['categoryPlat']) ?></p>
                                    <div class="price"><?= htmlspecialchars($dish['prix']) ?> MAD</div>
                                </div>
                                <div class="card-footer">
                                    <button>Add to cart</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun type de cuisine disponible.</p>
        <?php endif; ?>
    </div>
</body>
</html>
