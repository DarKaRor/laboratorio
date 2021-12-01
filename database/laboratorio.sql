CREATE DATABASE `laboratorio`;
USE `laboratorio`;

DROP TABLE IF EXISTS `persona`; 

CREATE TABLE `persona`(
    id_persona INT AUTO_INCREMENT,
    cedula INT UNIQUE,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    puesto ENUM('enfermero','doctor','paciente','usuario') DEFAULT 'usuario' NOT NULL,
    autoridad ENUM('1','2','3') DEFAULT '1' NOT NULL,
    PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario`(
    id_usuario INT AUTO_INCREMENT,
    id_persona INT NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id_usuario`),
    FOREIGN KEY (`id_persona`) REFERENCES persona(`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `paciente`;

CREATE TABLE `paciente`(
    id_paciente INT AUTO_INCREMENT,
    id_persona INT NOT NULL,
    peso DOUBLE(10,3),
    genero ENUM('F','M'),
    edad INT,
    correo VARCHAR(150) UNIQUE NOT NULL,
    PRIMARY KEY (`id_paciente`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `examen`;

CREATE TABLE `examen`(
    `id_examen` INT AUTO_INCREMENT,
    `id_paciente` INT NOT NULL,
    `id_enfermero` INT NOT NULL,
    `id_doctor` INT NOT NULL,
    `concluído` BOOLEAN DEFAULT 0,
    `tipo` ENUM('Hemograma','Urinálisis','Heces','Perfil Renal','Perfil lípdico','Perfil hepático','Perfil triode','Panel Básico metabólico','Covid','Sangre') NOT NULL,
    `resultado` VARCHAR(40),
    `fecha` DATETIME,
    PRIMARY KEY (`id_examen`),
    FOREIGN KEY (`id_paciente`) REFERENCES paciente(`id_paciente`),
    FOREIGN KEY (`id_enfermero`) REFERENCES persona(`id_persona`),
    FOREIGN KEY (`id_doctor`) REFERENCES persona(`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `persona` (cedula,nombre,apellido,puesto,autoridad)
VALUES 
(29714201,"Johandry",'López','usuario',3),
(28278945,"Juan","Medina",'doctor',2),
(20164389,"María","Hernandez",'enfermero',1),
(12345678,"Alfonso","Guitierrez","paciente",1),
(25345646,"Lupita","Montes","paciente",1);

INSERT INTO `usuario` (id_persona,correo,password)
VALUES 
(1,'jalp_18@hotmail.com','25d55ad283aa400af464c76d713c07ad'),
(2,'juan.medina@urbe.edu.ve','5e8667a439c68f5145dd2fcbecf02209'),
(3,'maria_hernandez@hotmail.com','4c882dcb24bcb1bc225391a602feca7c');

INSERT INTO `paciente` (id_persona,peso,genero,edad,correo)
VALUES
(4,'80','M',20,'alfonsogtrz20@hotmail.com'),
(5,'50','F',19,'lupitamontes14@hotmail.com');

INSERT INTO `examen` (id_paciente,id_enfermero,id_doctor,tipo,fecha)
VALUES
(1,3,2,'Covid',"2021-11-30 17:11:59"),
(1,3,2,'Hemograma',"2021-11-30 17:11:59"),
(2,3,2,'Covid',"2021-11-30 21:11:16"),
(2,3,2,'Sangre',"2021-11-30 21:11:16");

