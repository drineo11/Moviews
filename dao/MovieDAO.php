<?php

	require_once("models/Movie.php");
	require_once("models/Message.php");

	
	require_once("dao/ReviewDAO.php");

	class MovieDAO implements MovieDAOInterface {

	private $conn;
	private $url;
	private $message;

	public function __construct(PDO $conn, $url) {
		$this -> conn = $conn;
		$this -> url = $url;
		$this -> message = new Message($url);
	}

	public function buildMovie($data) {

		$movie = new Movie();

		$movie -> id = $data["mov_id"];
		$movie -> title = $data["mov_title"];
		$movie -> description = $data["mov_description"];
		$movie -> image = $data["mov_image"];
		$movie -> trailer = $data["mov_trailer"];
		$movie -> category = $data["mov_category"];
		$movie -> length = $data["mov_length"];
		$movie -> users_id = $data["use_id"];

		
		$reviewDao = new ReviewDao($this -> conn, $this -> url);

		$rating = $reviewDao -> getRatings($movie -> id);

		$movie -> rating = $rating;

		return $movie;

	}

	public function findAll() {

	}

	public function getLatestMovies() {

		$movies = [];

		$stmt = $this -> conn -> query("SELECT * FROM movies ORDER BY mov_id DESC");

		$stmt -> execute();

		if($stmt -> rowCount() > 0) {

		$moviesArray = $stmt -> fetchAll();

		foreach($moviesArray as $movie) {
			$movies[] = $this -> buildMovie($movie);
		}

		}

		return $movies;

	}

	public function getMoviesByCategory($category) {

		$movies = [];

		$stmt = $this -> conn -> prepare("SELECT * FROM movies
									WHERE mov_category = :mov_category
									ORDER BY mov_id DESC");

		$stmt -> bindParam(":mov_category", $category);

		$stmt -> execute();

		if($stmt -> rowCount() > 0) {

		$moviesArray = $stmt -> fetchAll();

		foreach($moviesArray as $movie) {
			$movies[] = $this -> buildMovie($movie);
		}

		}

		return $movies;

	}

	public function getMoviesByUserId($id) {

		$movies = [];

		$stmt = $this -> conn -> prepare("SELECT * FROM movies
									WHERE use_id = :use_id");

		$stmt -> bindParam(":use_id", $id);

		$stmt -> execute();

		if($stmt -> rowCount() > 0) {

		$moviesArray = $stmt -> fetchAll();

		foreach($moviesArray as $movie) {
			$movies[] = $this -> buildMovie($movie);
		}

		}

		return $movies;

	}

	public function findById($id) {

		$movie = [];

		$stmt = $this -> conn -> prepare("SELECT * FROM movies
									WHERE mov_id = :mov_id");

		$stmt -> bindParam(":mov_id", $id);

		$stmt -> execute();

		if($stmt -> rowCount() > 0) {

		$movieData = $stmt -> fetch();

		$movie = $this -> buildMovie($movieData);

		return $movie;

		} else {

		return false;

		}

	}

	public function findByTitle($title) {

		$movies = [];

		$stmt = $this -> conn -> prepare("SELECT * FROM movies
									WHERE mov_title LIKE :mov_title");

		$stmt -> bindValue(":mov_title", '%'.$title.'%');

		$stmt -> execute();

		if($stmt -> rowCount() > 0) {

		$moviesArray = $stmt -> fetchAll();

		foreach($moviesArray as $movie) {
			$movies[] = $this -> buildMovie($movie);
		}

		}

		return $movies;

	}

	public function create(Movie $movie) {

		$stmt = $this -> conn -> prepare("INSERT INTO movies (
		mov_title, mov_description, mov_image, mov_trailer, mov_category, mov_length, use_id
		) VALUES (
		:mov_title, :mov_description, :mov_image, :mov_trailer, :mov_category, :mov_length, :use_id
		)");

		$stmt -> bindParam(":mov_title", $movie -> title);
		$stmt -> bindParam(":mov_description", $movie -> description);
		$stmt -> bindParam(":mov_image", $movie -> image);
		$stmt -> bindParam(":mov_trailer", $movie -> trailer);
		$stmt -> bindParam(":mov_category", $movie -> category);
		$stmt -> bindParam(":mov_length", $movie -> length);
		$stmt -> bindParam(":use_id", $movie -> users_id);

		$stmt -> execute();

		// Mensagem de sucesso por adicionar filme
		$this -> message -> setMessage("Filme adicionado com sucesso!", "success", "index.php");
	}

	public function update(Movie $movie) {

		$stmt = $this -> conn -> prepare("UPDATE movies SET
		mov_title = :mov_title,
		mov_description = :mov_description,
		mov_image = :mov_image,
		mov_category = :mov_category,
		mov_trailer = :mov_trailer,
		mov_length = :mov_length
		WHERE mov_id = :mov_id		
		");

		$stmt -> bindParam(":mov_title", $movie -> title);
		$stmt -> bindParam(":mov_description", $movie -> description);
		$stmt -> bindParam(":mov_image", $movie -> image);
		$stmt -> bindParam(":mov_category", $movie -> category);
		$stmt -> bindParam(":mov_trailer", $movie -> trailer);
		$stmt -> bindParam(":mov_length", $movie -> length);
		$stmt -> bindParam(":mov_id", $movie -> id);

		$stmt -> execute();

		// Mensagem de sucesso por editar filme
		$this -> message -> setMessage("Filme atualizado com sucesso!", "success", "dashboard.php");
	}

	public function destroy($id) {
		$stmt = $this -> conn -> prepare("DELETE FROM movies WHERE mov_id = :mov_id");

		$stmt -> bindParam(":mov_id", $id);

		$stmt -> execute();

		// Mensagem de sucesso por remover filme
		$this -> message -> setMessage("Filme removido com sucesso!", "success", "dashboard.php");
	}
}