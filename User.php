<?php

require_once __DIR__."/Layer.php";

class User extends Layer{
	
	public $table = "users";
	

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

	public function all($limit = 50 , $offset = 0, $columns = "*"){

		$read = $this->read("SELECT {$columns} FROM {$this->table} limit :limit  offset :offset", "limit={$limit}&offset={$offset}");

		return $read->fetchAll(PDO::FETCH_CLASS,__CLASS__);

	}

}


$user = new User();
echo "<pre>", var_dump( $user->findByEmail("rerison25@gmail.com"), $user->findById(2), $user->all(2,1)), "</pre>";
