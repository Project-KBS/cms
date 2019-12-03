DROP USER IF EXISTS 'api-local'@'localhost';
CREATE USER IF NOT EXISTS 'api-local'@'localhost';

/* Voor alle tabellen handmatig de minimale requirements geven */
GRANT SELECT ON wideworldimporters.colors       TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockgroups  TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitems   TO 'api-local'@'localhost';
GRANT UPDATE ON wideworldimporters.stockitems   TO 'api-local'@'localhost'; /* VERWIJDER IN PRODUCTIE ******* */
GRANT SELECT ON wideworldimporters.suppliers    TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.packagetypes TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitemstockgroups TO 'api-local'@'localhost';
GRANT SELECT ON wideworldimporters.stockitemholdings TO 'api-local'@'localhost';

/* Om een nieuwe klanten aan te maken */
GRANT INSERT ON wideworldimporters.customers TO 'api-local'@'localhost';
/* Om het MAX(customerId) te bepalen */
GRANT SELECT ON wideworldimporters.customers TO 'api-local'@'localhost';

/* Om een nieuwe order aan te maken */
GRANT INSERT ON wideworldimporters.orders TO 'api-local'@'localhost';
/* Om producten aan een order toe te voegen */
GRANT INSERT ON wideworldimporters.orderlines TO 'api-local'@'localhost';

/* Om een nieuwe betaling te maken */
GRANT INSERT ON wideworldimporters.customertransactions TO 'api-local'@'localhost';
/* Om de status van een betaling te veranderen */
GRANT UPDATE ON wideworldimporters.customertransactions TO 'api-local'@'localhost';

/* Maakt ideal betalingsmogelijkheid aan in de database*/
INSERT INTO wideworldimporters.Paymentmethods (PaymentMethodID, PaymentMethodName, LastEditedBy, ValidFrom, Validto)
VALUES ( 419, "Ideal", 1, "2019-12-03", "9999-12-31 23:59:59");
