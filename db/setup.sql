DROP USER IF EXISTS 'api-local'@'localhost';
CREATE USER IF NOT EXISTS 'api-local'@'localhost';

/* Voor alle tabellen handmatig de minimale requirements geven */
GRANT SELECT ON wideworldimporters.stockgroups TO 'api-local'@'localhost';
