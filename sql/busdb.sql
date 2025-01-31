CREATE DATABASE busdb
DEFAULT CHARACTER SET utf8;

USE busdb;

-- SET GLOBAL event_scheduler = ON;

CREATE TABLE IF NOT EXISTS usuario (
ci INT NOT NULL,
telefono INT NOT NULL,
nombre VARCHAR(200) NOT NULL,
apellido VARCHAR(200),
fechaNac char(10) NOT NULL,
usuario VARCHAR(30) UNIQUE NOT NULL,
clave VARCHAR(255) NOT NULL,
email VARCHAR(255) UNIQUE NOT NULL,
activo BIT NOT NULL,
PRIMARY KEY (ci));

CREATE TABLE IF NOT EXISTS empresa (
id INT AUTO_INCREMENT NOT NULL,
nombre VARCHAR(20) UNIQUE NOT NULL,
email VARCHAR(30),
PRIMARY KEY (id));

CREATE TABLE IF NOT EXISTS empresa_tel (
id INT NOT NULL,
telefono INT NOT NULL,
PRIMARY KEY (id, telefono),
FOREIGN KEY (id) REFERENCES empresa (id) ON UPDATE CASCADE ON DELETE RESTRICT);

CREATE TABLE IF NOT EXISTS viaje (
id INT AUTO_INCREMENT NOT NULL,
fechaHoraSalida DATETIME NOT NULL,
fechaHoraLlegada DATETIME NOT NULL,
origen VARCHAR(30) NOT NULL,
destino VARCHAR(30) NOT NULL,
asientos INT NOT NULL,
categoria ENUM('Común', 'Semi-cama', 'Cama') NOT NULL,
tarifa INT NOT NULL,
wifi BIT NOT NULL,
idemp INT NOT NULL,
PRIMARY KEY (id));

CREATE TABLE IF NOT EXISTS elige (
ciusu INT NOT NULL,
idviaje INT NOT NULL,
fechaHoraCompra DATETIME NOT NULL,
PRIMARY KEY (ciusu, idviaje),
FOREIGN KEY (ciusu) REFERENCES usuario (ci) ON UPDATE CASCADE ON DELETE RESTRICT,
FOREIGN KEY (idviaje) REFERENCES viaje (id) ON UPDATE CASCADE ON DELETE RESTRICT);

CREATE TABLE IF NOT EXISTS elige_asiento (
ciusu INT NOT NULL,
idviaje INT NOT NULL,
num_asiento CHAR(2) NOT NULL,
PRIMARY KEY (ciusu, idviaje, num_asiento),
FOREIGN KEY (ciusu, idviaje) REFERENCES elige (ciusu, idviaje) ON UPDATE CASCADE ON DELETE RESTRICT);

CREATE TABLE IF NOT EXISTS recuperacion_clave (
idrec INT AUTO_INCREMENT UNIQUE NOT NULL,
ciusu INT NOT NULL,
url VARCHAR(255) UNIQUE NOT NULL,
fecha DATETIME NOT NULL,
PRIMARY KEY (idrec),
FOREIGN KEY (ciusu) REFERENCES usuario (ci) ON UPDATE CASCADE ON DELETE RESTRICT);

CREATE TABLE IF NOT EXISTS confirmacion_cuenta (
id INT AUTO_INCREMENT UNIQUE NOT NULL,
ciusu INT NOT NULL,
url VARCHAR(255) UNIQUE NOT NULL,
fecha DATETIME NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (ciusu) REFERENCES usuario (ci) ON UPDATE CASCADE ON DELETE RESTRICT);


-- CREATE EVENT eliminar_peticion
--     ON SCHEDULE EVERY 1 DAY
--     DO
--         DELETE FROM recuperacion_clave 
--         WHERE fecha < DATE_SUB(NOW(), INTERVAL 90 DAY)

-- CREATE EVENT limpieza
--     ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 DAY
--     DO
--       DELETE FROM esquema.tabla WHERE creacion < (CURRENT_TIMESTAMP - INTERVAL 1 DAY);