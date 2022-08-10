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

		try{

			$connect = (new Connect())->getInstance();

			$stmt = $connect->prepare($query);

			parse_str($param, $paramArray);

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
