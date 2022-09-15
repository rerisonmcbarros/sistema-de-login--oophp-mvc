<?php
session_start();

require_once __DIR__."/vendor/autoload.php";

$route = new \Core\Route("/login", ":");

$route->setNamespace("Controller");

$route->get("/", "App:home");
$route->post("/", "App:home");


$route->dispatch();


//echo "<pre>", var_dump($route), "</pre>";


















?>