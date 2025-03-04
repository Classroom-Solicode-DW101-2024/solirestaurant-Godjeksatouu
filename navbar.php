<?php
session_start();
require_once 'config.php';
?>


<nav class="navbar">
    <div class="logo">
        <a href="index.php">Foodes</a>
    </div>
    
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search for dishes..." class="search-input">
            <button type="submit" class="search-btn">
                <span class="search-icon">&#128269;</span>
            </button>
        </form>
    </div>
    
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">Cuisines <span class="dropdown-arrow">▼</span></a>
            <ul class="dropdown-menu">
                <?php
                // Fetch cuisine types from database
                $cuisineTypes = getAllCuisineTypes(); // You'll need to create this function
                foreach($cuisineTypes as $cuisine): ?>
                    <li><a href="shop.php?cuisine=<?= urlencode($cuisine['TypeCuisine']) ?>"><?= htmlspecialchars($cuisine['TypeCuisine']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    
    <div class="nav-buttons">
        <?php if(isset($_SESSION["client"])): ?>
            <a href="cart.php" class="btn cart-btn">Panier</a>
            <div class="dropdown user-dropdown">
                <a href="#" class="btn user-btn dropdown-toggle">
                    <?= htmlspecialchars($_SESSION["client"]["name"] ?? "Mon Compte") ?> <span class="dropdown-arrow">▼</span>
                </a>
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

<style>
/* Styles de base */
:root {
  --primary-color: #4CAF50;
  --primary-hover: #388E3C;
  --text-light: #FFFFFF;
  --text-dark: #333333;
  --background-light: #F5F5F5;
  --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--background-light);
  color: var(--text-dark);
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--text-light);
  padding: 12px 5%;
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.logo a {
  color: var(--primary-color);
  font-size: 28px;
  text-decoration: none;
  font-weight: 700;
  letter-spacing: 1px;
  transition: var(--transition);
}

.logo a:hover {
  color: var(--primary-hover);
  transform: scale(1.05);
}

.nav-links {
  list-style: none;
  display: flex;
  padding: 0;
  margin: 0;
}

.nav-links li {
  margin: 0 20px;
  position: relative;
}

.nav-links a {
  color: var(--text-dark);
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
  padding: 8px 0;
  transition: var(--transition);
}

/* Enhanced Navigation Bar Styles - Add to your existing navbar CSS */

/* Search container */
.search-container {
  position: relative;
  margin-right: 20px;
  flex-grow: 1;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 10px 15px;
  padding-right: 40px;
  border: none;
  border-radius: 50px;
  background-color: rgba(255, 255, 255, 0.9);
  transition: var(--transition);
}

.search-input:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
  background-color: white;
}

.search-btn {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  cursor: pointer;
  height: 30px;
  width: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-color);
}

.search-icon {
  font-size: 16px;
}

/* Dropdown menu styles */
.dropdown {
  position: relative;
}

.dropdown-toggle {
  cursor: pointer;
  display: flex;
  align-items: center;
}

.dropdown-arrow {
  font-size: 10px;
  margin-left: 5px;
  transition: transform 0.2s ease;
}

.dropdown:hover .dropdown-arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: white;
  min-width: 180px;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  padding: 8px 0;
  z-index: 100;
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all 0.3s ease;
}

.dropdown:hover .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-menu li {
  display: block;
  margin: 0;
}

.dropdown-menu a {
  color: var(--text-dark) !important;
  padding: 10px 20px;
  display: block;
  font-size: 14px;
  transition: all 0.2s ease;
}

.dropdown-menu a:hover {
  background-color: rgba(76, 175, 80, 0.1);
  color: var(--primary-color) !important;
}

/* User dropdown specific styles */
.user-dropdown .dropdown-menu {
  right: 0;
  left: auto;
}

.user-btn {
  display: flex;
  align-items: center;
}

.logout-link {
  color: #e74c3c !important;
}

.logout-link:hover {
  background-color: rgba(231, 76, 60, 0.1) !important;
}

/* Cart button styling */
.cart-btn {
  position: relative;
  padding-left: 35px;
}

.cart-btn::before {
  content: '🛒';
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
}

/* Outline button style */
.btn-outline {
  background-color: transparent;
  border: 1px solid var(--primary-color);
  color: var(--primary-color);
}

.btn-outline:hover {
  background-color: var(--primary-color);
  color: white;
}

/* Fix spacing for mobile */
@media (max-width: 992px) {
  .navbar {
    flex-wrap: wrap;
  }
  
  .search-container {
    order: 3;
    max-width: 100%;
    width: 100%;
    margin: 10px 0;
  }
  
  .nav-links {
    order: 2;
  }
  
  .logo {
    order: 1;
  }
  
  .nav-buttons {
    order: 4;
  }
}

@media (max-width: 768px) {
  .nav-links .dropdown-menu {
    position: static;
    box-shadow: none;
    border-radius: 0;
    max-height: 0;
    overflow: hidden;
    opacity: 1;
    visibility: visible;
    transform: none;
    transition: max-height 0.3s ease;
    padding: 0;
    background-color: rgba(0, 0, 0, 0.05);
  }
  
  .nav-links .dropdown:hover .dropdown-menu {
    max-height: 500px;
  }
  
  .nav-links .dropdown-menu a {
    padding-left: 30px;
  }
}

.nav-links a:hover {
  color: var(--primary-color);
}

.nav-links a::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: var(--primary-color);
  transition: var(--transition);
}

.nav-links a:hover::after {
  width: 100%;
}

.nav-buttons {
  display: flex;
  align-items: center;
}

.btn {
  background-color: var(--primary-color);
  color: var(--text-light);
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 50px;
  margin-left: 15px;
  font-weight: 500;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: var(--transition);
  display: flex;
  align-items: center;
}

.btn:hover {
  background-color: var(--primary-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Cart button with icon */
.btn.cart-btn::before {
  content: '🛒';
  margin-right: 5px;
}

/* Responsive design */
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    padding: 15px;
  }
  
  .nav-links {
    margin: 15px 0;
    width: 100%;
    justify-content: center;
  }
  
  .nav-links li {
    margin: 0 10px;
  }
  
  .nav-buttons {
    width: 100%;
    justify-content: center;
    margin-top: 10px;
  }
  
  .btn {
    margin: 0 5px;
  }
}

/* Optional animation for mobile menu toggle */
@media (max-width: 480px) {
  .nav-links {
    flex-direction: column;
    align-items: center;
  }
  
  .nav-links li {
    margin: 8px 0;
  }
}
</style>
