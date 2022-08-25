<?php
session_start();

class Session{
	

	public const SESSION_PATH = "";

	public function __construct(){

		if(!session_id()){

			session_start();

		}

		$this->sessionLoad();

	}

	public function __get($name){

		return $this->$name;
	}

	public function __set($name, $value){

		$this->$name = $value;
	}


	public function __isset($name){

		return isset($this->$name);
	}

	public function __debugInfo(){

		return (array)$this;
	}

	public function set($name, $value){
  
		$_SESSION[$name] = ( is_array($value) ? (object)$value : $value );

		$this->sessionLoad();

		return $this;

	}

	public function sessionLoad(){

		foreach ($_SESSION as $index => $data){

			$this->$index = $data;

		}
	}

	public function has($name){

		return isset($this->$name);
	}

	public function sessionId(){

		return session_id();

	}

	public function sessionPath(){

		return self::SESSION_PATH;

	}

	public function unset($var){

		unset($_SESSION[$var]);

		unset($this->$var);

		return $this;

	}

	public function unsetAll(){

		session_unset();

		if(empty($_SESSION )){

			foreach($this as $key => $value){

				unset($this->$key);
			}
		}

		return $this;
	}

	public function destroy(){

		session_destroy();

		if(empty($_SESSION )){

			foreach($this as $key => $value){

				unset($this->$key);
			}
		}

		return $this;

	}

	public function csrf(){

		$_SESSION['csrf_token'] = bin2hex(random_bytes(5));

		$this->sessionLoad();

		return $this;

	}


}

require_once __DIR__."/User.php";

$session = new Session();

$user = new User();

$user = $user->findById(1);

$session->set("user", $user->data());


//$session->csrf();

echo $session->sessionId();

//$session->destroy();
//session_destroy();

$session->unsetAll();


echo "<pre>", var_dump($session, $_SESSION, session_id(), $session->sessionId()), "</pre>";

?>