ALTER TABLE plat ADD COLUMN "description" TEXT;

UPDATE plat SET "description" = 'Description du plat';

ALTER TABLE plat ADD COLUMN disponible BOOLEAN NOT NULL DEFAULT TRUE;

ALTER TABLE plat
ADD CONSTRAINT ck_plat_prix_positif CHECK (prix > 0),
ADD CONSTRAINT uq_plat_nom UNIQUE (nom);

CREATE TABLE plat_du_jour (
    date_jour DATE PRIMARY KEY,
    id_plat INTEGER NOT NULL REFERENCES plat (id)
);