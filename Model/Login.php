<?php

namespace Model;

class Login{

	private $message;

	public function signIn(){

		if($this->input()['login']){

			$user = new User();

			$user = $user->findByEmail($this->input()['email']);
			
			if($user){

				if( password_verify($this->input()['password'], $user->password) ){

					$session = new Session();

					$session->set("user", $user->data());

					$this->message = "login realizado com sucesso!";

					return null;
				}

			}

			$this->message = "Usuário ou senha inválidos!";

			return $this;

		}

	}


	public function message(){

		return $this->message;

	}


	public function input(){

		$input = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

		if(empty($input)){

			return;
		}
		
		$sanitized = array_map("trim", $input);

		return $sanitized;
	}

}





$session = new Session();


echo "<pre>", var_dump($_POST['token'], $session->csrf_token), "</pre>";

if($_POST['login'] && $_POST['token'] == $session->csrf_token){

	echo "Requisição aceita!";

	$login = new Login();

	$login->signIn();


}else{

	echo "Requisição não permitida!";
}




require_once __DIR__."/view-login.php";

echo "<pre>", var_dump($login->input(), $login, $session->data(), $user), "</pre>";


















?>




