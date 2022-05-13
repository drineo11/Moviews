<?php
	require_once("models/User.php");
	require_once("models/Message.php");
	
	class UserDAO implements UserDAOInterface {
		public $conn;
		private $url;
		private $message;
		
		public function __construct(PDO $conn, $url) {
			$this -> conn = $conn;
			$this -> url = $url;
			$this -> message = new Message($url);
		}
		
		public function buildUser($data) {
			$user = new User();
			
			$user -> id = $data["use_id"];
			$user -> name = $data["use_name"];
			$user -> lastname = $data["use_lastname"];
			$user -> email = $data["use_email"];
			$user -> password = $data["use_password"];
			$user -> image = $data["use_image"];
			$user -> bio = $data["use_bio"];
			$user -> token = $data["use_token"];
			
			return $user;
		}
		
		public function create(User $user, $authUser = false) {
			$stmt = $this -> conn -> prepare("insert into users (use_name, use_lastname, use_email, use_password, use_token) values (:use_name, :use_lastname, :use_email, :use_password, :use_token)");
			
			$stmt -> bindParam(":use_name", $user -> name);
			$stmt -> bindParam(":use_lastname", $user -> lastname);
			$stmt -> bindParam(":use_email", $user -> email);
			$stmt -> bindParam(":use_password", $user -> password);
			$stmt -> bindParam(":use_token", $user -> token);
			$stmt -> execute();
			
			if ($authUser) $this -> setTokenToSession($user -> token);
		}
		
		public function update(User $user, $redirect = true) {
			$stmt = $this -> conn -> prepare("update users set
				use_name = :use_name,
				use_lastname = :use_lastname,
				use_email = :use_email,
				use_image = :use_image,
				use_bio = :use_bio,
				use_token = :use_token
				WHERE use_id = :use_id
			");
			
			$stmt -> bindParam(":use_name", $user -> name);
			$stmt -> bindParam(":use_lastname", $user -> lastname);
			$stmt -> bindParam(":use_email", $user -> email);
			$stmt -> bindParam(":use_image", $user -> image);
			$stmt -> bindParam(":use_bio", $user -> bio);
			$stmt -> bindParam(":use_token", $user -> token);
			$stmt -> bindParam(":use_id", $user -> id);
			
			$stmt -> execute();
			if ($redirect) $this -> message -> setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
		}
		
		public function verifyToken($protected = false) {
			if (!empty($_SESSION["use_token"])) {
				$token = $_SESSION["use_token"];
				$user = $this -> findByToken($token);
				
				if ($user) return $user;
				else if ($protected) $this -> message -> setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
			} else if ($protected) $this -> message -> setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
		}
		
		public function setTokenToSession($token, $redirect = true) {
			$_SESSION["use_token"] = $token;
			
			if ($redirect) $this -> message -> setMessage("Seja muito bem-vindo!", "success", "editprofile.php");
		}
		
		public function authenticateUser($email, $password) {
			$user = $this -> findByEmail($email);
			
			if ($user) {
				if (password_verify($password, $user -> password)) {
					$token = $user -> generateToken();
					$this -> setTokenToSession($token, false);
					$user -> token = $token;
					$this -> update($user, false);
					
					return true;
				} else return false;
			} else return false;
		}
		
		public function findByEmail($email) {
			if ($email != "") {
				$stmt = $this -> conn -> prepare("select * from users where use_email = :use_email");
				
				$stmt -> bindParam(":use_email", $email);
				$stmt -> execute();
				
				if ($stmt -> rowCount() > 0) {
					$data = $stmt -> fetch();
					$user = $this -> buildUser($data);
					
					return $user;
				} else return false;
			} else return false;
		}
		
		public function findById($id) {
			if ($id != "") {
				$stmt = $this -> conn -> prepare("select * from users where use_id = :use_id");
				
				$stmt -> bindParam(":use_id", $id);
				$stmt -> execute();
				
				if ($stmt -> rowCount() > 0) {
					$data = $stmt -> fetch();
					$user = $this -> buildUser($data);
					
					return $user;
				} else return false;
			} else return false;
		}
		
		public function findByToken($token) {
			if ($token != "") {
				$stmt = $this -> conn -> prepare("select * from users where use_token = :use_token");
				
				$stmt -> bindParam(":use_token", $token);
				$stmt -> execute();
				
				if ($stmt -> rowCount() > 0) {
					$data = $stmt -> fetch();
					$user = $this -> buildUser($data);
					
					return $user;
				} else return false;
			} else return false;
		}
		
		public function destroyToken() {
			$_SESSION["use_token"] = "";
			
			$this -> message -> setMessage("Você fez o logout com sucesso!", "success", "index.php");
		}
		
		public function changePassword(User $user) {
			$stmt = $this -> conn -> prepare("update users set use_password = :use_password where use_id = :use_id");
			
			$stmt -> bindParam(":use_password", $user -> password);
			$stmt -> bindParam(":use_id", $user -> id);
			$stmt -> execute();
			
			$this -> message -> setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
		}
	}