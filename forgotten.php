<?php
	require_once("globals.php");
	require_once("templates/header.php");
	require_once("models/Message.php");
	require_once("dao/UserDAO.php");
	
	$userDao = new UserDAO($conn, $BASE_URL);
	if ($_SESSION["use_token"]) $message -> setMessage("Seja bem-vindo!", "success", "editprofile.php");
?>

<div id="main-container" class="container-fluid">
	<div class="col-md-12">
		<div class="row" id="auth-row">
			<div class="col-md-4" id="send-container">
				<h2> Código </h2>
				<form method="POST">
					<input type="hidden" id="type" name="type" value="send">
					<div class="form-group">
						<label for="email"> E-mail: </label>
						<input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
					</div>
					<div class="form-group">
						<label for="name"> Nome: </label>
						<input type="name" class="form-control" id="name" name="name" placeholder="Digite seu nome, sem sobrenome">
					</div>
					<input type="submit" class="btn card-btn" value="Verificar conta para enviar código">
					<label class="forgotten" for="forgotten">
						<a href="<?=$BASE_URL ?>auth.php">
							Lembrou a senha?
						</a>
					</label>
				</form>
				<?php
					if (isset($_POST["name"]) && isset($_POST["email"])) {
						$email = $_POST["email"];
						$name = $_POST["name"];
						
						if ($email && $name) {
							$stmt_00 = $userDao -> conn -> prepare("select use_lastname, use_update from users where use_email = :use_email and use_name = :use_name");
							
							$stmt_00 -> bindParam(":use_email", $email);
							$stmt_00 -> bindParam(":use_name", $name);
							$stmt_00 -> execute();
							
							if ($stmt_00 -> rowCount() > 0) {
								$data = $stmt_00 -> fetch();
								$lastname = $data["use_lastname"];
								$updateCode = $data["use_update"];
								$acess_key = "300857b4-146e-4bc0-af3f-a25f96e6e159";
								?>
								
								<!-- Envio de e-mail da API -->
								<form action="https://api.staticforms.xyz/submit" method="post">
									<!-- Chave de acesso --> 
									<input type="hidden" name="accessKey" value="<?php echo $acess_key ?>">
									
									<!-- Assunto --> 
									<input type="hidden" name="subject" value="Moviews (código de segurança)">
									
									<!-- E-mail indicado --> 
									<input type="hidden" name="$e-mail" value="<?php echo $email ?>">
									<input type="hidden" name="$ ">
									
									<!-- Nome indicado --> 
									<input type="hidden" name="$nome" value="<?php echo $name . ' ' . $lastname ?>">
									<input type="hidden" name="$	">
									
									<!-- Código de segurança --> 
									<input type="hidden" name="$codigo de segurança" value="<?php echo $updateCode ?>">
									<input type="hidden" name="$	 ">
									
									<!-- Observação --> 
									<input type="hidden" name="$observação" value="O código de segurança será apagado quando houver alteração na senha.">
									
									<!-- E-mail que irá receber --> 
									<input type="hidden" name="replyTo" value="<?php echo $email ?>">
									
									<!-- Página que vai acessar --> 
									<input type="hidden" name="redirectTo" value="<?=$BASE_URL ?>forgotten_sucess.php">
									
									<!-- Botão para confirmar --> 
									<input class="forgottenAPI" type="submit" value="Conta encontrada, encaminhar?">
								</form>
								
								<?php
							} else $message -> setMessage("E-mail e/ou nome incorretos.", "error", "back");
						}
					}
				?>
			</div>
			<div class="col-md-4" id="update-container">
				<h2> Atualizar </h2>
				<form action="<?= $BASE_URL ?>auth_process.php" method="POST">
					<input type="hidden" name="type" value="updateCode">
					<div class="form-group">
						<label for="updateCodey_code"> Código de segurança: </label>
						<input type="text" class="form-control" id="updateCodey_code" name="updateCodey_code" placeholder="Digite o código de segurança">
					</div>
					<div class="form-group">
						<label for="new_password"> Nova senha: </label>
						<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Digite a nova senha">
					</div>
					<div class="form-group">
						<label for="new_password_confirmed"> Confirmação da nova senha: </label>
						<input type="password" class="form-control" id="new_password_confirmed" name="new_password_confirmed" placeholder="Confirme a nova senha">
					</div>
					<input type="submit" class="btn card-btn" value="Atualizar senha">
				</form>
			</div>
		</div>
	</div>
</div>
<?php require_once("templates/footer.php"); ?>