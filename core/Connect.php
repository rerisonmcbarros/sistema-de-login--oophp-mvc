<?php

namespace Core;

/** @package Core */
class Connect{
	
	/** @var object */
	private static $instance;
	/** @var array*/
	private $error;

	/** @return \PDO|null */
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

			return null;

		}

		return self::$instance;
	}

	/** @return array|null */
	public function error(){

		return $this->error;
	}

	/** @return null */
	final public function __construct(){}

	/** @return null */
	final public function __clone(){}

}

