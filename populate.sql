PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
    CREATE TABLE Tweet(userId string, tweet string);
    CREATE TABLE User(userId string, password string, displayName string, followers string);
    INSERT INTO "User" VALUES('eugene','eugene','Eugene','');
    INSERT INTO "User" VALUES('magic','magic','Magic','');
    INSERT INTO "User" VALUES('jhalderm', 'jhalderm', 'Alex Halderman', '');
    INSERT INTO "Tweet" VALUES('jhalderm', 'EECS588 is fun!');
    COMMIT;
