<?php
	// Inclue apenas uma vez os arquivos abaixo.
	require_once("templates/header.php");
	require_once("models/Message.php");
	require_once("models/User.php");
	require_once("dao/UserDAO.php");
	
	// Verifica se o usuário está validado e retorna seus dados.
	$userData = $userDao -> verifyToken(true);
?>

<div id="main-container" class="container-fluid">
	<!-- Formulário de conexão -->
	<div id="trailer-container">
		<h3> Inserir Trailer </h3>
		<form action="<?=$BASE_URL ?>newmovie.php" method="POST">
			<img src="<?= $BASE_URL ?>../assets/trailer_tutorial/pt_BR_01.png">
			<img src="<?= $BASE_URL ?>../assets/trailer_tutorial/pt_BR_02.png">
			<img src="<?= $BASE_URL ?>../assets/trailer_tutorial/pt_BR_03.png">
		</form>
	</div>
</div>

<?php
	// Inclue apenas uma vez o footer.
	require_once("templates/footer.php");
?>