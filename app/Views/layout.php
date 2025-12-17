<?php
// Calcule le nombre d'articles dans le panier
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $cartCount = \Mini\Models\Cart::countItems($_SESSION['user_id']);
}

// Récupère les catégories pour le menu
$categories = \Mini\Models\Category::getAll();

// Détermine la page active
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Mozikako') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Header / Navigation -->
    <header class="header">
        <nav class="nav-container">
            <!-- Logo -->
            <div class="logo">
                <a href="/">
                    <span class="logo-icon">🎵</span>
                    <span class="logo-text">Mozikako</span>
                </a>
            </div>
            
            <!-- Menu principal -->
            <div class="nav-menu">
                <a href="/" class="nav-link <?= $currentPath === '/' ? 'active' : '' ?>">Accueil</a>
                
                <!-- Menu déroulant catégories -->
                <div class="nav-dropdown">
                    <button class="nav-link">
                        Catégories
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-content">
                        <?php foreach ($categories as $cat): ?>
                            <a href="/categorie?slug=<?= urlencode($cat['slug']) ?>">
                                <?= htmlspecialchars($cat['nom']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <a href="/produits" class="nav-link <?= $currentPath === '/produits' ? 'active' : '' ?>">Tous les produits</a>
            </div>
            
            <!-- Actions utilisateur -->
            <div class="nav-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/commandes" class="nav-link">📋 Commandes</a>
                    <a href="/panier" class="nav-link cart-link">
                        🛒 Panier
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                    <span class="nav-link">👋 <?= htmlspecialchars($_SESSION['user_prenom']) ?></span>
                    <a href="/deconnexion" class="btn btn-secondary">Déconnexion</a>
                <?php else: ?>
                    <a href="/connexion" class="btn btn-secondary">Connexion</a>
                    <a href="/inscription" class="btn btn-primary">Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <!-- Contenu principal -->
    <main class="main-content">
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Mozikako</h3>
                <p>Votre boutique d'instruments de musique en ligne</p>
            </div>
            
            <div class="footer-section">
                <h3>Catégories</h3>
                <ul>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="/categorie?slug=<?= urlencode($cat['slug']) ?>">
                                <?= htmlspecialchars($cat['nom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Liens utiles</h3>
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/produits">Produits</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/commandes">Mes commandes</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <p>📧 contact@mozikako.com</p>
                <p>📞 01 23 45 67 89</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 Mozikako - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>