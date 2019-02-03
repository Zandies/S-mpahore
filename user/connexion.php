<?php
session_start();
include 'a-connect.php';
if (isset($_COOKIE['remember']) && !empty($_COOKIE['remember'])) {
  $reqUser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
  $reqUser->execute(array($_COOKIE['remember']));
  $userInfo = $reqUser->fetch();
  $_SESSION['id'] = $userInfo['id'];
  $_SESSION['nom'] = $userInfo['nom'];
  header('Location: admin/admin.php?id=' . $userInfo['id']);
}
if (isset($_POST['envoyer'])) {
  $nom = htmlspecialchars($_POST['name']);
  if (isset($nom) && isset($_POST['mdp'])) {
    $reqUser = $bdd->prepare('SELECT * FROM membre WHERE nom = ? LIMIT 1');
    $reqUser->execute(array($nom));
    $userExist = $reqUser->rowCount();
    $userInfo = $reqUser->fetch();
    if ($userExist == 1) {
      $compte = $nom;
      if (sha1($_POST['mdp']) == $userInfo['mdp']) {
        $_SESSION['id'] = $userInfo['id'];
        $_SESSION['nom'] = $userInfo['nom'];
        if (isset($_POST['remember'])) {
          setcookie("remember", $_SESSION['id'], time()+3600*24*15);
        }
        header('Location: admin/admin.php?id=' . $userInfo['id']);
      }else{
        $erreur = "Mauvais mot de passe";
      }
    }else{
      $erreur= "Cette personne n'existe pas";
    }
  }else{
    $erreur = "Bon, il n'y a qu'un champ, remplissez le quand même s'il vous plaît...";
  }
}
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8"><meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Sémaphore">
 <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, user-scalable=no">
    <title>Connexion - Sémaphore</title>
    <style>
      body{
        background: linear-gradient(to right, #ff5a6b, #ff916e);
        display: flex;
        height: 90vh;
        flex-direction: column;
        text-align: center;
        justify-content: center;
        align-items: center;
        color: #fff;
      }
      input{
          background-color: #fff;
          border: 0px;
          padding: 5px;
          border-radius: 5px;
          margin: 5px 0;
          color: #888;
          box-shadow: 2px 2px #ff5a6d;
      }
      p{
        color: #fff;
      }
    </style>
  </head>
  <body>
    <form method="post" action="">
      <input type="text" name="name" placeholder="Nom" <?php if (isset($compte)){ echo "value='" . $compte . "'";} ?>><br />
      <input type="password" name="mdp" placeholder="Mot de passe"><br />
      <label><input type="checkbox" name="remember" value="remember">Se souvenir de moi</label><br />
      <input type="submit" name="envoyer" value="Se connecter" />
    </form>
    <?php if (isset($erreur)) { ?>
        <strong style="color:red;"><?= $erreur?></strong>
    <?php } ?>
  </body>
</html>
