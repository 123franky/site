<?php
// =============================================
// CONFIGURATION DE LA BASE DE DONNÉES
// =============================================

// Activer les erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session (si non déjà active)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =============================================
// CONSTANTES DE CONNEXION BASE DE DONNÉES
// =============================================
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'archeo_it');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', ''); // à adapter en production

// =============================================
// CONSTANTES DU SITE
// =============================================
if (!defined('SITE_NAME')) define('SITE_NAME', 'Archéo-IT');
if (!defined('BASE_URL')) define('BASE_URL', 'http://localhost/archeo-it/site-web');
if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// =============================================
// CONNEXION PDO
// =============================================
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// =============================================
// FONCTIONS UTILITAIRES
// =============================================

/**
 * Redirige vers une page avec un message flash optionnel
 */
function redirect($page, $message = null) {
    if ($message) {
        $_SESSION['flash'] = $message;
    }
    header("Location: " . BASE_URL . "/$page");
    exit;
}

/**
 * Affiche un message flash et le supprime
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $message = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='flash-message'>$message</div>";
    }
    return '';
}

/**
 * Protection XSS de base
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// =============================================
// PARAMÈTRES GÉNÉRAUX
// =============================================
date_default_timezone_set('Europe/Paris');
