<?php
// ==== CONFIGURATION ====
require_once 'includes/config.php';

$filterType = $_GET['filter'] ?? null;
$filterValue = $_GET['value'] ?? null;

if ($filterType && $filterValue) {
    $stmt = $pdo->prepare("SELECT * FROM chantiers WHERE $filterType LIKE ?");
    $stmt->execute(["%$filterValue%"]);
}

// ==== TRAITEMENT DU FILTRE ====
$region = $_GET['region'] ?? '';
$regions_disponibles = [
    'Auvergne-Rhône-Alpes', 'Bourgogne-Franche-Comté', 'Bretagne', 'Centre-Val de Loire',
    'Grand Est', 'Hauts-de-France', 'Île-de-France', 'Normandie', 'Nouvelle-Aquitaine',
    'Occitanie', 'Pays de la Loire', 'Provence-Alpes-Côte d\'Azur'
];

// Définir la base URL pour les images (adapter selon ton serveur local)
$baseUrl = '/archeo-it/site-web/';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chantiers de fouilles - Archéo-IT</title>
    <style>
        .chantier {
            border: 1px solid #ccc;
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
        }

        .chantier img {
            max-width: 300px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            margin: 10px 0;
            display: block;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <h1>Nos chantiers de fouilles</h1>

    <!-- Filtres par région -->
    <div>
        <form method="get">
            <label for="region">Filtrer par région :</label>
            <select name="region" id="region" onchange="this.form.submit()">
                <option value="">Toutes les régions</option>
                <?php foreach ($regions_disponibles as $r) : ?>
                    <option value="<?= htmlspecialchars($r) ?>" <?= $region === $r ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($region) : ?>
                <a href="chantiers.php">(Tout voir)</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    try {
        $sql = "SELECT * FROM chantiers";
        $params = [];

        if ($region) {
            $sql .= " WHERE lieu LIKE ?";
            $params[] = "%$region%";
        }

        $sql .= " ORDER BY nom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $chantiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($chantiers)) {
            echo '<div>';
            echo $region
                ? '<p>Aucun chantier trouvé en ' . htmlspecialchars($region) . '</p>'
                : '<p>Aucun chantier à afficher pour le moment.</p>';
            echo '</div>';
        } else {
            echo $region
                ? '<h2>Région : ' . htmlspecialchars($region) . '</h2>'
                : '';

            foreach ($chantiers as $index => $chantier) {
                echo '<div class="chantier">';
                echo '<h3>' . htmlspecialchars($chantier['nom']) . '</h3>';
                echo '<p><strong>Lieu :</strong> ' . htmlspecialchars($chantier['lieu']) . '</p>';

                // Image dynamique si définie en base
                if (!empty($chantier['image'])) {
                    // Sécuriser et afficher image depuis la base
                    echo '<img src="' . htmlspecialchars($chantier['image']) . '" alt="Image du chantier">';
                } else {
                    // Sinon image locale chantier1.jpg à chantier5.jpg
                    $numImage = ($index % 5) + 1;
                    $imagePathLocal = __DIR__ . '/uploads/chantier' . $numImage . '.jpg';
                    $imageUrl = $baseUrl . 'uploads/chantier' . $numImage . '.jpg';

                    if (file_exists($imagePathLocal)) {
                        echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="Image chantier ' . $numImage . '">';
                    } else {
                        echo '<p><em>Image non disponible</em></p>';
                    }
                }

                echo '<div>' . nl2br(htmlspecialchars($chantier['description'])) . '</div>';
                echo '</div>';
            }
        }
    } catch (PDOException $e) {
        echo '<div>Erreur de chargement des chantiers : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    ?>
   <table border="0">
    <tr>
        <td>
             <img src="uploads/chantier1.jpg" alt="Test image" width="1000" height="1000">
        </td>
        <td>
             <img src="uploads/chantier2.jpg" alt="Test image" width="1000" height="1000">
        </td>
    </tr>
    <tr>
        <td>
             <img src="uploads/chantier3.jpg" alt="Test image" width="1000" height="1000">
        </td>
        <td>
             <img src="uploads/chantier4.jpg" alt="Test image" width="1000" height="1000">
        </td>
    </tr>
   </table>

</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
