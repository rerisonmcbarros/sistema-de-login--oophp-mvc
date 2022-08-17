<?php

require_once __DIR__."/Layer.php";

class User extends Layer{
	
	public $table = "users";
	public static $unchanged = ["id", "created_at", "updated_at"];

	

	public function addUser($first_name, $last_name, $age, $email, $password, $document){

		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->age = $age;
		$this->email = $email;
		$this->password = $password;
		$this->document = $document;

		return $this;

	}

	public function find($query, $param){

		$read = $this->read($query, $param);

		return  $read->fetchObject(__CLASS__);

	}

	public function findByEmail($email, $columns = "*"){

		$find = $this->find("SELECT {$columns} FROM {$this->table} WHERE email =:email", "email={$email}");

		return $find;

	}

	public function findById($id, $columns = "*"){

		$find = $this->find("SELECT {$columns} FROM {$this->table} WHERE id =:id", "id={$id}");

		return $find;

	}

	public function findByName($name, $columns = "*"){

		$read = $this->read("SELECT {$columns} FROM {$this->table} WHERE first_name LIKE :partial ", "partial=%{$name}%");

		return $read->fetchAll(PDO::FETCH_CLASS,__CLASS__);

	}

	public function all($limit = 50 , $offset = 0, $columns = "*"){

		$read = $this->read("SELECT {$columns} FROM {$this->table} limit :limit  offset :offset", "limit={$limit}&offset={$offset}");

		return $read->fetchAll(PDO::FETCH_CLASS,__CLASS__);

	}


	public function save(){


		echo "<pre>", var_dump($this), "</pre>";

		if(empty($this->id)){

			$this->create($this->table, $this->data());

		}

		

		if(!empty($this->id)){

			$this->update( $this->table, $this->dataFilter(), "id={$this->id}");

		}

	}


	public function destroy($id = null){

		if(empty($id)){

			$id = $this->id;
		}

		$userRemoved = $this->findById($id);

		$this->delete($this->table, "id={$id}");

		$this->data = null;

	}


}




$user = new User();

$newUser = $user->findById(52);





echo "<pre>", var_dump( $newUser->save()), "</pre>";
