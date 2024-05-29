\c construction;
create sequence seqUser Start 1 INCREMENT 1;

CREATE TABLE tbl_users (
  id varchar(200) primary key default concat ('USER', LPAD(nextval('seqUser')::text, 10,'0')),
 name varchar(120) DEFAULT NULL,
 email varchar(120) DEFAULT NULL,
 password varchar(120) DEFAULT NULL,
 created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 role varchar(10)
);


-- Supprimer toutes les entrées de la table
DELETE FROM tbl_users;

-- Réinitialiser la séquence à la valeur initiale
ALTER SEQUENCE seqUser RESTART WITH 1;



 


create sequence seqClient Start 1 INCREMENT 1;
create table client(
  id varchar(200) primary key default concat ('CLT', LPAD(nextval('seqClient')::text, 10, '0')),
  phone varchar(10) UNIQUE
);


create sequence seqMaison start 1 INCREMENT 1;
create table maison(
  id varchar(200) primary key default concat ('MAIS', LPAD(nextval('seqMaison')::text, 10,'0')),
  nom varchar(200),
  duree_jours decimal(20, 2)
);

alter table maison add column typemaison varchar(200);
alter table maison add column descriptions text;
alter table maison add column surface decimal(50,2);


create sequence seqFinition start 1 INCREMENT 1;
create table finition(
  id varchar(200) primary key default concat ('FNT', LPAD(nextval('seqFinition')::text, 10,'0')),
  nom varchar(200),
  pourcentage decimal(20, 2)
);

create sequence seqDevis start 1 INCREMENT 1;
create table devis(
  id varchar(200) primary key default concat ('D', LPAD(nextval('seqDevis')::text, 3,'0')),
  idclient varchar(200),
  idmaison varchar(200),
  idfinition varchar(200),
  datedevis  TIMESTAMP DEFAULT NOW(),
  debut date,
  FOREIGN KEY (idclient) REFERENCES client(id),
  FOREIGN KEY (idmaison) REFERENCES maison(id),
  FOREIGN KEY (idfinition) REFERENCES finition(id)
); --view manao prix total
ALTER TABLE devis DROP CONSTRAINT devis_pkey cascade;
ALTER TABLE devis ALTER COLUMN id TYPE varchar(200);
ALTER TABLE devis ADD PRIMARY KEY (id);
alter table devis add column lieu text;

create table Travaux(
  id varchar(200) primary key,
  nom varchar(200),
  idTravaux_mere varchar(200),
  FOREIGN KEY (idTravaux_mere) REFERENCES Travaux(id)
);



create sequence seqUnite start 1 INCREMENT 1;
create table unite(
  id varchar(200) primary key default concat ('UNT', LPAD(nextval('seqUnite')::text, 10,'0')),
  nom varchar(200)
);

create table detailtravaux(
  iddetailtravaux serial primary key,
  quantite decimal(20, 2),
  prix decimal(20, 2),
  idunite varchar(200),
  idtravaux varchar(200),
  idmaison varchar(200),
  FOREIGN KEY (idunite) REFERENCES unite(id),
  FOREIGN KEY (idtravaux) REFERENCES Travaux(id),
  FOREIGN KEY (idmaison) REFERENCES maison(id)
);
ALTER TABLE detailtravaux ADD COLUMN updated_at TIMESTAMP DEFAULT NOW();






create table typepaiement (
  idtype serial primary key,
  nomtype varchar(200)
);

create table paiementdevis (
  id serial primary key,
  nom varchar(200),
  iddevis varchar(200),
  idtype int,
  FOREIGN KEY (idtype) REFERENCES typepaiement(idtype),
  FOREIGN KEY (iddevis) REFERENCES devis(id)
);

create sequence seqPaiement start 1 INCREMENT 1;
create table paiement(
  id varchar(200) primary key default concat ('PAY', LPAD(nextval('seqPaiement')::text, 10,'0')),
  datepaiement timestamp,
  a_paye decimal(20, 2),
  iddevis varchar(200),
  FOREIGN KEY (iddevis) REFERENCES devis(id)
);

create table historiquepaiement(
  id serial primary key,
  idpaiement varchar(200),
  datepaiement timestamp,
  avancementpaiement decimal(20, 2),
  FOREIGN KEY (idpaiement) REFERENCES paiement(id)
);

