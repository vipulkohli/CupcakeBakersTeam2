SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


CREATE SCHEMA IF NOT EXISTS `customcupcakes` DEFAULT CHARACTER SET utf8 ;
USE `customcupcakes` ;

-- -----------------------------------------------------
-- Table `customcupcakes`.`cupcakes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`cupcakes` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`cupcakes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `flavor` VARCHAR(45) NOT NULL,
  `img_url` VARCHAR(200) NOT NULL,
  `quantity_sold` INT(11) NOT NULL,
  `price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`users` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `onmailinglist` TINYINT(4) NOT NULL DEFAULT '0',
  `fname` VARCHAR(45) NOT NULL,
  `lname` VARCHAR(45) NOT NULL,
  `address` VARCHAR(100) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `state` VARCHAR(2) NOT NULL,
  `zip_code` VARCHAR(10) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `telephone` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`frostings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`frostings` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`frostings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `flavor` VARCHAR(45) NOT NULL,
  `img_url` VARCHAR(200) NOT NULL,
  `quantity_sold` INT(11) NOT NULL,
  `price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 25
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`fillings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`fillings` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`fillings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `flavor` VARCHAR(200) NOT NULL,
  `rgb` VARCHAR(45) NOT NULL,
  `quantity_sold` INT(11) NOT NULL,
  `price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`favorites`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`favorites` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`favorites` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `cupcake_id` INT(11) NOT NULL,
  `frosting_id` INT(11) NOT NULL,
  `filling_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_favorites_1_idx` (`cupcake_id` ASC),
  INDEX `fk_favorites_2_idx` (`user_id` ASC),
  INDEX `fk_favorites_3_idx` (`frosting_id` ASC),
  INDEX `fk_favorites_4_idx` (`filling_id` ASC),
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `customcupcakes`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `cupcake_id`
    FOREIGN KEY (`cupcake_id`)
    REFERENCES `customcupcakes`.`cupcakes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `frosting_id`
    FOREIGN KEY (`frosting_id`)
    REFERENCES `customcupcakes`.`frostings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `filling_id`
    FOREIGN KEY (`filling_id`)
    REFERENCES `customcupcakes`.`fillings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`orders` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total_price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_orders_1_idx` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`toppings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`toppings` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`toppings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `quantity_sold` INT(11) NOT NULL,
  `price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `customcupcakes`.`toppings_bridge`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customcupcakes`.`toppings_bridge` ;

CREATE TABLE IF NOT EXISTS `customcupcakes`.`toppings_bridge` (
  `id` INT(11) NOT NULL,
  `favorite_id` INT(11) NOT NULL,
  `topping_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_toppings_bridge_toppings1_idx` (`topping_id` ASC),
  INDEX `fk_toppings_bridge_favorites1_idx` (`favorite_id` ASC),
  CONSTRAINT `fk_toppings_bridge_toppings1`
    FOREIGN KEY (`topping_id`)
    REFERENCES `customcupcakes`.`toppings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_toppings_bridge_favorites1`
    FOREIGN KEY (`favorite_id`)
    REFERENCES `customcupcakes`.`favorites` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
