-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


DROP SCHEMA IF EXISTS `hfdb` ;


CREATE SCHEMA IF NOT EXISTS `hfdb` DEFAULT CHARACTER SET utf8 ;
USE `hfdb` ;

CREATE TABLE IF NOT EXISTS `hfdb`.`Felhasznalo` (
  `felhasznalo_id` INT NOT NULL AUTO_INCREMENT,
  `nev` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `jelszo` VARCHAR(180) NOT NULL,
  `szuletesnap` DATE NOT NULL,
  `statusz` INT(1) NULL,
  PRIMARY KEY (`felhasznalo_id`)
  );

CREATE TABLE IF NOT EXISTS `hfdb`.`Recept` (
  `recept_id` INT NOT NULL AUTO_INCREMENT,
  `nev` VARCHAR(45) NOT NULL,
  `time_min` INT NOT NULL,
  `megjegyzest` VARCHAR(800) NULL,
  `nehezsegi_szint` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`recept_id`)
  );

CREATE TABLE IF NOT EXISTS `hfdb`.`Otthon` (
  `hozzavalo_id` INT NOT NULL,
  `felhasznalo_id` INT NOT NULL, 
  PRIMARY KEY(hozzavalo_id, felhasznalo_id),
  FOREIGN KEY (`felhasznalo_id`) REFERENCES `hfdb`.`Felhasznalo` (`felhasznalo_id`) ON DELETE CASCADE,
  FOREIGN KEY(`hozzavalo_id`) REFERENCES hfdb.Hozzavalok (`hozzavalo_id`) ON DELETE CASCADE
  );

CREATE TABLE IF NOT EXISTS `hfdb`.`Recepteskonyv` (
  `Felhasznalo_id` INT NOT NULL,
  `Recept_id` INT NOT NULL,
  PRIMARY KEY (`Felhasznalo_id`, `Recept_id`),  
  FOREIGN KEY (`Felhasznalo_id`) REFERENCES `hfdb`.`Felhasznalo` (`felhasznalo_id`)  ON DELETE CASCADE,
  FOREIGN KEY (`Recept_id`) REFERENCES `hfdb`.`Recept` (`recept_id`) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS `hfdb`.`Hozzavalok` (
  `hozzavalo_id` INT NOT NULL AUTO_INCREMENT,
  `nev` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`hozzavalo_id`)
  );

CREATE TABLE IF NOT EXISTS `hfdb`.`Osszetevok_listaja` (
  `Recept_id` INT NOT NULL,
  `Hozzavalo_id` INT NOT NULL,
  `mennyiseg` INT NULL,
  PRIMARY KEY (`Recept_id`, `Hozzavalo_id`),
  FOREIGN KEY (`Recept_id`) REFERENCES `hfdb`.`Recept` (`recept_id`)  ON DELETE CASCADE,
  FOREIGN KEY (`Hozzavalo_id`) REFERENCES `hfdb`.`Hozzavalok` (`hozzavalo_id`) ON DELETE CASCADE
  );
-- Egy rendszergazdának muszáj lennie, ha létezik az adatbázis
INSERT INTO `felhasznalo` (`felhasznalo_id`, `nev`, `email`, `jelszo`, `szuletesnap`, `statusz`) 
VALUES 
(NULL, 'Rendszergazda', 'c@koordinator', '$2y$10$UXsUkRdxaErSIwEHSHji.OvlN4BZ7GwgP1tT9IjDXvePwy7hPRH3q', '0002-02-02', '2');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
