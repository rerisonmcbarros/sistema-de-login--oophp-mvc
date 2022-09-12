<?php
session_start();

require_once __DIR__."/vendor/autoload.php";


$session = new \Core\Session();

$user = new \Model\User();








echo "<pre>", var_dump($_POST['token'], $_SESSION['csrf_token'], $_SESSION), "</pre>";

if($session->login){
	header("Location: http://localhost/Login/home.php ");
}

if($_POST['login']){

	if( $_POST['token'] == $session->csrf_token){

	echo "Requisição aceita!";

		if($user = $user->findByEmail($_POST['email'])){

			$user = $user->data();

			$session->set("login", $user);

			echo "usuário válido!";

			echo "<pre>", var_dump($user,$session->data()), "</pre>";

			header("Location: http://localhost/Login/home.php ");
			echo "<h2>Login Realizado com Sucesso</h2>";

		}else{

			echo "Usuário ou senha inválidos!";

		}


	}else{

	echo "Requisição não aceita!";

	}

}


require_once __DIR__."/view-login.php";





















?>