<?php
require_once 'navbar.php';

$search_query = "";
$plats = [];
$filter_cuisine = "";

// Get cuisine types for filtering
$typeCuisine = getKitchenType() ?? [];

// Process search query
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = trim($_GET['search_query']);
    $plats = searchDishes($search_query);
    $showSearchResults = true;
} elseif (isset($_GET['cuisine']) && !empty($_GET['cuisine'])) {
    // Filter by cuisine type
    $filter_cuisine = trim($_GET['cuisine']);
    $plats = getdishesByType($filter_cuisine);
    $showSearchResults = true;
} else {
    $showSearchResults = false;
}


if (isset($_POST['add_to_cart']) && isset($_POST['plat_id'])) {
    $plat_id = $_POST['plat_id'];
    $plat = getDishById($plat_id);
    
    if ($plat) {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
    
        if (isset($_SESSION['panier'][$plat_id])) {
            $_SESSION['panier'][$plat_id]['quantity']++;
        } else {
            $_SESSION['panier'][$plat_id] = [
                'nomPlat' => $plat['nomPlat'],
                'prix' => $plat['prix'],
                'TypeCuisine' => $plat['TypeCuisine'],
                'quantity' => 1
            ];
        }
        
       
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $dishId = $_POST['delete_item'];
    if (isset($_SESSION['panier'][$dishId])) {
        unset($_SESSION['panier'][$dishId]);
    }
}


$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Foodes - Home</title>
</head>
<body>
    <div class="hero-section">
        <div class="hero-content">
            <h1>Discover Delicious Food</h1>
            <p>Explore our wide variety of cuisines from around the world</p>
            <a href="#menu-section" class="cta-button">Browse Menu</a>
        </div>
        <div class="hero-image">
            <img src="img/hero.png" alt="Delicious Food">
        </div>
    </div>

    <aside class="cart-containerr">
    <div class="panier" style="<?= empty($_SESSION['panier']) ? 'display:none;' : '' ?>"> 
        <h2 class="cart-title">🛒 Your Cart</h2>
        <?php if (!empty($_SESSION['panier'])): ?>
            <ul class="cart-items">
                <?php foreach ($_SESSION['panier'] as $dishId => $item): ?>
                    <li class="cart-item">
                        <div class="item-info">
                            <h3 class="item-name"><?= $item['nomPlat'] ?></h3> <!-- Removed htmlspecialchars -->
                            <p class="item-price"><?= $item['prix'] ?> MAD</p> <!-- Removed htmlspecialchars -->
                        </div>
                        <div class="item-controls">
                            <span class="item-quantity">Qty: <?= $item['quantity'] ?></span>
                            <form method="POST" class="remove-form">
                                <input type="hidden" name="delete_item" value="<?= $dishId ?>">
                                <button type="submit" class="remove-btn">
                                    <span id="checkout-btn">🗑️</span>
                                    <span class="remove-btn">Remove</span>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="cart-summary">
                <p class="total">Total: <span><?= number_format($total, 2) ?> MAD</span></p>
                <button class="checkout-btn">Secure Checkout →</button>
            </div>
        <?php else: ?>
            <p class="empty-cart">🍽️ Your cart is empty. Start adding delicious dishes!</p>
        <?php endif; ?>
    </div>
</aside>


    <?php if ($showSearchResults): ?>
        <div class="search-results">
            <h2>
                <?php if (!empty($search_query)): ?>
                    Results for: "<?= htmlspecialchars($search_query) ?>"
                <?php elseif (!empty($filter_cuisine)): ?>
                    <?= htmlspecialchars($filter_cuisine) ?> Cuisine
                <?php endif; ?>
            </h2>

            <?php if (empty($plats)): ?>
                <p>No dishes found.</p>
            <?php else: ?>
                <div class="Cards">
                    <?php foreach ($plats as $dish): ?>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-image-container">
                                    <img src="<?= htmlspecialchars($dish['image']) ?>" alt="<?= htmlspecialchars($dish['nomPlat']) ?>">
                                </div>
                                <h4><?= htmlspecialchars($dish['nomPlat']) ?></h4>
                                <p><?= htmlspecialchars($dish['categoryPlat']) ?></p>
                                <p class="cuisine-type"><?= htmlspecialchars($dish['TypeCuisine']) ?></p>
                                <div class="price"><?= htmlspecialchars($dish['prix']) ?> MAD</div>
                            </div>
                            <div class="card-footer">
                                <form method="POST">
                                    <input type="hidden" name="plat_id" value="<?= $dish['idPlat'] ?>">
                                    <button type="submit" name="add_to_cart">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div id="menu-section" class="content">
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
                                    <form method="POST">
                                        <input type="hidden" name="plat_id" value="<?= $dish['idPlat'] ?>">
                                        <button type="submit" name="add_to_cart">Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No cuisine types available.</p>
        <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll('button[name="add_to_cart"]').forEach(button => {
            button.addEventListener('click', function() {
            });
        });
    </script>
</body>
</html>