<?php

include_once('connexion.php');

if (!check_post('hash'))
{
	echo "error";
	exit;
}

$req = $bdd->prepare('SELECT path FROM hash WHERE hash = ?');
$req->execute(array($_POST['hash']));

if ($req->rowCount() != 0)
{
	echo "error";
	exit;
}

exec("~/.brew/bin/node ../js/torrent.js ".$_POST['hash']." > /dev/null 2>/dev/null &");

?>
