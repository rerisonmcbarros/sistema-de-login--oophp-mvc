<?php
require_once __DIR__."/Connect.php";
require_once __DIR__."/User.php";


class Layer{
	

	protected $message;
	protected $data;

	public function __get($name){

		return $this->data->$name;

	}

	public function __set($name, $value){
	
		if(empty($this->data)){
			$this->data = new \stdClass();
		}
		$this->data->$name = $value;
	}

	public function create($table,$params,$columns){

		echo "INSERT INTO {$table} ($columns) VALUES ($params)";

		try{



		}catch(PDOException $esception){



		}

	}

	public function read($query, $param){


		echo "<pre>", var_dump($query), "</pre>";

		try{

			$connect = (new Connect())->getInstance();

			$stmt = $connect->prepare($query);

			parse_str($param, $paramArray);

			if(array_key_exists("limit", $paramArray) && array_key_exists("offset", $paramArray)){

				foreach($paramArray as $key => $value){

					$paramArray[$key] = (int)$value;

				}
			}

			echo "<pre>", var_dump($param, $paramArray), "</pre>";

			foreach ($paramArray as $key => $value) {
				
				$bindType = (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 

				echo "<pre>", var_dump($key, $value, $bindType),"</pre>";

				$stmt->bindValue($key, $value, $bindType);

			}

			echo "<pre>", var_dump($stmt), "</pre>";

			$stmt->execute();

			

			return $stmt;

		}catch(PDOException $exception){

			echo "<pre>", var_dump($exception), "</pre>";

		}


	}

	public function update($table, $columns, $param){

		echo "UPDATE {$table} SET {$columns} WHERE ($param)";

		try{



		}catch(PDOException $esception){



		}

	}
	public function delete($table, $param){

		echo "DELETE FROM {$table} WHERE {$param}";

		try{



		}catch(PDOException $esception){



		}


	}




	public function message(){

		return $this->message;

	}



	public function filter(){


	}


}
