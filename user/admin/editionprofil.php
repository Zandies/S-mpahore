<?php
session_start();
include '../a-connect.php';
if (isset($_SESSION['id'])) {

if (isset($_POST['envoyerimg'])) {
  if (isset($_FILES['image']) AND !empty($_FILES['image']['name']))
     {
         $tailleMax = 10485760;
         $extensionValides = array('jpg', 'jpeg', 'gif', 'png', 'svg');

         if($_FILES['image']['size'] <= $tailleMax)
         {
            $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'] ,'.' ), 1));

            if(in_array($extensionUpload, $extensionValides))
            {
              $nomfichier = $_SESSION['id'];
              exec("convert $nomfichier.".".$extensionUpload $nomfichier.'.png'");
              $chemin = "../../templates/img/". $nomfichier.".png";
               $resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin);

               if($resultat)
               {
                     header('Location: admin.php?id='.$_SESSION['id']);
               }
               else
               {
                  $msg = "Erreur durant l'importation";
               }
            }
            else
            {
               $msg = "Votre photo de profil doit être au format png, jpg, jpeg, svg ou gif";
            }
         }
         else
         {
            $msg = "Votre photo de  profil ne doit pas dépasser 10Mo";
         }
     }
   }

   if (isset($_POST['envoyermdp'])) {
     $mdp1 = $_POST['mdp1'];
     $mdp2 = $_POST['mdp2'];
     if ($mdp1 === $mdp2) {
       $mdp = sha1($mdp1);
       $reqInsertMdp = $bdd->prepare('UPDATE membre SET mdp = ? WHERE id = ?');
       $reqInsertMdp->execute(array($mdp, $_SESSION['id']));
       header('Location: admin.php?id='.$_SESSION['id']);
     }else{
       $msg = "Vos mots de passe ne correspondent pas";
     }
   }

}else{
  header('Location: ../connexion.php');
}


?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8"><meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Sémaphore">
     <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, user-scalable=no">
    <title>Modifier l'image d'arrière plan ou votre mot de passe</title>
    <link rel="stylesheet" href="/Semaphore/templates/css/style.css">
  </head>
  <body id="body">
    <?php if (isset($msg)) { echo ""; ?>
    <strong style="background-color:#e74c3c;"><?= $msg?></strong><br />
  <?php } ?>
    <strong style="background-color:#e74c3c;">Attention ! Si vous modifiez l'image de fond il faudra actualiser manuellement l'écran sur lequel l'image s'affichera</strong>
    <form method="post" action="" enctype="multipart/form-data">
      <input type="file" name="image" /><br />
      <input type="submit" name="envoyerimg" value="Mettre cette image" />
    </form>
    <form method="post" action="" enctype="multipart/form-data">
      <label>Nouveau mot de passe</label>
      <input type="password" name="mdp1" /><br />
      <label>Répéter mot de passe</label>
      <input type="password" name="mdp2" /><br />
      <input type="submit" name="envoyermdp" value="Changer de mot de passe" />
    </form>
    <a href="admin.php">&lt;Retour</a>
  </body>
</html>
