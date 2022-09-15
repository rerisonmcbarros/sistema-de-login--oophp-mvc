<?php

namespace Model;

use \Core\Layer;

/** @package Model */
class User extends Layer{
	
	/** @var string */
	protected $table = "users";
	/** @var array */
	protected static $unchanged = ["id", "created_at", "updated_at"];
	/** @var array */
	protected static $required = ["first_name", "last_name", "email", "password"];

	
	/** @param string $first_name */ 
	/** @param string $last_name */
	/** @param string $email */
	/** @param string $password */
	/** @param string $age */
	/** @param string $document */
	/** @return object */     
	public function bootUser($first_name, $last_name, $email, $password, $age, $document){

		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->age = $age;
		$this->email = $email;
		$this->password = $password;
		$this->document = $document;

		return $this;

	}

	/** @param string $terms */ 
	/** @param string $params */ 
	/** @param string $columns */
	/** @return object|null */ 
	public function find($terms, $params, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE {$terms}", $params);

		if($this->error || !$find->rowCount()){

			$this->message = "O usuário informado não foi encontrado!";

			return null;
		}

		return $find->fetchObject(__CLASS__);

	}


	
	/** @param string $email */ 
	/** @param string $columns */
	/** @return object|null */ 
	public function findByEmail($email, $columns = "*"){

		$find = $this->find("email =:email", "email={$email}", $columns);

		return $find;

	}

	/** @param string $id */ 
	/** @param string $columns */
	/** @return object|null */ 
	public function findById($id, $columns = "*"){

		$find = $this->find(" id =:id", "id={$id}", $columns);

		return $find;

	}

	/** @param string $document */ 
	/** @param string $columns */
	/** @return object|null */ 
	public function findByDoc($document, $columns = "*"){

		$find = $this->find(" document =:document", "document={$document}", $columns);

		return $find;

	}

	/** @param string $name */ 
	/** @param string $columns */
	/** @return array|null */ 
	public function findByName($name, $columns = "*"){

		$find = $this->read("SELECT {$columns} FROM {$this->table} WHERE first_name LIKE :partial ", "partial=%{$name}%");

		if($this->error || !$find->rowCount()){

			$this->message = "Sua consulta não retornou usuários!";

			return null;
		}


		return $find->fetchAll(\PDO::FETCH_CLASS,__CLASS__);

	}

	/** @param string|int $id */ 
	/** @param string $columns */
	/** @return array|null */ 
	public function all($limit = 50 , $offset = 0, $columns = "*"){

		$read = $this->read("SELECT {$columns} FROM {$this->table} limit :limit  offset :offset", "limit={$limit}&offset={$offset}");

		if($this->error || !$read->rowCount()){

			$this->message = "Sua consulta não retornou usuários!";

			return null;
		}


		return $read->fetchAll(\PDO::FETCH_CLASS,__CLASS__);

	}


	/** @return object|null */
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


	/** @param null|string */
	/** @return object */
	public function destroy($id = null){

		if(empty($id)){

			$id = $this->id;
		}

		$userRemoved = $this->findById($id);

		$this->delete($this->table, "id={$id}");

		$this->data = null;

		return $this;

	}

}


