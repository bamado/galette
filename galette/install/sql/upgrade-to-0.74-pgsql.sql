-- sequence for title
DROP SEQUENCE IF EXISTS galette_title_id_seq;
CREATE SEQUENCE galette_title_id_seq
    START 1
    INCREMENT 1
    MAXVALUE 2147483647
    MINVALUE 1
    CACHE 1;

DROP TABLE IF EXISTS galette_title CASCADE;
CREATE TABLE galette_title (
  id_title integer DEFAULT nextval('galette_title_id_seq'::text) NOT NULL,
  short_label character varying(10) DEFAULT '' NOT NULL,
  long_label character varying(30) DEFAULT '',
  PRIMARY KEY (id_title)
);

-- insert required data for proper conversion
INSERT INTO galette_title(short_label, long_label) VALUES ('Mr.', 'Mister');
INSERT INTO galette_title(short_label, long_label) VALUES ('Mrs.', 'Mrs.');
INSERT INTO galette_title(short_label, long_label) VALUES ('Miss', 'Miss');

ALTER TABLE galette_adherents ALTER COLUMN titre_adh TYPE integer;
ALTER TABLE galette_adherents ADD CONSTRAINT galette_adherents_titre_adh_fkey FOREIGN KEY (titre_adh) REFERENCES galette_title(id_title);

ALTER TABLE galette_adherents ALTER COLUMN mdp_adh TYPE character varying(60);

ALTER TABLE galette_adherents ADD sexe_adh smallint DEFAULT 0;
UPDATE galette_adherents SET sexe_adh = titre_adh;

UPDATE galette_database SET version = 0.702;
