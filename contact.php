<?php
// ==== CONFIGURATION ====
require_once 'includes/config.php';



// ==== VARIABLES ====
$erreurs = [];
$succes_msg = '';
$valeurs_saisies = ['nom' => '', 'prenom' => '', 'email' => '', 'sujet' => '', 'message' => ''];

// ==== TRAITEMENT DU FORMULAIRE ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Nettoyage des données
    $valeurs_saisies['nom'] = htmlspecialchars($_POST['nom'] ?? '');
    $valeurs_saisies['prenom'] = htmlspecialchars($_POST['prenom'] ?? '');
    $valeurs_saisies['email'] = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $valeurs_saisies['sujet'] = htmlspecialchars($_POST['sujet'] ?? '');
    $valeurs_saisies['message'] = htmlspecialchars($_POST['message'] ?? '');

    // 2. Validation
    if (empty($valeurs_saisies['nom'])) $erreurs[] = "Le nom est obligatoire.";
    if (empty($valeurs_saisies['prenom'])) $erreurs[] = "Le prénom est obligatoire.";
    if (!$valeurs_saisies['email']) $erreurs[] = "Email invalide.";
    if (empty($valeurs_saisies['sujet'])) $erreurs[] = "Veuillez choisir un sujet.";
    if (strlen($valeurs_saisies['message']) < 10) $erreurs[] = "Le message doit contenir au moins 10 caractères.";

    // 3. Si tout est valide
    if (empty($erreurs)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (nom, prenom, email, sujet, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $valeurs_saisies['nom'],
                $valeurs_saisies['prenom'],
                $valeurs_saisies['email'],
                $valeurs_saisies['sujet'],
                $valeurs_saisies['message']
            ]);
            $succes_msg = "Votre message a bien été envoyé !";
            $valeurs_saisies = ['nom' => '', 'prenom' => '', 'email' => '', 'sujet' => '', 'message' => '']; // Réinitialisation
        } catch (PDOException $e) {
            $erreurs[] = "Erreur technique : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - Archéo-IT</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Contactez-nous</h1>

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
            </div>
        <?php endif; ?>

        <form action="traitement_contact.php" method="POST">
            <input type="text" name="nom" placeholder="Nom" value="<?= $valeurs_saisies['nom'] ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $valeurs_saisies['prenom'] ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $valeurs_saisies['email'] ?>" required>
            
            <select name="sujet" required>
                <option value="" disabled <?= empty($valeurs_saisies['sujet']) ? 'selected' : '' ?>>Choisir un sujet</option>
                <option value="Demande d'infos" <?= $valeurs_saisies['sujet'] === "Demande d'infos" ? 'selected' : '' ?>>Demande d'informations</option>
                <option value="Demande de Rendez-vous" <?= $valeurs_saisies['sujet'] === "Demande de Rendez-vous" ? 'selected' : '' ?>>Demande de rendez-vous</option>
                <option value="Autre" <?= $valeurs_saisies['sujet'] === "Autre" ? 'selected' : '' ?>>Autre</option>
            </select>
            
            <textarea name="message" placeholder="Votre message..." required><?= $valeurs_saisies['message'] ?></textarea>
            
            <button type="submit">Envoyer</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>