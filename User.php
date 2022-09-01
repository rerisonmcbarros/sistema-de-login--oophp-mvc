<?php
session_start();

require_once __DIR__."/Layer.php";

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


	public function findByEmail($email, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE email =:email", "email={$email}");

		return $find->fetchObject(__CLASS__);

	}

	public function findById($id, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE id =:id", "id={$id}");

		return $find->fetchObject(__CLASS__);

	}

	public function findByDoc($document, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE document =:document", "document={$document}");

		return $find->fetchObject(__CLASS__);

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
			return;
		}

		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){

			$this->message = "O email informado não é válido!"; 
			return;
		}
		
		if(mb_strlen($this->password) < 8 || mb_strlen($this->password) > 10  && empty(password_get_info($this->password)['algo']) ){

			echo "<pre>", var_dump(password_get_info($this->password), password_get_info("1")), "</pre>";

			$this->message = "Sua senha deve possuir entre 8 e 10 caracteres!";
			return;
		
		}else{

			$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		}


		if(empty($this->id)){

			if($this->findByEmail($this->email)){
				
				$this->message = "O email informado já existe!";
				return;
			}

			$userId = $this->create($this->table, $this->dataFilter());
			$this->message = "Usuário cadastrado com sucesso!";
			
		}

		

		if(!empty($this->id)){

			$userId = $this->id;

			if($this->findByEmail($this->email) && $this->id != $this->findByEmail($this->email)->id){
				
				$this->message = "O email informado já existe!";
				return;
			}

			$this->update( $this->table, $this->dataFilter(), "id={$this->id}");
			$this->message = "Usuário atualizado com sucesso!";
	
		}

		$read = $this->read("SELECT * FROM {$this->table} WHERE id =:id", "id={$userId}");

		$user = $read->fetchObject(__CLASS__);

		return $user;
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

$user->bootUser("Rerison", "Barros", "rerison@email.com", "123456789", "", "");

if($user){

	$user->save();
	echo $user->message();

}

if($user = $user->findByEmail($user->email)){

	$user->first_name = "Rerison";
	$user->document = "02147927651";

	$user->save();
	echo $user->message();

}


echo "<pre>", var_dump($user), "</pre>";
