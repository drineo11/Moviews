/* Criação do banco de dados, caso não exista */
create database if not exists moviews;
use moviews;

/* Remoção das tabelas, caso exista, para resetar os dados */
drop table if exists reviews;
drop table if exists movies;
drop table if exists users;

/* Criação da tabela usuários */
create table users (
	use_id int unsigned auto_increment primary key,
	use_name varchar(100),
	use_lastname varchar(100),
	use_email varchar(200),
	use_password varchar(200),
	use_image varchar(200),
	use_token varchar(200),
	use_bio text
);

/* Criação da tabela de filmes */
create table movies (
	mov_id int unsigned auto_increment primary key,
	mov_title varchar(100),
	mov_description text,
	mov_image varchar(200),
	mov_trailer varchar(150),
	mov_category varchar(50),
	mov_length varchar(50),
	use_id int unsigned,
	foreign key(use_id) references users (use_id)
);

/* Criação da tabela de avaliações */
create table reviews (
	rev_id int unsigned auto_increment primary key,
	rev_rating int,
	rev_review text,
	use_id int unsigned,
	mov_id int unsigned,
	foreign key (use_id) references users (use_id),
	foreign key (mov_id) references movies (mov_id)
);