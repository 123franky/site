<?php
// ==== CONFIGURATION ====
require_once 'includes/config.php';


// ==== VARIABLES ====
$erreurs = [];
$succes_msg = '';
$action = $_GET['action'] ?? 'inscription';
$valeurs_saisies = ['nom' => '', 'prenom' => '', 'email' => '', 'type_mdp' => 'alphanumérique'];

// ==== TRAITEMENT FORMULAIRE ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'inscription') {
        // --- INSCRIPTION ---
        $valeurs_saisies['nom'] = htmlspecialchars($_POST['nom'] ?? '');
        $valeurs_saisies['prenom'] = htmlspecialchars($_POST['prenom'] ?? '');
        $valeurs_saisies['email'] = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $valeurs_saisies['type_mdp'] = $_POST['type_mdp'] ?? 'alphanumérique';

        // Validation
        if (empty($valeurs_saisies['nom'])) $erreurs[] = "Le nom est obligatoire.";
        if (empty($valeurs_saisies['prenom'])) $erreurs[] = "Le prénom est obligatoire.";
        if (!$valeurs_saisies['email']) $erreurs[] = "Email invalide.";

        if (empty($erreurs)) {
            // Génération mot de passe
            $commande = escapeshellcmd("python3 scripts-python/generate_password.py " . escapeshellarg($valeurs_saisies['type_mdp']));
            $mdp_genere = trim(shell_exec($commande));
            $mdp_hashe = password_hash($mdp_genere, PASSWORD_BCRYPT);

            // Insertion BDD
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$valeurs_saisies['nom'], $valeurs_saisies['prenom'], $valeurs_saisies['email'], $mdp_hashe]);

            $succes_msg = "Inscription réussie ! Mot de passe : " . $mdp_genere;
        }

    } elseif ($action === 'connexion') {
        // --- CONNEXION ---
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $erreurs[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription/Connexion - Archéo-IT</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1><?= ucfirst($action) ?></h1>

        <!-- Onglets -->
        <div>
            <a href="?action=inscription">Inscription</a>
            <a href="?action=connexion">Connexion</a>
        </div>

        <!-- Messages -->
        <?php if (!empty($erreurs)) : ?>
            <div>
                <?php foreach ($erreurs as $erreur) : ?>
                    <p><?= $erreur ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($succes_msg) : ?>
            <div>
                <p><?= $succes_msg ?></p>
                <p><a href="connexion.php">Se connecter</a></p>
            </div>
        <?php endif; ?>

        <!-- Formulaire INSCRIPTION -->
        <?php if ($action === 'inscription') : ?>
            <form method="POST" action="?action=inscription">
                <input type="text" name="nom" placeholder="Nom" value="<?= $valeurs_saisies['nom'] ?>" required>
                <input type="text" name="prenom" placeholder="Prénom" value="<?= $valeurs_saisies['prenom'] ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?= $valeurs_saisies['email'] ?>" required>
                
                <label>Type de mot de passe :</label>
                <select name="type_mdp" required>
                    <option value="alphabétique" <?= $valeurs_saisies['type_mdp'] === 'alphabétique' ? 'selected' : '' ?>>Lettres uniquement</option>
                    <option value="alphanumérique" <?= $valeurs_saisies['type_mdp'] === 'alphanumérique' ? 'selected' : '' ?>>Lettres + chiffres</option>
                    <option value="complexe" <?= $valeurs_saisies['type_mdp'] === 'complexe' ? 'selected' : '' ?>>Lettres + chiffres + spéciaux</option>
                </select>

                <button type="submit">S'inscrire</button>
            </form>

        <!-- Formulaire CONNEXION -->
        <?php elseif ($action === 'connexion') : ?>
            <form method="POST" action="?action=connexion">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>