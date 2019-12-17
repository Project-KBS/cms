/*
 * Maak de API gebruiker aan die we vanuit onze PHP applicatie gebruiken
 */
DROP USER IF EXISTS 'api-local'@'localhost';
CREATE USER IF NOT EXISTS 'api-local'@'localhost';

/*
 * Voor alle predefined tabellen van WWI handmatig de minimale rechten geven
 */
GRANT SELECT ON wideworldimporters.colors       TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockgroups  TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitems   TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.suppliers    TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.packagetypes TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitemstockgroups TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitemholdings TO 'api-local'@'localhost';

/*
 * Om een nieuwe klanten aan te maken
 */
GRANT INSERT ON wideworldimporters.customers TO 'api-local'@'localhost';

/*
 * Om het MAX(customerId) te bepalen
 */
GRANT SELECT ON wideworldimporters.customers TO 'api-local'@'localhost';

/*
 * Om een nieuwe order aan te maken
 */
GRANT INSERT ON wideworldimporters.orders TO 'api-local'@'localhost';

/*
 * Om producten aan een order toe te voegen
 */
GRANT INSERT ON wideworldimporters.orderlines TO 'api-local'@'localhost';

/*
 * Om een nieuwe betaling te maken
 */
GRANT INSERT ON wideworldimporters.customertransactions TO 'api-local'@'localhost';

/*
 * Om de status van een betaling te veranderen
 */
GRANT UPDATE ON wideworldimporters.customertransactions TO 'api-local'@'localhost';

/*
 * Om de paymentId/orderId combinatie op te slaan
 */
GRANT INSERT ON wideworldimporters.invoices TO 'api-local'@'localhost';

/*
 * idem, maar dan om weer uit te lezen
 */
GRANT SELECT ON wideworldimporters.invoices TO 'api-local'@'localhost';

/*
 * Om nieuwe orders op te slaan
 */
GRANT INSERT ON wideworldimporters.orders TO 'api-local'@'localhost';

/*
 * idem, maar dan om weer uit te lezen
 */
GRANT SELECT ON wideworldimporters.orders TO 'api-local'@'localhost';

/*
 * Om producten bij orders toe te voegen
 */
GRANT INSERT ON wideworldimporters.orderlines TO 'api-local'@'localhost';

/*
 * idem, maar dan om weer uit te lezen
 */
GRANT SELECT ON wideworldimporters.orderlines TO 'api-local'@'localhost';

/** Maak onze tabellen aan */
-- MySQL Script generated by MySQL Workbench
-- Wed 11 Dec 2019 12:12:29 PM CET
-- Model: WWI Accounts    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

/*
 * Maak klaar om nieuwe tabellen aan te maken
 */
USE `wideworldimporters` ;
DROP TABLE IF EXISTS `wideworldimporters`.`Account`;
DROP TABLE IF EXISTS `wideworldimporters`.`Review`;
DROP TABLE IF EXISTS `wideworldimporters`.`MailSubscriber`;

/*
 * Maak Account tabel aan
 *
 * Hier worden klantenaccounts in gezet.
 */
