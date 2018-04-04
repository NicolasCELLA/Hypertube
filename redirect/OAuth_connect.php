<?php

function validate_login($login, $bdd) {
	$req = $bdd->prepare('SELECT id_user FROM users WHERE login = ?');
	$req->execute(array($login . '_' . $count));
		
	if($req->rowCount() == 0) {
		return $login;
	}

	$exist = 1;
	$count = 2;

	while ($exist)
	{
		$req = $bdd->prepare('SELECT id_user FROM users WHERE login = ?');
		$req->execute(array($login . '_' . $count));
		
		if($req->rowCount() > 0) {
			$count += 2;
		} else {
			$exist = 0;
		}
	}
}

function register_user($user, $bdd) {

	$user['login'] = validate_login($user['login'], $bdd);

	$cle = md5(microtime(TRUE)*100000);

	$req = $bdd->prepare('INSERT INTO users (email, login, first_name, last_name, passwd, confirm, cle) VALUES (:email, :login, :first_name, :last_name, :passwd, 0, :cle)');
	
	$req->execute(array(
	    'email' => $user['email'],
	    'login' => $user['login'],
	    'first_name' => $user['first_name'],
	    'last_name' => $user['last_name'],
	    'passwd' => md5(microtime(TRUE)*100000),
	    'cle' => $cle
	    ));

	return true;
}

function connect_user($user) {
	$_SESSION['id'] = $user['id'];
	$_SESSION['login'] = $user['login'];

   	echo '<script>document.location.href="/"</script>';
    exit;
}

function user_exist($user, $bdd) {
	$req = $bdd->prepare('SELECT id_user, confirm FROM users WHERE email = ?');
	$req->execute(array($user['email']));

	if($req->rowCount() == 1)
	{
		$data = $req->fetch();
		return $data['id_user'];
	}
	return false;
}

function login_or_register($user, $bdd) {
	$user['id'] = user_exist($user, $bdd);
	if ($user['id']) {
		connect_user($user);
	} else {
		register_user($user, $bdd);
		login_or_register($user, $bdd);
	}
} 

?>
