<?php require_once 'config.php'; ?>
<nav class="navbar">
    <div class="logo">
        <a href="home.php">Foodes</a>
    </div>
    
    <div class="search-container">
        <form action="home.php" method="GET" class="search-form">
            <input type="text" name="search_query" placeholder="Search for dishes..."
                value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">Cuisines <span class="dropdown-arrow">▼</span></a>
            <ul class="dropdown-menu">
                <?php
                // Fetch cuisine types from database
                $cuisineTypes = getAllCuisineTypes();
                foreach($cuisineTypes as $cuisine): ?>
                    <li><a href="home.php?cuisine=<?= urlencode($cuisine['TypeCuisine']) ?>"><?= htmlspecialchars($cuisine['TypeCuisine']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    
    <div class="nav-buttons">
        <?php if(isset($_SESSION["client"])): ?>
            <div class="cart-icon-container">
                <a href="cart.php" class="btn cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-badge"><?= array_sum(array_column($_SESSION['panier'] ?? [], 'quantity')) ?></span>
                </a>
            </div>
            <div class="user-dropdown">
                <button class="user-btn">
                    <?= htmlspecialchars($_SESSION["client"]["nomCl"] ?? "Mon Compte") ?>
                    <span class="dropdown-arrow">▼</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="logout.php" class="logout-link">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn btn-outline">Register</a>
        <?php endif; ?>
    </div>
</nav>