CREATE TABLE IF NOT EXISTS `wideworldimporters`.`Account` (
                                                `Email` VARCHAR(320) NOT NULL,
                                                `PasswordHashResult` VARCHAR(255) NOT NULL,
                                                `PasswordHashMethod` VARCHAR(12) NOT NULL,
                                                `FirstName` VARCHAR(45) NOT NULL,
                                                `MiddleName` VARCHAR(30) NULL,
                                                `LastName` VARCHAR(45) NOT NULL,
                                                `AddressStreet` VARCHAR(45) NOT NULL,
                                                `AddressNumber` VARCHAR(45) NOT NULL,
                                                `AddressToevoeging` VARCHAR(45) NULL,
                                                `AddressCity` VARCHAR(45) NOT NULL,
                                                `AddressPostalCode` VARCHAR(7) NOT NULL,
                                                `LastIpAddress` VARCHAR(45) NOT NULL,
                                                `LastUserAgent` VARCHAR(120) NOT NULL,
                                                `CustomerID` INT(11) NULL,
                                                PRIMARY KEY (`Email`),
                                                FOREIGN KEY (`CustomerID`)
                                                    REFERENCES `wideworldimporters`.`Customers` (`CustomerID`)
                                                        ON UPDATE NO ACTION
                                                        ON DELETE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;


/*
 * Maak Review tabel aan
 *
 * Hier worden klantenreviews in gezet.
 */
CREATE TABLE IF NOT EXISTS `wideworldimporters`.`Review` (
                                               `Email` VARCHAR(320) NOT NULL,
                                               `StockItemID` INT(11) NOT NULL,
                                               `Title` VARCHAR(80) NOT NULL,
                                               `Description` LONGTEXT NOT NULL,
                                               `Score` INT(2) NOT NULL,
                                               `UpdatedWhen` DATETIME NOT NULL DEFAULT NOW(),
                                               `CreatedWhen` DATETIME NOT NULL DEFAULT NOW(),
                                               PRIMARY KEY (`Email`, `StockItemID`),
                                               FOREIGN KEY (`Email`)
                                                   REFERENCES `wideworldimporters`.`Account` (`Email`)
                                                       ON UPDATE NO ACTION
                                                       ON DELETE NO ACTION,
                                               FOREIGN KEY (`StockItemID`)
                                                   REFERENCES `wideworldimporters`.`StockItems` (`StockItemID`)
                                                   ON UPDATE NO ACTION
                                                   ON DELETE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;

/*
 * Maak MailSubscriber tabel aan
 *
 * Hier worden e-mail lijst subscribers in gezet.
 */
CREATE TABLE IF NOT EXISTS `wideworldimporters`.`MailSubscriber` (
                                                                     `id` INT NOT NULL AUTO_INCREMENT,
                                                                     `Email` VARCHAR(320) NOT NULL UNIQUE,
                                                                     `CustomerID` INT(11) NOT NULL UNIQUE,
                                                                     `IsSubscribed` TINYINT NOT NULL DEFAULT 0,
                                                                     PRIMARY KEY (`id`),
                                                                     FOREIGN KEY (`Email`)
                                                                         REFERENCES `wideworldimporters`.`Account` (`Email`)
                                                                         ON UPDATE NO ACTION
                                                                         ON DELETE NO ACTION,
                                                                     FOREIGN KEY (`CustomerID`)
                                                                         REFERENCES `wideworldimporters`.`Customers` (`CustomerID`)
                                                                         ON UPDATE NO ACTION
                                                                         ON DELETE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;

/*
 * Om nieuwe gebruikers aan te maken
 */
GRANT INSERT ON wideworldimporters.Account TO 'api-local'@'localhost';

/*
 * Update is nodig om je accountgegevens te wijzigen
 */
GRANT UPDATE ON wideworldimporters.Account TO 'api-local'@'localhost';

/*
 * idem, maar dan om weer uit te lezen
 */
GRANT SELECT ON wideworldimporters.Account TO 'api-local'@'localhost';

/*
 * En voor de europese wetgeving: om accounts te verwijderen
 */
GRANT DELETE ON wideworldimporters.Account TO 'api-local'@'localhost';

/*
 * Om klantenreviews bij producten op te slaan
 */
GRANT INSERT ON wideworldimporters.Review TO 'api-local'@'localhost';

/*
 * Om klantenreviews te bewerken
 */
GRANT UPDATE ON wideworldimporters.Review TO 'api-local'@'localhost';

/*
 * idem, maar dan om weer uit te lezen
 */
GRANT SELECT ON wideworldimporters.Review TO 'api-local'@'localhost';

/*
 * Om de paymentId/orderId combinatie op te slaan
 */
GRANT INSERT ON wideworldimporters.MailSubscriber TO 'api-local'@'localhost';

/*
 * We hebben nu alles aangemaakt dus revert naar oude settings
 */
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

/*
 * Maakt ideal betalingsmogelijkheid aan in de database
 */
INSERT INTO wideworldimporters.PaymentMethods (PaymentMethodID, PaymentMethodName, LastEditedBy, ValidFrom, ValidTo)
VALUES (419, "Ideal", 1, "2019-12-03", "9999-12-31 23:59:59");
