<?php

	require_once("models/Review.php");
	require_once("models/Message.php");

	require_once("dao/UserDAO.php");

	class ReviewDao implements ReviewDAOInterface {

	private $conn;
	private $url;
	private $message;

	public function __construct(PDO $conn, $url) {
		$this->conn = $conn;
		$this->url = $url;
		$this->message = new Message($url);
	}

	public function buildReview($data) {

		$reviewObject = new Review();

		$reviewObject->id = $data["rev_id"];
		$reviewObject->rating = $data["rev_rating"];
		$reviewObject->review = $data["rev_review"];
		$reviewObject->users_id = $data["use_id"];
		$reviewObject->movies_id = $data["mov_id"];

		return $reviewObject;

	}

	public function create(Review $review) {

		$stmt = $this->conn->prepare("INSERT INTO reviews (
		rev_rating, rev_review, mov_id, use_id
		) VALUES (
		:rev_rating, :rev_review, :mov_id, :use_id
		)");

		$stmt->bindParam(":rev_rating", $review->rating);
		$stmt->bindParam(":rev_review", $review->review);
		$stmt->bindParam(":mov_id", $review->movies_id);
		$stmt->bindParam(":use_id", $review->users_id);

		$stmt->execute();

		// Mensagem de sucesso por adicionar filme
		$this->message->setMessage("Crítica adicionada com sucesso!", "success", "index.php");

	}

	public function getMoviesReview($id) {

		$reviews = [];

		$stmt = $this->conn->prepare("SELECT * FROM reviews WHERE mov_id = :mov_id");

		$stmt->bindParam(":mov_id", $id);

		$stmt->execute();

		if($stmt->rowCount() > 0) {

		$reviewsData = $stmt->fetchAll();

		$userDao = new UserDao($this->conn, $this->url);

		foreach($reviewsData as $review) {

			$reviewObject = $this->buildReview($review);

			// Chamar dados do usuário
			$user = $userDao->findById($reviewObject->users_id);

			$reviewObject->user = $user;

			$reviews[] = $reviewObject;
		}

		}

		return $reviews;

	}

	public function hasAlreadyReviewed($id, $userId) {

		$stmt = $this->conn->prepare("SELECT * FROM reviews WHERE mov_id = :mov_id AND use_id = :use_id");

		$stmt->bindParam(":mov_id", $id);
		$stmt->bindParam(":use_id", $userId);

		$stmt->execute();

		if($stmt->rowCount() > 0) {
		return true;
		} else {
		return false;
		}

	}

	public function getRatings($id) {

		$stmt = $this->conn->prepare("SELECT * FROM reviews WHERE mov_id = :mov_id");

		$stmt->bindParam(":mov_id", $id);

		$stmt->execute();

		if($stmt->rowCount() > 0) {

		$rating = 0;

		$reviews = $stmt->fetchAll();

		foreach($reviews as $review) {
			$rating += $review["rev_rating"];
		}

		$rating = $rating / count($reviews);

		} else {

		$rating = "Não avaliado";

		}

		return $rating;

	}

	}