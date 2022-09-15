<?php

namespace Controller;


class App{
	

	protected $view;

	public function __construct(){

		$this->view = new \League\Plates\Engine(__DIR__."/../view");

	}

	public function home(){

		echo "pagina home";

		$session = new \Core\Session();
		
		$template = $this->view->render("view-login", ["session" => $session]);

		echo  $template;

	}

}

?>