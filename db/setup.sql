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