create view v_montant_paye as
SELECT paiement.iddevis, SUM(historiquepaiement.avancementpaiement) AS total_paiements
FROM historiquepaiement
JOIN paiement on historiquepaiement.idpaiement = paiement.id
GROUP BY paiement.iddevis;



-- create view v_Encoursdeconstruction as 
--   SELECT d.*, m.nom as nom_maison, f.nom as nom_finition, c.phone, v_devise_prix.prix_total
--   FROM devis d
--   JOIN maison m ON d.idmaison = m.id
--   JOIN finition f on d.idfinition = f.id
--   JOIN client c on d.idclient = c.id
--   join v_devise_prix on v_devise_prix.id_devis = d.id
--   WHERE (d.debut + interval '1 day' * m.duree_jours) >= CURRENT_DATE;



SELECT votre_colonne_timestamp + (votre_colonne_nombre_jours || ' days')::interval AS nouvelle_date
FROM votre_table;


//raha le farany de mitovy
-- create view v_devise_prix as
--   SELECT 
--     d.id,
--     SUM(dt.prix * dt.quantite) AS prix_total,
--     SUM((dt.prix * dt.quantite) *finition.pourcentage/100)+(dt.prix * dt.quantite) as prix_total_finition,
--     dt.idmaison
-- FROM 
--     detailtravaux dt
-- JOIN 
--     devis d ON d.idmaison = dt.idmaison
-- JOIN finition on finition.id = d.idfinition
-- GROUP BY 
--   d.id,
--     dt.idmaison;

create view v_devise_prix_vrai as 
    select devis.id,
    sum(prixavecfinition) as prix_total_finition,
    sum(prixsansdevis) as prix_total,
    devis.idmaison
    from history_prix_Devis
    join devis on history_prix_Devis.iddevis = devis.id
    group by devis.id, devis.idmaison;

create view v_devise_prix as
SELECT 
    d.id AS id_devis,
    dt.idmaison,
    d.datedevis,
    SUM(dt.prix * dt.quantite) AS prix_total,
    SUM((dt.prix * dt.quantite) * (1 + f.pourcentage / 100)) AS prix_total_finition
FROM 
    detailtravaux dt
JOIN 
    devis d ON d.idmaison = dt.idmaison
JOIN 
    finition f ON f.id = d.idfinition
GROUP BY 
    d.id, dt.idmaison;


-- //raha le date tsy miova jiaby
-- create view v_devise_prix as
-- SELECT 
--     d.id AS id_devis,
--     d.idmaison,
--     d.datedevis,
--     SUM(dt.prix * dt.quantite) AS prix_total,
--     SUM((dt.prix * dt.quantite)*finition.pourcentage/100)+(dt.prix * dt.quantite) as prix_total_finition
-- FROM 
--     devis d
-- JOIN 
--     detailtravaux dt ON d.idmaison = dt.idmaison
-- JOIN finition on finition.id = d.idfinition

-- GROUP BY 
--     d.id, d.idmaison;


-- create view v_devise_prix as
-- SELECT 
--     d.id AS id_devis,
--     d.idmaison,
--     d.datedevis,
--     SUM(dt.prix * dt.quantite) AS prix_total,
--     SUM((dt.prix * dt.quantite) * (1 + finition.pourcentage / 100)) AS prix_total_finition
-- FROM 
--     devis d
-- JOIN 
--     detailtravaux dt ON d.idmaison = dt.idmaison
-- JOIN 
--     finition ON finition.id = d.idfinition
-- GROUP BY 
--     d.id, d.idmaison, d.datedevis, finition.pourcentage;


-- create view v_devise_prix as
-- select 
--     d.id AS id_devis,
--     d.idmaison,
--     d.datedevis,
--     sum(history_prix_devis.prixsansdevis) as prix_total,
--     sum(history_prix_devis.prixavecfinition) as prix_total_finition
--     from devis d
--     join history_prix_devis
--     on history_prix_devis.iddevis = d.id
--     GROUP BY 
--     d.id, d.idmaison, d.datedevis;




SELECT 
    SUM(dt.quantite * dt.prix) AS total,
    COALESCE(tp.id, t.id) as idtravaux
