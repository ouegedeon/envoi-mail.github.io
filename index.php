<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function formatInput($input) {
    return mb_encode_mimeheader(htmlspecialchars($input), 'UTF-8', 'Q');
}

if(isset($_POST['send'])){
    $monNom = formatInput($_POST['mon-nom']);
    $nomClient = formatInput($_POST['nom-client']);
    $mailClient = formatInput($_POST['mail-client']);
    $monMail = formatInput($_POST['mon-mail']);
    $objet = formatInput($_POST['objet']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'patrickderoule3311@gmail.com';
    $mail->Password = 't8wYsfR7nHXC9zch';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8'; // Ajustement de l'encodage
    $mail->setFrom($monMail, $monNom);
    $mail->addAddress($mailClient, $nomClient);
    $mail->Subject = $objet;
    $mail->Body = $message;

    // Traitement de la pièce jointe
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $attachmentPath = $_FILES['attachment']['tmp_name'];
        $attachmentName = $_FILES['attachment']['name'];
        $attachmentSize = $_FILES['attachment']['size'];

        if ($attachmentSize <= 10 * 1024 * 1024) { // Vérification de la taille (10 Mo)
            $mail->addAttachment($attachmentPath, $attachmentName);
        } else {
            $errorMessage = "La taille de la pièce jointe dépasse la limite autorisée (10 Mo).";
        }
    }

    try {
        $mail->send();
        $successMessage = "Le message a été envoyé avec succès.";
    } catch (Exception $e) {
        $errorMessage = "Une erreur s'est produite lors de l'envoi du message : {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoi de mail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    /* Ajoutez ici vos styles CSS personnalisés */
    body {
        margin: 0;
        padding: 0;
    }

    header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 1rem 0;
        background-color: #343a40;
        color: #ffffff;
    }

    footer {
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 0.5rem 0;
        background-color: #343a40;
        color: #ffffff;
    }

    .content {
        padding-top: 100px;
    }
    </style>
</head>

<body>

    <header class="text-center">
        <h1 class="mb-0">BIG GEDO SENDER</h1>
    </header>

    <div class="content container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <p>Bienvenue dans notre application d'envoi d'e-mails, conçue par Big Gedo. Assurez-vous de remplir
                    soigneusement tous les champs requis avant de cliquer sur le bouton "Envoyer". Pour toute réponse de
                    la part du destinataire, veuillez consulter l'adresse e-mail spécifiée dans le champ "Mail".

                    Merci d'utiliser notre service et n'hésitez pas à nous contacter en cas de questions ou de besoins
                    d'assistance.

                    L'équipe de Big Gedo
                </p>

                <?php if (isset($successMessage)) { echo '<div class="alert alert-success" role="alert">' . $successMessage . '</div>'; } elseif (isset($errorMessage)) { echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>'; } ?>

                <form method="post">
                  <br>
                  <div class="mb-3">
                    <label for="mon-nom" class="form-label" >Mon nom à afficher :</label>
                    <input type="text" class="form-control" id="mon-nom" name="mon-nom">
                </div>

                    <div class="mb-3">
                        <label for="nom-client" class="form-label">Nom du client :</label>
                        <input type="text" class="form-control" id="nom-client" name="nom-client">
                    </div>

                    <div class="mb-3">
                        <label for="mail-client" class="form-label">Mail du client :</label>
                        <input type="text" class="form-control" id="mail-client" name="mail-client">
                    </div>

                    <div class="mb-3">
                        <label for="mon-mail" class="form-label">Mon mail :</label>
                        <input type="text" class="form-control" id="mon-mail" name="mon-mail">
                    </div>

                    <div class="mb-3">
                        <label for="objet" class="form-label">Objet du message :</label>
                        <input type="text" class="form-control" id="objet" name="objet">
                    </div>

                    <div class="mb-3">
            <label for="attachment" class="form-label">Pièce jointe :</label>
            <input type="file" class="form-control" id="attachment" name="attachment">
          </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Le message :</label>
                        <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                    </div> 
                    <button type="submit" class="btn btn-secondary" name="send">Envoyer</button>
                </form>
                <br>
            </div>
        </div>
    </div>
    <footer class="text-center">
        &copy; 2023 Envoyer un e-mail par Big Gedo
    </footer>
   

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
