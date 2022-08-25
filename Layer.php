<?php
require_once __DIR__."/Connect.php";
require_once __DIR__."/User.php";


class Layer{
	

	protected $message;
	protected $data;
	protected $pdo;


	public function __construct(){

		$this->pdo = (new Connect())->getInstance();

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

			$data = (array)$data;

			$dataKeys = array_keys($data);
			$dataValues = array_values($data);


			$columns = implode(",", $dataKeys);

			$values = ":".implode(",:", $dataKeys);

			//echo "<pre>", var_dump($columns, $values), "</pre>";

			$stmt = $this->pdo()->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$values})");

			foreach($data as $key => $value){

				$bindType = (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 

				//echo "<pre>", var_dump(":{$key}", $value, $bindType), "</pre>";

				$stmt->bindValue(":{$key}", $value, $bindType);

			}
			
			return $stmt->execute();

		}catch(PDOException $esception){


			echo "<pre>", var_dump($exception), "</pre>";

		}

	}

	public function read($query, $param){

		try{

			$stmt = $this->pdo()->prepare($query);

			parse_str($param, $paramArray);

			if(array_key_exists("limit", $paramArray) && array_key_exists("offset", $paramArray)){

				foreach($paramArray as $key => $value){

					$paramArray[$key] = (int)$value;

				}
			}

			foreach ($paramArray as $key => $value) {
				
				$bindType = (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 
				$stmt->bindValue($key, $value, $bindType);

			}

			$stmt->execute();

			

			return $stmt;

		}catch(PDOException $exception){

			echo "<pre>", var_dump($exception), "</pre>";

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

			//echo "<pre>", var_dump($paramArray), "</pre>";

			foreach ($paramArray as $key => $value) {
				
				$bindType = (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 

				$stmt->bindValue($key, $value, $bindType);

			}

			$stmt->execute();


		}catch(PDOException $exception){


			echo "<pre>", var_dump($exception), "</pre>";


		}

	}
	public function delete($table, $param){

		try{

			$stmt = $this->pdo()->prepare("DELETE FROM {$table} WHERE id =:id");

			//echo "<pre>", var_dump($param), "</pre>";

			parse_str($param, $paramArray);

			//echo "<pre>", var_dump($paramArray), "</pre>";

			foreach($paramArray as $key => $value){

				$bindType = (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 

				//echo "<pre>", var_dump(":{$key}", $value, $bindType), "</pre>";

				$stmt->bindValue(":{$key}", $value, $bindType);

			}

			//echo "<pre>", var_dump($stmt), "</pre>";

			$stmt->execute();

		}catch(PDOException $exception){

			echo "<pre>", var_dump($exception), "</pre>";

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

		return $data;
	}


	public function filter(){


	}


}