FROM 
    detailtravaux dt
JOIN 
    travaux t ON dt.idtravaux = t.id
LEFT JOIN 
    travaux tp ON t.idTravaux_mere = tp.id
GROUP BY 
    COALESCE(tp.id, t.id);



create view v_montant_paye as
SELECT paiement.iddevis, SUM(paiement.a_paye) AS total_paiements
FROM paiement
GROUP BY paiement.iddevis;

 




-- create view v_statistique as
--     SELECT
--       EXTRACT(MONTH FROM datedevis) AS mois,
--       EXTRACT(YEAR FROM datedevis) AS annee,
--       SUM(prix_total) AS montant_total
--   FROM
--       v_devise_prix
--   GROUP BY
--       EXTRACT(MONTH FROM datedevis),
--       EXTRACT(YEAR FROM datedevis)
--   ORDER BY
--       annee, mois;


-- CREATE VIEW v_statistique AS
CREATE VIEW v_statistique AS
SELECT
    months.mois as mois_int,
    TO_CHAR(to_date(months.mois::text, 'MM'), 'Month') AS mois,
    years.annee AS annee,
    COALESCE(SUM(v_devise_prix.prix_total_finition), 0) AS montant_total
FROM
    (
        SELECT generate_series(1, 12) AS mois
    ) AS months
CROSS JOIN
    (
        SELECT DISTINCT EXTRACT(YEAR FROM datedevis) AS annee
        FROM v_devise_prix
    ) AS years
LEFT JOIN
    v_devise_prix ON EXTRACT(MONTH FROM v_devise_prix.datedevis) = months.mois
                  AND EXTRACT(YEAR FROM v_devise_prix.datedevis) = years.annee
GROUP BY
    years.annee, months.mois
ORDER BY
    years.annee, months.mois;





    CREATE VIEW v_Encoursdeconstruction AS
   SELECT
       d.*,
       m.nom as nom_maison,
       f.nom as nom_finition,
       c.phone,
       v_devise_prix.prix_total_finition,
       v_devise_prix,
       COALESCE(vp.total_paiements, 0) AS total_paiements,
       (d.debut + (m.duree_jours || ' days')::interval) AS fin,
       m.duree_jours as jours,
       CASE 
           WHEN v_devise_prix.prix_total_finition = 0 THEN 0 -- Si le prix total est nul, le pourcentage est 0
           ELSE COALESCE(vp.total_paiements, 0) * 100 / v_devise_prix.prix_total_finition
       END AS pourcentage
   FROM
       devis d
   JOIN
       maison m ON d.idmaison = m.id
   JOIN
       finition f ON d.idfinition = f.id
   JOIN
       client c ON d.idclient = c.id
   JOIN
       v_devise_prix ON v_devise_prix.id_devis = d.id
   LEFT JOIN
       v_montant_paye vp ON vp.iddevis = d.id
   WHERE
       (d.debut + interval '1 day' * m.duree_jours) >= CURRENT_DATE;



    CREATE VIEW v_Encoursdeconstruction AS
   SELECT
       d.*,
       m.nom as nom_maison,
       f.nom as nom_finition,
       c.phone,
       v_devise_prix.prix_total_finition,
       v_devise_prix.prix_total,

       COALESCE(vp.total_paiements, 0) AS total_paiements,
       m.duree_jours as jours,
       CASE 
           WHEN v_devise_prix.prix_total_finition = 0 THEN 0 -- Si le prix total est nul, le pourcentage est 0
           ELSE COALESCE(vp.total_paiements, 0) * 100 / v_devise_prix.prix_total_finition
       END AS pourcentage
   FROM
       devis d
   JOIN
       maison m ON d.idmaison = m.id
   JOIN
       finition f ON d.idfinition = f.id
   JOIN
       client c ON d.idclient = c.id
   JOIN
       v_devise_prix ON v_devise_prix.id_devis = d.id
   LEFT JOIN
       v_montant_paye vp ON vp.iddevis = d.id;



create view v_travaux_lib as
select detailtravaux.*, travaux.nom, maison.nom as nommaison, unite.nom as nomunite, travaux.nom as nomtravaux  from travaux
join detailtravaux on travaux.id = detailtravaux.idtravaux
join maison on maison.id = detailtravaux.idmaison
join unite on unite.id = detailtravaux.idunite;



