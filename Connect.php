<?php

require_once __DIR__."/config.php";

class Connect{
	
	private $instance;
	private $error;

	final public function __construct(){

		try{

			$instance = new PDO(
			"mysql:host=".DB_HOST.";dbname=".DB_NAME,
			DB_USER,
			DB_PASSWORD
			);

			$this->instance = $instance;

		}catch(\Exception $exception){

			$this->error = [
				$exception->getMessage(), 
				$exception->getFile(), 
				$exception->getLine()
			];

		}

	
	}


	public function getInstance(){

		return $this->instance;
	}

	public function error(){

		return $this->error;
	}

	final public function __clone(){}

}

