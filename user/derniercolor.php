<?php
include 'a-connect.php';

if (isset($_GET['id'])) {
  $getid = $_GET['id'];
  $reqMot = $bdd->prepare('SELECT * FROM messages WHERE id_proprio = ? ORDER BY id DESC LIMIT 1');
  $reqMot->execute(array($getid));
  $dernierMot = $reqMot->fetch();
  echo $dernierMot['valeur'];
  echo $dernierMot['valeur2'];
}
?>
<link rel="stylesheet" href="../templates/css/style.css">
<div id="banner-article" style="background-color: <?= $dernierMot['couleur'] ?>;">
  <p id="banner-title"><?= $dernierMot['valeur'] ?></p><br /><br>
  <p id="banner-subtitle"><?= $dernierMot['valeur2'] ?></p>
</div>
