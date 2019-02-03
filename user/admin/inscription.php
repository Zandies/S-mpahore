<?php
session_start();
include '../a-connect.php';
if (isset($_SESSION['id'])) {
  if (isset($_POST['envoyer'])) {
    $fonction = htmlspecialchars($_POST['nom']);
    $reqInsert = $bdd->prepare('INSERT INTO membre(nom) VALUES (?)');
    $reqInsert->execute(array($fonction));
    header('Location: ../connexion.php');
}
}

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8"><meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Sémaphore">
    <title></title>
  </head>
  <body>
    <form method="post" action="">
      <input type="text" name="nom" placeholder="Fonction" />
      <input type="submit" name="envoyer" value="S'inscrire" />
    </form>
  </body>
</html>
