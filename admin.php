<?php
// ==== DÉMARRAGE SESSION & CONFIGURATION ====
session_start();
require_once 'includes/config.php';


// ==== VÉRIFICATION CONNEXION UTILISATEUR ====
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?action=connexion');
    exit;
}

// ==== RÉCUPÉRATION DES UTILISATEURS ====
try {
    $stmt = $pdo->query("SELECT id, nom, prenom, email, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as date_inscription FROM users ORDER BY id DESC");
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Liste des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: #c0392b;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Administration - Liste des Utilisateurs</h1>

    <?php if (count($utilisateurs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['date_inscription']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Aucun utilisateur inscrit pour le moment.</p>
    <?php endif; ?>

    <div class="logout">
        <p><a href="logout.php">Se déconnecter</a></p>
    </div>

</body>
</html>
