-- MySQL Script generated by MySQL Workbench
-- Fri Mar 23 21:23:54 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema slim_todo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema slim_todo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `slim_todo` DEFAULT CHARACTER SET utf8 ;
USE `slim_todo` ;

-- -----------------------------------------------------
-- Table `slim_todo`.`account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`account` (
  `id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `nickname` VARCHAR(45) NOT NULL,
  `lock` ENUM('T', 'F') NOT NULL DEFAULT 'F',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slim_todo`.`tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`tag` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `account_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tag_account_idx` (`account_id` ASC),
  CONSTRAINT `fk_tag_account`
    FOREIGN KEY (`account_id`)
    REFERENCES `slim_todo`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slim_todo`.`project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`project` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `account_id` INT NOT NULL,
  `sort` INT NOT NULL DEFAULT 500,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_project_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_project_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `slim_todo`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slim_todo`.`todo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`todo` (
  `id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `summary` VARCHAR(400) NOT NULL,
  `sort` INT NOT NULL DEFAULT 500,
  `forecast_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_todo_tag1_idx` (`tag_id` ASC),
  INDEX `fk_todo_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_todo_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `slim_todo`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_todo_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `slim_todo`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slim_todo`.`project_account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`project_account` (
  `id` INT NOT NULL,
  `project_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  INDEX `fk_project_account_project1_idx` (`project_id` ASC),
  INDEX `fk_project_account_account1_idx` (`account_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_project_account_project1`
    FOREIGN KEY (`project_id`)
    REFERENCES `slim_todo`.`project` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_account_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `slim_todo`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slim_todo`.`todo_account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `slim_todo`.`todo_account` (
  `id` INT NOT NULL,
  `todo_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_todo_account_todo1_idx` (`todo_id` ASC),
  INDEX `fk_todo_account_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_todo_account_todo1`
    FOREIGN KEY (`todo_id`)
    REFERENCES `slim_todo`.`todo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_todo_account_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `slim_todo`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;