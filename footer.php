<?php
/**
 * Footer commun à toutes les pages
 */
?>

<footer>
    <div class="footer-content">
        <!-- Section Coordonnées -->
        <div class="footer-section">
            <h3>Archéo-IT</h3>
            <p>
                <i class="fas fa-map-marker-alt"></i> 123 Rue des Fouilles, 75000 Paris<br>
                <i class="fas fa-phone"></i> +33 1 23 45 67 89<br>
                <i class="fas fa-envelope"></i> contact@archeo-it.fr
            </p>
        </div>

        <!-- Section Liens rapides -->
        <div class="footer-section">
            <h3>Accès rapide</h3>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="chantiers.php">Chantiers</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="profil.php">Mon profil</a></li>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Section Réseaux sociaux -->
        <div class="footer-section">
            <h3>Nous suivre</h3>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="newsletter">
                <form action="newsletter.php" method="POST">
                    <input type="email" name="email" placeholder="Votre email" required>
                    <button type="submit">S'abonner</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Copyright et mentions légales -->
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Archéo-IT - Tous droits réservés</p>
        <p>
            <a href="mentions-legales.php">Mentions légales</a> | 
            <a href="politique-confidentialite.php">Politique de confidentialité</a>
        </p>
    </div>
</footer>

<!-- Scripts JS -->
<script>
    setTimeout(() => {
        if (window.scritLoaded && window.scriptDeferred) {
            const fallbackScript = document.createElement('script');
            fallbackScript.src = 'js/script.js';
            document.body.appendChild(fallbackScript);
        }
    }, 1000);
</script>


<?php if (defined('DEBUG_MODE') && DEBUG_MODE): ?>
    <!-- Affichage des infos de debug en bas de page -->
    <div class="debug-info">
        <h4>Debug Information</h4>
        <p>Page générée en <?= round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4) ?> secondes</p>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>
<?php endif; ?>