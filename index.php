<?php
// Déclaration des variables
$firstName = $lastName = $email = $tournamentPlace = "";
$conditionsAccepted = false;
$errors = [];

// Vérification des données envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérification du prénom
    if (empty($_POST["first"])) {
        $errors[] = "Le prénom ne doit pas être vide.";
    } else {
        $firstName = cleanInput($_POST["first"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
            $errors[] = "Le prénom ne doit contenir que des lettres et des espaces.";
        }
    }

    // Vérification du nom
    if (empty($_POST["last"])) {
        $errors[] = "Le nom ne doit pas être vide.";
    } else {
        $lastName = cleanInput($_POST["last"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
            $errors[] = "Le nom ne doit contenir que des lettres et des espaces.";
        }
    }

    // Vérification de l'email
    if (empty($_POST["email"])) {
        $errors[] = "L'email ne doit pas être vide.";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Le format de l'email est invalide.";
        }
    }

    // Vérification du lieu de tournoi
    if (empty($_POST["location"])) {
        $errors[] = "Vous devez choisir un lieu de tournoi.";
    } else {
        $tournamentPlace = cleanInput($_POST["location"]);
    }

    // Vérification des conditions d'utilisation
    if (empty($_POST["checkbox1"])) {
        $errors[] = "Vous devez accepter les conditions d'utilisation.";
    } else {
        $conditionsAccepted = true;
    }

    // Si aucune erreur, traiter les données (par exemple, envoyer un email)
    if (empty($errors)) {
        // Configuration de l'email
        $to = "glm.donizetti@gmail.com"; // Adresse email du destinataire
        $subject = "Nouvelle inscription au tournoi"; // Sujet de l'email
        $message = "
        Une nouvelle personne s'est inscrite au tournoi :
        
        Prénom : $firstName
        Nom : $lastName
        Email : $email
        Lieu du tournoi choisi : $tournamentPlace
        ";

        $headers = "From: no-reply@votredomaine.com\r\n"; // Adresse email de l'expéditeur
        $headers .= "Reply-To: $email\r\n"; // Permet de répondre directement à l'email de l'utilisateur
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Format de l'email en texte brut

        // Envoi de l'email
        if (mail($to, $subject, $message, $headers)) {
            echo "<div class='success-message'>Le formulaire a été soumis avec succès, et un email a été envoyé !</div>";
        } else {
            echo "<div class='error-message'>Une erreur s'est produite lors de l'envoi de l'email.</div>";
        }
    } else {
        // Afficher les erreurs
        foreach ($errors as $error) {
            echo "<div class='error-message'>$error</div>";
        }
    }
}

// Fonction pour nettoyer les données soumises
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
