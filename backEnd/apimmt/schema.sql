create table usuario(
  id integer unsigned not null auto_increment,
  username varchar(40),
  password varchar(255),
  nombres varchar(80),
  apellidos varchar(80),
  correo varchar(80),
  fnac date,
  puntos integer,
  primary key(id)
);
create table curiosidad(
  id integer unsigned not null auto_increment,
  cont varchar(150),
  url varchar(150),
  primary key(id)
);
create table pregunta(
  id integer unsigned not null auto_increment,
  preg varchar(200),
  resp varchar(100),
  inc1 varchar(100),
  inc2 varchar(100),
  inc3 varchar(100),
  primary key(id)
);
create table arbol(
  id integer unsigned not null auto_increment,
  id_usuario integer unsigned not null,
  nombre varchar(40),
  fecha date,
  primary key(id),
  foreign key(id_usuario) references usuario(id)
  on delete cascade
);
create table respuesta(
  id_usuario integer unsigned not null,
  id_pregunta integer unsigned not null,
  fecha date,
  correcto boolean,
  primary key(id_usuario, id_pregunta),
  foreign key(id_usuario) references usuario(id)
  on delete cascade,
  foreign key(id_pregunta) references pregunta(id)
  on delete cascade
);
create table etapa(
  id integer unsigned not null auto_increment,
  id_arbol integer unsigned not null,
  url varchar(150),
  des varchar(255),
  primary key(id),
  foreign key(id_arbol) references arbol(id)
  on delete cascade
);