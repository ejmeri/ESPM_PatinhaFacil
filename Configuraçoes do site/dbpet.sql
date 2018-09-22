-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema patinhaf_patinhafacil
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema patinhaf_patinhafacil
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `patinhaf_patinhafacil` DEFAULT CHARACTER SET utf8 ;
USE `patinhaf_patinhafacil` ;

-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`genero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`genero` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`porte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`porte` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`especie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`especie` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`raca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`raca` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `EspecieId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Raca_Especie1_idx` (`EspecieId` ASC),
  CONSTRAINT `fk_Raca_Especie1`
    FOREIGN KEY (`EspecieId`)
    REFERENCES `patinhaf_patinhafacil`.`especie` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`area`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`area` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(85) NOT NULL,
  `DtInclusao` DATETIME NULL DEFAULT NULL,
  `DtAtualizacao` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`pelagem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`pelagem` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`animal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`animal` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtNascimento` VARCHAR(45) NULL DEFAULT NULL,
  `Peso` VARCHAR(45) NULL DEFAULT NULL,
  `Descricao` VARCHAR(500) NULL DEFAULT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `Adotado` INT(11) NULL DEFAULT '0',
  `Castrado` INT(11) NOT NULL DEFAULT '0',
  `RacaId` INT(11) NOT NULL,
  `PorteId` INT(11) NOT NULL,
  `GeneroId` INT(11) NOT NULL,
  `PelagemId` INT(11) NOT NULL,
  `AreaId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Animal_Raca1_idx` (`RacaId` ASC),
  INDEX `fk_Animal_Porte1_idx` (`PorteId` ASC),
  INDEX `fk_Animal_Genero1_idx` (`GeneroId` ASC),
  INDEX `fk_animal_Pelagem1_idx` (`PelagemId` ASC),
  INDEX `fk_animal_Area1_idx` (`AreaId` ASC),
  CONSTRAINT `fk_Animal_Genero1`
    FOREIGN KEY (`GeneroId`)
    REFERENCES `patinhaf_patinhafacil`.`genero` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Animal_Porte1`
    FOREIGN KEY (`PorteId`)
    REFERENCES `patinhaf_patinhafacil`.`porte` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Animal_Raca1`
    FOREIGN KEY (`RacaId`)
    REFERENCES `patinhaf_patinhafacil`.`raca` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_animal_AreaId`
    FOREIGN KEY (`AreaId`)
    REFERENCES `patinhaf_patinhafacil`.`area` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_animal_Pelagem1`
    FOREIGN KEY (`PelagemId`)
    REFERENCES `patinhaf_patinhafacil`.`pelagem` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`animalimagem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`animalimagem` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(255) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `AnimalId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_animalimagem_animal1_idx` (`AnimalId` ASC),
  CONSTRAINT `fk_animalimagem_animal1`
    FOREIGN KEY (`AnimalId`)
    REFERENCES `patinhaf_patinhafacil`.`animal` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`pessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`pessoa` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `CpfCnpj` VARCHAR(14) NOT NULL,
  `Rg` VARCHAR(25) NULL DEFAULT NULL,
  `Nome` VARCHAR(80) NOT NULL,
  `Apelido` VARCHAR(60) NULL DEFAULT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 141
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`autenticacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`autenticacao` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(255) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  `Autenticado` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Autenticacao_pessoa1_idx` (`PessoaId` ASC),
  CONSTRAINT `fk_Autenticacao_pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 59
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`caracteristicapet`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`caracteristicapet` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `EspecieId` INT(11) NOT NULL,
  `PelagemId` INT(11) NOT NULL,
  `PorteId` INT(11) NOT NULL,
  `GeneroId` INT(11) NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_caracteristicapet_pessoa1_idx` (`PessoaId` ASC),
  CONSTRAINT `fk_caracteristicapet_pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`estado` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `Sigla` VARCHAR(2) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtATualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`cidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`cidade` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `EstadoId` INT(11) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Cidade_Estado1_idx` (`EstadoId` ASC),
  CONSTRAINT `fk_Cidade_Estado1`
    FOREIGN KEY (`EstadoId`)
    REFERENCES `patinhaf_patinhafacil`.`estado` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5572
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`cep`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`cep` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Numero` VARCHAR(45) NOT NULL,
  `CidadeId` INT(11) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtATualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Cep_Cidade1_idx` (`CidadeId` ASC),
  CONSTRAINT `fk_Cep_Cidade1`
    FOREIGN KEY (`CidadeId`)
    REFERENCES `patinhaf_patinhafacil`.`cidade` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`ddd`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`ddd` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Numero` INT(11) NOT NULL,
  `Regiao` VARCHAR(255) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `EstadoId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `ddd_estadoId_idx` (`EstadoId` ASC),
  CONSTRAINT `ddd_estadoId`
    FOREIGN KEY (`EstadoId`)
    REFERENCES `patinhaf_patinhafacil`.`estado` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 74
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`tipopessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`tipopessoa` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`pessoaanimal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`pessoaanimal` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `AnimalId` INT(11) NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  `TipoPessoaId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Item_Animal1_idx` (`AnimalId` ASC),
  INDEX `fk_item_pessoa1_idx` (`PessoaId` ASC),
  INDEX `fk_pessoaanimal_tipopessoa1_idx` (`TipoPessoaId` ASC),
  CONSTRAINT `fk_Item_Animal1`
    FOREIGN KEY (`AnimalId`)
    REFERENCES `patinhaf_patinhafacil`.`animal` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pessoaanimal_tipopessoa1`
    FOREIGN KEY (`TipoPessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`tipopessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`doacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`doacao` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `AdotadorId` INT(11) NOT NULL,
  `PessoaAnimalId` INT(11) NOT NULL,
  `PorqueAdotar` VARCHAR(500) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_doacao_pessoaanimal1_idx` (`PessoaAnimalId` ASC),
  CONSTRAINT `fk_doacao_pessoaanimal1`
    FOREIGN KEY (`PessoaAnimalId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoaanimal` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`tipoemail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`tipoemail` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`email`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`email` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(255) NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  `TipoEmailId` INT(11) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `Principal` BIT(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Email_Pessoa1_idx` (`PessoaId` ASC),
  INDEX `fk_Email_TipoEmail1_idx` (`TipoEmailId` ASC),
  CONSTRAINT `fk_Email_Pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Email_TipoEmail1`
    FOREIGN KEY (`TipoEmailId`)
    REFERENCES `patinhaf_patinhafacil`.`tipoemail` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`endereco` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `Numero` INT(11) NOT NULL,
  `Cep` VARCHAR(9) NOT NULL,
  `Logradouro` VARCHAR(255) NULL DEFAULT NULL,
  `Complemento` VARCHAR(45) NULL DEFAULT NULL,
  `PontoReferencia` VARCHAR(45) NULL DEFAULT NULL,
  `DtAtualizacao` DATETIME NULL DEFAULT NULL,
  `DtInclusao` DATETIME NULL DEFAULT NULL,
  `PessoaId` INT(11) NOT NULL,
  `DddId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Endereco_pessoa1_idx` (`PessoaId` ASC),
  INDEX `fk_DDD_dddd1_idx` (`DddId` ASC),
  CONSTRAINT `fk_Endereco_ddd`
    FOREIGN KEY (`DddId`)
    REFERENCES `patinhaf_patinhafacil`.`ddd` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Endereco_pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`preferencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`preferencia` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `EspecieId` INT(11) NULL DEFAULT NULL,
  `RacaId` INT(11) NULL DEFAULT NULL,
  `GeneroId` INT(11) NULL DEFAULT NULL,
  `PelagemId` INT(11) NULL DEFAULT NULL,
  `PorteId` INT(11) NULL DEFAULT NULL,
  `Email` INT(11) NULL DEFAULT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_pessoa_preferencia_idx` (`PessoaId` ASC),
  CONSTRAINT `fk_pessoa_preferencia`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`tipotelefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`tipotelefone` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`telefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`telefone` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Numero` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  `TipoTelefoneId` INT(11) NOT NULL,
  `Principal` BIT(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Telefone_Pessoa1_idx` (`PessoaId` ASC),
  INDEX `fk_Telefone_TipoTelefone1_idx` (`TipoTelefoneId` ASC),
  CONSTRAINT `fk_Telefone_Pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Telefone_TipoTelefone1`
    FOREIGN KEY (`TipoTelefoneId`)
    REFERENCES `patinhaf_patinhafacil`.`tipotelefone` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`tipoendereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`tipoendereco` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `patinhaf_patinhafacil`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patinhaf_patinhafacil`.`usuario` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Login` VARCHAR(255) NOT NULL,
  `Senha` VARCHAR(255) NOT NULL,
  `PessoaId` INT(11) NOT NULL,
  `DtInclusao` DATETIME NOT NULL,
  `DtAtualizacao` DATETIME NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Login_UNIQUE` (`Login` ASC),
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC),
  INDEX `fk_Usuario_Pessoa1_idx` (`PessoaId` ASC),
  CONSTRAINT `fk_Usuario_Pessoa1`
    FOREIGN KEY (`PessoaId`)
    REFERENCES `patinhaf_patinhafacil`.`pessoa` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 127
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
