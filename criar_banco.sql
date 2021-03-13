create database tcc;
use tcc;

create table usuario(
	id_usuario integer primary key auto_increment,
	nome varchar(30),
    email varchar(45),
    senha varchar(50),
	genero varchar(10),
    inativo boolean DEFAULT 0
);

create table postagem(
	id_postagem integer primary key auto_increment not null,
    id_usuario integer,
    foreign key(id_usuario) references usuario(id_usuario) ON DELETE CASCADE,
    titulo varchar(45),
    conteudo varchar(9999999),
    tags varchar(999999),
    descricao varchar(999999),
    privado boolean,
    denuncias int,
    inativo boolean DEFAULT 0
);

create table comentario(
	id_comentario integer primary key auto_increment not null,
    id_usuario integer,
    id_postagem integer,
    foreign key(id_usuario) references usuario(id_usuario)  ON DELETE CASCADE,
	foreign key(id_postagem) references postagem(id_postagem) ON DELETE CASCADE,
    conteudo varchar(9999999),
    anonimo boolean,
    denuncias int,
    inativo boolean DEFAULT 0
);