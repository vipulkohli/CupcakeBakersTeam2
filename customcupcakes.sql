SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `customcupcakes` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `customcupcakes` ;

-- -----------------------------------------------------
-- Table `customcupcakes`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `salt` VARCHAR(45) NOT NULL,
  `telephone` VARCHAR(45) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `state` VARCHAR(45) NOT NULL,
  `zip_code` VARCHAR(45) NOT NULL,
  `is_employee` TINYINT NOT NULL DEFAULT 0,
  `date_created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `total_price` DOUBLE NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_orders_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_orders_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `customcupcakes`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`fillings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`fillings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `price` DOUBLE NOT NULL DEFAULT 0.50,
  `rgb` VARCHAR(45) NOT NULL,
  `quantity_sold` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`flavors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`flavors` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `price` DOUBLE NOT NULL DEFAULT 0.50,
  `img_url` VARCHAR(45) NOT NULL,
  `quantity_sold` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`icings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`icings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `price` DOUBLE NOT NULL DEFAULT 0.50,
  `img_url` VARCHAR(45) NOT NULL,
  `quantity_sold` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`cupcakes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`cupcakes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `icing_id` INT NOT NULL,
  `flavor_id` INT NOT NULL,
  `order_id` INT NOT NULL,
  `filling_id` INT NOT NULL DEFAULT -1,
  `quantity` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_cupcakes_1_idx` (`order_id` ASC),
  INDEX `fk_cupcakes_2_idx` (`filling_id` ASC),
  INDEX `fk_cupcakes_3_idx` (`flavor_id` ASC),
  INDEX `fk_cupcakes_4_idx` (`icing_id` ASC),
  CONSTRAINT `fk_cupcakes_1`
    FOREIGN KEY (`order_id`)
    REFERENCES `customcupcakes`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_2`
    FOREIGN KEY (`filling_id`)
    REFERENCES `customcupcakes`.`fillings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_3`
    FOREIGN KEY (`flavor_id`)
    REFERENCES `customcupcakes`.`flavors` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_4`
    FOREIGN KEY (`icing_id`)
    REFERENCES `customcupcakes`.`icings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`toppings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`toppings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `price` DOUBLE NOT NULL DEFAULT 0.50,
  `quantity_sold` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`cupcake_toppings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`cupcake_toppings` (
  `cupcake_id` INT NOT NULL,
  `topping_id` INT NOT NULL,
  PRIMARY KEY (`cupcake_id`, `topping_id`),
  INDEX `fk_cupcake_toppings_2_idx` (`topping_id` ASC),
  CONSTRAINT `fk_cupcake_toppings_1`
    FOREIGN KEY (`cupcake_id`)
    REFERENCES `customcupcakes`.`cupcakes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcake_toppings_2`
    FOREIGN KEY (`topping_id`)
    REFERENCES `customcupcakes`.`toppings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `customcupcakes`.`favorites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customcupcakes`.`favorites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `cupcake_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_favorites_1_idx` (`cupcake_id` ASC),
  INDEX `fk_favorites_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_favorites_1`
    FOREIGN KEY (`cupcake_id`)
    REFERENCES `customcupcakes`.`cupcakes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_favorites_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `customcupcakes`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
