<?php $this->layout("template"); ?>	
<main class="principal">
	<section class="section-login">
		<h1>Login de Usuário</h1>
		<div class="message"> <?= ($message ?? ""); ?> </div>
		<form class="form-login" id="login" action="" method="post">
			<input type="hidden" name="token" value="<?= ($session->csrf() ?? '')?>">
			<label for="email">Email</label>
			<input type="text" id="email"  name="email" placeholder="Digite seu Email">
			<label for="password">Senha</label>
			<input type="password" id="password" name="password" placeholder="Senha">
			<input type="submit" form="login" formmethod="post" name="login" value="Entrar">
			<a href="#">Esqueceu sua senha?</a>
			<a href="#">Cadastre-se</a>	
		</form>		
	</section>
</main>

<?php $this->start('style'); ?>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<?php $this->stop(); ?>