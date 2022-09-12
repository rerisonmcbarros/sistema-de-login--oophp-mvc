<?php

namespace Model;

class User extends Layer{
	
	protected $table = "users";
	protected static $unchanged = ["id", "created_at", "updated_at"];
	protected static $required = ["first_name", "last_name", "email", "password"];

	

	public function bootUser($first_name, $last_name, $email, $password, $age, $document){

		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->age = $age;
		$this->email = $email;
		$this->password = $password;
		$this->document = $document;

		return $this;

	}

	public function find($terms, $params, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE {$terms}", $params);

		return $find->fetchObject(__CLASS__);

	}


	public function findByEmail($email, $columns = "*"){

		$find = $this->find("email =:email", "email={$email}", $columns);

		return $find;

	}

	public function findById($id, $columns = "*"){

		$find = $this->find(" id =:id", "id={$id}", $columns);

		return $find;

	}

	public function findByDoc($document, $columns = "*"){

		$find = $this->find(" document =:document", "document={$document}", $columns);

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

		if(!$this->required()){

			$this->message = "Preeencha todos os dados corretamente!";
			return null;
		}

		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){

			$this->message = "O email informado não é válido!"; 
			return null;
		}
		
		if(mb_strlen($this->password) < 8 || mb_strlen($this->password) > 10  && empty(password_get_info($this->password)['algo']) ){

			echo "<pre>", var_dump(password_get_info($this->password), password_get_info("1")), "</pre>";

			$this->message = "Sua senha deve possuir entre 8 e 10 caracteres!";
			return null;
		
		}

		$this->password = password_hash($this->password, PASSWORD_DEFAULT);


		if(empty($this->id)){

			if($this->findByEmail($this->email)){
				
				$this->message = "O email informado já existe!";
				return null;
			}

			$userId = $this->create($this->table, $this->dataFilter());
			$this->message = "Usuário cadastrado com sucesso!";
			
		}

		

		if(!empty($this->id)){

			$userId = $this->id;

			if($this->find("email = :email AND id != :id", "email={$this->email}&id={$this->id}")){
				
				$this->message = "O email informado já existe!";
				return null;
			}

			$this->update( $this->table, $this->dataFilter(), "id={$this->id}");
			$this->message = "Usuário atualizado com sucesso!";
	
		}


		$this->data = $this->findById($userId)->data();

		return $this;
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

/*
$user = new User();

$user = $user->findByEmail("rerison4@email.com");

if($user){

	echo "<pre>", var_dump($user), "</pre>";

	$user->first_name = "Rérison";

	$user->password = "password";

	$user->save();

}

echo "<pre>", var_dump($user), "</pre>";
*/