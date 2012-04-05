SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role` ;

CREATE  TABLE IF NOT EXISTS `role` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `roleId` VARCHAR(16) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `roleId_UNIQUE` (`roleId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `identity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `identity` ;

CREATE  TABLE IF NOT EXISTS `identity` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `roleId` INT UNSIGNED NOT NULL ,
  `created` DATETIME NOT NULL ,
  `ownerId` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_identity_identity` (`ownerId` ASC) ,
  INDEX `fk_identity_role1` (`roleId` ASC) ,
  CONSTRAINT `fk_identity_identity`
    FOREIGN KEY (`ownerId` )
    REFERENCES `identity` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_identity_role1`
    FOREIGN KEY (`roleId` )
    REFERENCES `role` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `identity_auth_credential`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `identity_auth_credential` ;

CREATE  TABLE IF NOT EXISTS `identity_auth_credential` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `identityId` INT UNSIGNED NOT NULL ,
  `username` VARCHAR(64) NOT NULL ,
  `passwordHash` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) ,
  INDEX `fk_identity_auth_credential_identity1` (`identityId` ASC) ,
  CONSTRAINT `fk_identity_auth_credential_identity1`
    FOREIGN KEY (`identityId` )
    REFERENCES `identity` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `identity_registration`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `identity_registration` ;

CREATE  TABLE IF NOT EXISTS `identity_registration` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `requestIp` VARCHAR(39) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `identityId` INT UNSIGNED NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_identity_registration_identity1` (`identityId` ASC) ,
  CONSTRAINT `fk_identity_registration_identity1`
    FOREIGN KEY (`identityId` )
    REFERENCES `identity` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `role`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO role (`id`, `roleId`) VALUES ('1', 'root');
INSERT INTO role (`id`, `roleId`) VALUES ('2', 'guest');
INSERT INTO role (`id`, `roleId`) VALUES ('3', 'auth');
INSERT INTO role (`id`, `roleId`) VALUES ('4', 'user');
INSERT INTO role (`id`, `roleId`) VALUES ('5', 'admin');
INSERT INTO role (`id`, `roleId`) VALUES ('6', 'selfregistrator');

COMMIT;

-- -----------------------------------------------------
-- Data for table `identity`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO identity (`id`, `roleId`, `created`, `ownerId`) VALUES ('1', '1', '2012-01-01 00:00:00', NULL);

COMMIT;
