<?php
	require_once("globals.php");
	require_once("db.php");
	require_once("models/User.php");
	require_once("models/Message.php");
	require_once("dao/UserDAO.php");
	
	$message = new Message($BASE_URL);
	$userDao = new UserDAO($conn, $BASE_URL);
	$type = filter_input(INPUT_POST, "type");
	$errorMessage = "Por favor, preencha todos os campos.";
	
	if ($type === "register") {
		$name = filter_input(INPUT_POST, "name");
		$lastname = filter_input(INPUT_POST, "lastname");
		$email = filter_input(INPUT_POST, "email");
		$password = filter_input(INPUT_POST, "password");
		$confirmpassword = filter_input(INPUT_POST, "confirmpassword");
		
		if ($name && $lastname && $email && $password) {
			if ($password === $confirmpassword) {
				$user = new User();
				
				// Caso não haja usuários, o usuário Administrador será criado.
				$stmt = $conn -> prepare("select use_email from users");
				$stmt -> execute();
				if ($stmt -> rowCount() == 0) {
					$stmt = $conn -> prepare("insert into users (use_name, use_lastname, use_email, use_password, use_update, use_image, use_token, use_bio) VALUES ('Administrador', null, 'admin@etec.sp.gov.br', :use_password, :use_update, null, null, null)");
					
					$stmt -> bindParam(":use_password", $user -> generatePassword("000000"));
					$stmt -> bindParam(":use_update", $user -> generateUpdateCode());
					$stmt -> execute();
				}
				
				// Caso não haja um usuário com este e-mail, o mesmo será criado.
				if ($userDao -> findByEmail($email) === false) {
					$userToken = $user -> generateToken();
					$finalPassword = $user -> generatePassword($password);
					$passwordupdateCode = $user -> generateUpdateCode();

					$user -> name = $name;
					$user -> lastname = $lastname;
					$user -> email = $email;
					$user -> password = $finalPassword;
					$user -> updateCode = $passwordupdateCode;
					$user -> token = $userToken;
					
					$auth = true;
					$userDao -> create($user, $auth);
				} else $message -> setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
			} else $message -> setMessage("As senhas não são iguais.", "error", "back");
		} else $message -> setMessage($errorMessage, "error", "back");
	} else if ($type === "login") {
		$email = filter_input(INPUT_POST, "email");
		$password = filter_input(INPUT_POST, "password");
		
		if ($email && $password)
			if ($userDao -> authenticateUser($email, $password)) $message -> setMessage("Seja bem-vindo!", "success", "editprofile.php");
			else $message -> setMessage("Usuário e/ou senha incorretos.", "error", "back");
		else $message -> setMessage($errorMessage, "error", "back");
	} else if ($type === "updateCode") {
		$code = filter_input(INPUT_POST, "updateCodey_code");
		$new_password = filter_input(INPUT_POST, "new_password");
		$new_password_confirmed = filter_input(INPUT_POST, "new_password_confirmed");
		
		if ($code && $new_password && $new_password_confirmed)
			if ($new_password_confirmed == $new_password)
				if ($userDao -> updatePassword($code, $new_password)) $message -> setMessage("Senha atualizada com sucesso!", "success", "auth.php");
				else $message -> setMessage("código de segurança inválido.", "error", "back");
			else $message -> setMessage("As senhas não são iguais.", "error", "back");
		else $message -> setMessage($errorMessage, "error", "back");
	} else $message -> setMessage("Informações inválidas!", "error", "index.php");