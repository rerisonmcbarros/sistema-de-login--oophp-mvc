<?php

namespace Controller;


class App{
	

	protected $view;

	public function __construct(){

		$this->view = new \League\Plates\Engine(__DIR__."/../view");

	}

	public function home(){

		$session = new \Core\Session();

		$login = new \Model\Login();

		if($_POST["login"] && $POST["token"] == $session->csrf ){

			$login->signIn();

			$message = ($login->message() ?? "");

		}
		
		$template = $this->view->render("view-login", ["session" => $session, "message" => $message]);

		echo  $template;

	}

}

?>