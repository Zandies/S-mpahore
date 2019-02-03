<?php
session_start();
include '../a-connect.php';
  if (!empty($_SESSION['id'])) {
    if (!empty($_GET['id']) AND $_GET['id'] == $_SESSION['id']) {
      $getid = $_GET['id'];
    // ZONE DE CODE ---- TEXTE (2)
    $reqMessages = $bdd->prepare('SELECT * FROM messages WHERE id_proprio = ? AND souvenir = 1 ORDER BY id DESC LIMIT 0,11');
    $reqMessages->execute(array($getid));

    if (isset($_POST['erase'])) {
      $reqInsertMessage = $bdd->prepare('UPDATE messages SET souvenir = 0 WHERE id = ?');
      $reqInsertMessage->execute(array($_POST['erase']));
      header("Refresh:0");
    }

    if (isset($_POST['envoyer'])) {

      $message = $_POST['message'];

      if ($message != NULL || isset($_POST['choixradio'])) {

        if (isset($_POST['choixradio'])) { # Choix boutons radio -------------------------
          $message = $_POST['choixradio'];
          $reqInsertMessage = $bdd->prepare('SELECT * FROM messages WHERE id = ?');
          $reqInsertMessage->execute(array($_POST['choixradio']));
          $messageaCopier = $reqInsertMessage->fetch();
          if (isset($messageaCopier['valeur2'])) {
            $inserserCopie = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
            $inserserCopie->execute(array($getid, $messageaCopier['valeur'], $messageaCopier['valeur2'], 0, $messageaCopier['couleur']));
          }else{
            $inserserCopie = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
            $inserserCopie->execute(array($getid, $messageaCopier['valeur'], NULL, 0, $messageaCopier['couleur']));
          }
        }else{

          if (isset($_POST['couleur'])) { # Couleur ---------------------
            $couleur = $_POST['couleur'];
            if (isset($_POST['souvenir'])) { # Souvenir ------------------
              if (isset($_POST['message2'])) { # Message 2 ---------------
                $message2 = $_POST['message2'];
                $reqInsertMessage = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
                $reqInsertMessage->execute(array($getid, $message, $message2, 1, $couleur));
              }else{ # Pas message 2 -----------------
                $reqInsertMessage = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
                $reqInsertMessage->execute(array($getid, $message, NULL, 1, $couleur));
              }
            }else{ # Pas de souvenir
              if (isset($_POST['message2'])) { # Message 2 ---------------
                $message2 = $_POST['message2'];
                $reqInsertMessage = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
                $reqInsertMessage->execute(array($getid, $message, $message2, 0, $couleur));
              }else{ # Pas message 2 -----------------
                $reqInsertMessage = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
                $reqInsertMessage->execute(array($getid, $message, NULL, 0, $couleur));
              }
            }
          }else{ # Pas de couleur
            $reqInsertMessage = $bdd->prepare('INSERT INTO messages(id_proprio, valeur, valeur2, souvenir, couleur) VALUES (?,?,?,?,?)');
            $reqInsertMessage->execute(array($getid, $message, $message2, 0, "#000000"));
          }
        }

        header("Refresh:0");
      }else{
        $erreur = "Il y'avait 1 paramètre à remplir... 1 !";
      }
    }

    // FIN DE ZONE DE CODE ---- TEXTE (2)

    }else{
      header('Location: admin.php?id=' . $_SESSION['id']);
    }
  }else{
    header('Location: ../connexion.php');
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8"><meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Sémaphore">
    <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, user-scalable=no">
    <link rel="stylesheet" href="../../templates/css/style.css">
    <!-- <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"> -->
    <title>Page Administrateur</title>
  </head>
  <body id="admin-menu">
    <div id="body">
      <?= '<p id="welcome">Bienvenue, Ô ' . ucfirst($_SESSION['nom'])  . '</p>' ?>
      <div id="admin-form">

      <p>Message à afficher:</p>
      <form method="post" action="" enctype="multipart/form-data">
        <?php
        while ($donnees = $reqMessages->fetch()) {
          if (isset($donnees['valeur2'])) {
            $message2 = $donnees['valeur2'];
          }
          ?>
          <label style="background-color:<?= $donnees['couleur']?>"><input type="radio" name="choixradio" value="<?= $donnees['id'] ?>"/>  <?= $donnees['valeur'] ?> <?php if(isset($message2)){ echo '<em style="font-size:10px">' . $donnees['valeur2'] . '</em>';} ?> </label>
          <button type="submit" name="erase" value="<?= $donnees['id'] ?>">X</button><br />
          <?php
        }
        ?>
        <input type="text" name="message" placeholder="Autre chose"/><br />
        <input type="text" name="message2" placeholder="Seconde ligne"/><br />
        <label>Couleur:
          <input type="color" name="couleur" />
        </label>
        <label><br />
          <input type="checkbox" name="souvenir" />
          Ajouter à l'historique
        </label><br />
        <input type="submit" name="envoyer" value="Mettre ce message" />
      </form>
    </div>
      <?php if (isset($erreur)) { ?>
          <strong style="background-color:#e74c3c;"><?= $erreur?></strong><br />
          <img src="/Semaphore/templates/img/clap.gif"  /><br />
      <?php } ?>
      <a href="editionprofil.php" id="edition">Modifier vore profil (image de fond et mot de passe)</a>
      <a href="../logout.php">Se déconnecter</a>
    </div>
    <!-- <div id="unpb">
      <p>Si vous avez un problème/bug dites le moi <a href="mailto:a.discepoli@student.arnivelles.be">par mail</a> ou comme vous voulez ;)</p>
    </div> -->
  </body>
</html>
