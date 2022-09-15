<?php

namespace Model;

/** @package Model */
class Login{

	/** @var string */
	private $message;

	/** @return object|null */
	public function signIn(){

		if($this->input()['login']){

			$user = new \Model\User();

			$user = $user->findByEmail($this->input()['email']);
			
			if($user){

				if( password_verify($this->input()['password'], $user->password) ){

					$session = new \Core\Session();

					$session->set("login", ["user" => $user->data()]);

					$this->message = "login realizado com sucesso!";

					return $this;
				}

			}

			$this->message = "Usuário ou senha inválidos!";

			return null;

		}

	}

	/** @return string */
	public function message(){

		return $this->message;

	}

	/** @return array */ 
	public function input(){

		$input = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

		if(empty($input)){

			return;
		}
		
		$sanitized = array_map("trim", $input);

		return $sanitized;
	}

}




















?>




