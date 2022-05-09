<?php
	require_once("templates/header.php");
	require_once("models/Message.php");
	
	$message -> setMessage("Código de segurança enviado para o e-mail!", "success", "forgotten.php");
?>