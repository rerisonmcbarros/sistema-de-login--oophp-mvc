<?php
session_start();

require_once __DIR__."/Session.php";
require_once __DIR__."/User.php";

$session = new Session();

$user = new User();








echo "<pre>", var_dump($_POST['token'], $_SESSION['csrf_token'], $_SESSION), "</pre>";

if($session->login){
	header("Location: http://localhost/Login/home1.php ");
}


if($_POST['login']){

	if( $_POST['token'] == $session->csrf_token){

	echo "Requisição aceita!";

		if($user = $user->findByEmail($_POST['email'])){

			$user = $user->data();

			$session->set("login", $user);

			echo "usuário válido!";

			echo "<pre>", var_dump($user,$session->data()), "</pre>";

			header("Location: http://localhost/Login/home1.php ");

		}else{

			echo "Usuário ou senha inválidos!";

		}


	}else{

	echo "Requisição não aceita!";

	}

}






require_once __DIR__."/view-login.php";





















?>