<?php

/**
 * En-tête commune à toutes les pages du site Archéo-IT
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Archéo-IT</title>
    <meta name="description" content="<?= $pageDescription ?? 'Association Archéo-IT - Promotion des activités archéologiques' ?>">
    <!-- ... autres balises ... -->
     <script src="js/script.js" defer></script>
    <script>window.scriptDeferred = true;</script>
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Polices Google -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Bandeau d'accessibilité -->
    <div class="accessibility-bar">
        <button id="font-increase">A+</button>
        <button id="font-decrease">A-</button>
        <button id="high-contrast">Contraste élevé</button>
    </div>

    <!-- En-tête principal -->
    <header class="main-header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
                
                    <img src="includes/logo.png" alt="Logo" width="90" height="90">

               
            </div>

            <!-- Navigation principale -->
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Accueil</a></li>
                    <li><a href="chantiers.php" class="<?= basename($_SERVER['PHP_SELF']) === 'chantiers.php' ? 'active' : '' ?>">Chantiers</a></li>
                    <li><a href="actualites.php" class="<?= basename($_SERVER['PHP_SELF']) === 'actualites.php' ? 'active' : '' ?>">Actualités</a></li>
                    <li><a href="contact.php" class="<?= basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : '' ?>">Contact</a></li>
                   

                    <!-- Menu utilisateur -->
                    <li class="user-menu">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="profil.php"><i class="fas fa-user-circle"></i> Mon compte</a>
                            <ul class="submenu">
                                <li><a href="profil.php">Profil</a></li>
                                <li><a href="deconnexion.php">Déconnexion</a></li>
                            </ul>
                        <?php else: ?>
                            
                            <ul class="submenu">
                                <li><a href="inscription.php">Inscription</a></li>
                            </ul>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>

            <!-- Menu mobile -->
            <button class="mobile-menu-toggle" aria-label="Menu mobile">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Conteneur principal -->
    <main class="content-wrapper">
        <!-- Affichage des messages flash -->
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="flash-message <?= $_SESSION['flash']['type'] ?? 'success' ?>">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                <span class="close-flash">&times;</span>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>