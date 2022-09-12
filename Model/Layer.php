<?php

namespace Model;


use \PDO;
use \Core\Connect;

class Layer{
	

	protected $message;
	protected $data;
	protected $error;
	protected $pdo;


	public function __construct(){

		$this->pdo = (Connect::getInstance());

	}

	public function __get($name){

		return $this->data->$name;

	}

	public function __set($name, $value){
	
		if(empty($this->data)){
			$this->data = new \stdClass();
		}
		$this->data->$name = $value;
	}

	public function __isset($name){

		return isset($this->data->$name);

	}

	public function create($table, $data){

		try{

			$dataKeys = array_keys($data);
			$dataValues = array_values($data);


			$columns = implode(",", $dataKeys);

			$values = ":".implode(",:", $dataKeys);

			$stmt = $this->pdo()->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$values})");

			foreach($data as $key => $value){

				$stmt->bindValue(":{$key}", $value);
			}
			
			$stmt->execute();

			return $this->pdo()->lastInsertId();

		}catch(PDOException $exception){

			$this->error = $exception;
		}

	}

	public function read($query, $param){

		try{

			$stmt = $this->pdo()->prepare($query);

			parse_str($param, $paramArray);

			foreach ($paramArray as $key => $value) {

				$bindType = ($key == "limit" || $key == "offset" ? \PDO::PARAM_INT : \PDO::PARAM_STR);
			
				$stmt->bindValue(":$key", $value, $bindType);
			}

			$stmt->execute();

			return $stmt;

		}catch(PDOException $exception){

			$this->error = $exception;

		}


	}

	public function update($table, $dataSet, $param){

		

		try{

			$data;

			foreach($dataSet as $key => $value){

				$data[] = "{$key} = :{$key}";

			}

			$data = implode(", ", $data);

			$stmt = $this->pdo()->prepare("UPDATE {$table} SET {$data} WHERE id = :id");

			parse_str($param, $paramArray);

			$paramArray = array_merge($this->dataFilter(), $paramArray);
			

			foreach ($paramArray as $key => $value) {

				$stmt->bindValue(":$key", $value);
			}

			$stmt->execute();

		}catch(PDOException $exception){

			$this->error = $exception;
		}

	}
	public function delete($table, $param){

		try{

			$stmt = $this->pdo()->prepare("DELETE FROM {$table} WHERE id =:id");

			parse_str($param, $paramArray);

			foreach($paramArray as $key => $value){
				
				$stmt->bindValue(":{$key}", $value);
			}

			$stmt->execute();

		}catch(PDOException $exception){

			$this->error = $exception;

		}


	}

	public function pdo(){

		return $this->pdo;

	}

	public function data(){

		return $this->data;
	}


	public function message(){

		return $this->message;

	}

	public function dataFilter(){

		$data = (array)$this->data();

		foreach( static::$unchanged as $key){
			unset($data[$key]);
		}

		return $this->sanitize($data);
	}

	public function sanitize($data){

		$sanitized = [];

		foreach($data as $key => $value){

			$sanitized[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_STRIPPED));

		}

		return $sanitized;
	}

	public function required(){

		$data = (array)$this->data();

		foreach (static::$required as $item) {
			
			if(empty($data[$item])){

				return false;
			}
		}

		return true;

	}



}
