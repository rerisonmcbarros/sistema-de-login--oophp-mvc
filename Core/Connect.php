<?php

namespace Core;



class Connect{
	
	private static $instance;
	private $error;

	
	public static function getInstance(){

		try{

			self::$instance = new \PDO(
			"mysql:host=".DB_HOST.";dbname=".DB_NAME,
			DB_USER,
			DB_PASSWORD
			);

			}catch(\Exception $exception){

			$this->error = [
				$exception->getMessage(), 
				$exception->getFile(), 
				$exception->getLine()
			];


		}

		return self::$instance;
	}

	public function error(){

		return $this->error;
	}

	final public function __construct(){}

	final public function __clone(){}

}

