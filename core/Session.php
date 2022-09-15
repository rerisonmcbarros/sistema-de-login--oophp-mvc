<?php

namespace Core;

/** @package Core */
class Session{

	/** @var string */
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

	public function __isset($name){

		return isset($_SESSION[$name]);
	}

 	/** @param $name string */
 	/** @param $value mixed */
 	/** @return object */
	public function set($name, $value){
  
		$_SESSION[$name] = ( is_array($value) ? (object)$value : $value );

		return $this;
	}

	/** @return @object */
	public function data(){

		return (object)$_SESSION;
	}

	/** @param string name */
	/** @return bool */
	public function has($name){

		return isset($_SESSION[$name]);
	}

	/** @return string */
	public function sessionId(){

		return session_id();
	}

	/** @return */
	public function sessionPath(){

		return self::SESSION_PATH;
	}

	/** @param strin $var */
	/** @return object */
	public function unset($var){

		unset($_SESSION[$var]);
		return $this;
	}

	/** @return object */
	public function unsetAll(){

		session_unset();
		return $this;
	}

	/** @return object */
	public function destroy(){
		session_unset();
		session_destroy();
		return $this;

	}

	/** @return string */
	public function csrf(){
		
		return $_SESSION['csrf_token'] = bin2hex(random_bytes(5));
	}


}



?>