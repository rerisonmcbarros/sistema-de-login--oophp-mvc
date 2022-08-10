<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<header>
		<a href="#">Entrar</a>
		<a href="#">Cadastre-se</a>	
	</header>

	<main>
		<section>
			<h1>Login de Usuário</h1>
			<p>Seja Bem vindo!</p>
			<form id="login" action="" method="post">
				<label for="email">Email</label>
				<input type="text" id="email"  name="email" placeholder="Digite seu Email">
				<label for="password">Senha</label>
				<input type="password" id="password" name="password" placeholder="Senha">
				<input type="submit" form="login" formmethod=post name="login" value="Entrar">
				<a href="#">Esqueceu sua senha?</a>
			</form>
			<?php

			echo "<pre>", var_dump($_POST), "</pre>";
			?>
		</section>
	</main>
</body>
</html>