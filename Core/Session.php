<?php

namespace Core;

class Session{

	public const SESSION_PATH = "";

	public function __construct(){

		if(!session_id()){

			session_start();

		}
	}

	public function __get($name){

		if( !empty($_SESSION[$name]) ){

			return $_SESSION[$name];
		}

		return null;
	}

	public function __set($name, $value){

		$_SESSION[$name] = ( is_array($value) ? (object)$value : $value );
	}

	public function isset($name){

		return isset($_SESSION[$name]);
	}

	public function set($name, $value){
  
		$_SESSION[$name] = ( is_array($value) ? (object)$value : $value );

		return $this;
	}

	public function data(){

		return (object)$_SESSION;
	}

	public function has($name){

		return isset($_SESSION[$name]);
	}

	public function sessionId(){

		return session_id();
	}

	public function sessionPath(){

		return self::SESSION_PATH;
	}

	public function unset($var){

		unset($_SESSION[$var]);
		return $this;
	}

	public function unsetAll(){

		session_unset();
		return $this;
	}

	public function destroy(){
		session_unset();
		session_destroy();
		return $this;

	}

	public function csrf(){
		
		return $_SESSION['csrf_token'] = bin2hex(random_bytes(5));
	}


}



?>