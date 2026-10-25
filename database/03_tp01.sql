ALTER TABLE plat ADD COLUMN "description" TEXT;

UPDATE plat SET "description" = 'Description du plat';