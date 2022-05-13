<?php
	require_once("templates/header.php");
	require_once("models/Message.php");
	require_once("models/User.php");
	require_once("dao/UserDAO.php");
	
	$message -> setMessage("Sua mensagem foi enviada com sucesso!", "success", "index.php");
	$userData = $userDao -> verifyToken(true);
?>