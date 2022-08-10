<?php

require_once __DIR__."/Layer.php";

class User extends Layer{
	
	public $table = "users";
	

	public function find($term, $param, $columns = "*"){

		$read = $this->read("SELECT {$columns} FROM {$this->table} WHERE {$term}", $param);

		$result = $read->fetchObject(__CLASS__);

		echo "<pre>", var_dump($result), "</pre>";

		return $result;

	}


	public function findByEmail($email){

		$find = $this->find("email =:email", "email={$email}");

	}

}


$user = new User();
echo "<pre>", var_dump($user->findByEmail("rerison25@gmail.com")), "</pre>";
