<?php
require_once 'includes/config.php';

$pageTitle = "Actualités";

// Vérifie si l'utilisateur est connecté
$estConnecte = isset($_SESSION['user_id']);

// Prépare la requête SQL
$sql = $estConnecte
    ? "SELECT * FROM actualites ORDER BY date_publication DESC"
    : "SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 3";

// Exécute la requête
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll();

include 'includes/header.php';
?>

<h1>Actualités</h1>

<?php if (empty($actualites)) : ?>
    <p>Aucune actualité disponible pour le moment.</p>
<?php else : ?>
    <div class="actualites-list">
        <?php foreach ($actualites as $actu) : ?>
            <article class="actualite">
                <?php if (!empty($actu['image'])) : ?>
                    <img src="uploads/<?= htmlspecialchars($actu['image']) ?>" alt="<?= htmlspecialchars($actu['titre']) ?>" class="actu-img" style="max-width: 300px;">
                <?php endif; ?>
                <h2><?= htmlspecialchars($actu['titre']) ?></h2>
                <p class="date"><?= date('d/m/Y', strtotime($actu['date_publication'])) ?></p>
                <p><?= nl2br(htmlspecialchars(substr($actu['contenu'], 0, 200))) ?>...</p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