-- create view v_travaux_lib as
-- select detailtravaux.*, travaux.nom, unite.nom as nomunite  from travaux
-- join detailtravaux on travaux.id = detailtravaux.idtravaux
-- join unite on unite.id = detailtravaux.idunite;

-- select distinct on(travaux.id), detailtravaux.* from travaux 
-- join detailtravaux on detailtravaux.idtravaux = travaux.id


create view v_travaux_lib as
SELECT DISTINCT ON (travaux.id) 
    travaux.id AS id_travaux, 
    detailtravaux.*,
    unite.nom nomunite,
    travaux.nom as nom_travaux
FROM 
    travaux
JOIN 
    detailtravaux ON detailtravaux.idtravaux = travaux.id
JOIN unite on unite.id = detailtravaux.idunite
ORDER BY 
    travaux.id, detailtravaux.idtravaux;




create table history_prix_devis (
    iddevis varchar(200) ,
    prixavecfinition decimal(50,2),
    prixsansdevis decimal(50,2),
    FOREIGN KEY (iddevis) REFERENCES devis(id)
);







/////////////////////////////

 CREATE VIEW v_statistique AS
 SELECT
     months.mois as mois_int,
     TO_CHAR(to_date(months.mois::text, 'MM'), 'Month') AS mois,
     years.annee AS annee,
     COALESCE(SUM(v_devise_prix.prix_total_finition), 0) AS montant_total
 FROM
     (
       SELECT generate_series(1, 12) AS mois
   ) AS months
 CROSS JOIN
     (
       SELECT DISTINCT EXTRACT(YEAR FROM datedevis) AS annee
       FROM v_devise_prix
   ) AS years
 LEFT JOIN
     v_devise_prix ON EXTRACT(MONTH FROM v_devise_prix.datedevis) = months.mois
                   AND EXTRACT(YEAR FROM v_devise_prix.datedevis) = years.annee
 GROUP BY
     years.annee, months.mois
 ORDER BY
     years.annee, months.mois;



    CREATE VIEW v_Encoursdeconstruction AS
    SELECT
        d.*,
        m.nom as nom_maison,
        f.nom as nom_finition,
        c.phone,
        v_devise_prix.prix_total_finition,
        v_devise_prix,
        COALESCE(vp.total_paiements, 0) AS total_paiements,
        (d.debut + (m.duree_jours || ' days')::interval) AS fin,
        m.duree_jours as jours,
        CASE
            WHEN v_devise_prix.prix_total_finition = 0 THEN 0 -- Si le prix total est nul, le pourcentage est 0
            ELSE COALESCE(vp.total_paiements, 0) * 100 / v_devise_prix.prix_total_finition
        END AS pourcentage
    FROM
        devis d
    JOIN
        maison m ON d.idmaison = m.id
    JOIN
        finition f ON d.idfinition = f.id
    JOIN
        client c ON d.idclient = c.id
    JOIN
        v_devise_prix ON v_devise_prix.id_devis = d.id
    LEFT JOIN
        v_montant_paye vp ON vp.iddevis = d.id;





    create view v_Encoursdeconstruction as
            SELECT
        d.*,
        m.nom as nom_maison,
        f.nom as nom_finition,
        c.phone,
        v_devise_prix_vrai.prix_total_finition,
        v_devise_prix_vrai,
        COALESCE(vp.total_paiements, 0) AS total_paiements,
        (d.debut + (m.duree_jours || ' days')::interval) AS fin,
        m.duree_jours as jours,
        CASE
            WHEN v_devise_prix_vrai.prix_total_finition = 0 THEN 0 -- Si le prix total est nul, le pourcentage est 0
            ELSE COALESCE(vp.total_paiements, 0) * 100 / v_devise_prix_vrai.prix_total_finition
        END AS pourcentage
    FROM
        devis d
    JOIN
        maison m ON d.idmaison = m.id
    JOIN
        finition f ON d.idfinition = f.id
    JOIN
        client c ON d.idclient = c.id
    JOIN
        v_devise_prix_vrai ON v_devise_prix_vrai.id = d.id
    LEFT JOIN
        v_montant_paye vp ON vp.iddevis = d.id;