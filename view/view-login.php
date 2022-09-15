<?php $this->layout("template"); ?>
	
<?php $this->start('style'); ?>
<link rel="stylesheet" type="text/css" href="">
<?php $this->stop(); ?>


<header>
	<a href="#">Entrar</a>
	<a href="#">Cadastre-se</a>	
</header>

<main>
	<section>
		<h1>Login de Usuário</h1>
		<p>Seja Bem vindo!</p>
		<form id="login" action="" method="post">
			<input type="text" name="token" value="<?= ($session->csrf() ?? '')?>">
			<label for="email">Email</label>
			<input type="text" id="email"  name="email" placeholder="Digite seu Email">
			<label for="password">Senha</label>
			<input type="password" id="password" name="password" placeholder="Senha">
			<input type="submit" form="login" formmethod="post" name="login" value="Entrar">
			<a href="#">Esqueceu sua senha?</a>
		</form>		
	</section>
</main>
