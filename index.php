<?php
// ==== DÉMARRAGE DE LA SESSION (sans doublon) ====


// ==== CONFIGURATION ====
require_once 'includes/config.php';


// ==== RÉCUPÉRATION DES ACTUALITÉS ====
$limit = isset($_SESSION['user_id']) ? 100 : 3; // Limite différente selon connexion
try {
    $stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT $limit");
    $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de chargement des actualités : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Archéo-IT</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h1>Dernières actualités</h1>

        <?php if (empty($actualites)) : ?>
            <p>Aucune actualité à afficher pour le moment.</p>
        <?php else : ?>
            <div class="blog">
                <?php foreach ($actualites as $actualite) : ?>
                    <article class="card">
                        <?php if (!empty($actualite['image'])) : ?>
                            <img src="<?= htmlspecialchars($actualite['image']) ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>">
                        <?php endif; ?>
                        <h2><?= htmlspecialchars($actualite['titre']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($actualite['contenu'])) ?></p>
                        <time>Publié le : <?= date('d/m/Y', strtotime($actualite['date_publication'])) ?></time>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if (!isset($_SESSION['user_id'])) : ?>
                <p>Connectez-vous pour voir toutes les actualités.</p>
                <a class="btn" href="inscription_connexion.php?action=connexion">Se connecter</a>
                <a class="btn" href="chantiers.php?filter=region&value=occitanie">Voir les chantiers en Occitanie</a>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
