-- Insertions dans la table "maison"
INSERT INTO maison (nom, duree_jours) VALUES
    ('Maison A', 15.5),
    ('Maison B', 20),
    ('Maison C', 10.75);

-- Insertions dans la table "finition"
INSERT INTO finition (nom, pourcentage) VALUES
    ('Finition 1', 5.25),
    ('Finition 2', 8),
    ('Finition 3', 10.5);

-- Insertion de données de test dans la table unite avec des noms abrégés
INSERT INTO unite (nom) VALUES 
('m²'), -- Mètre carré
('m³'), -- Mètre cube
('ml'), -- Mètre linéaire
('fft'),  -- Heure
('pc'); -- Pièce


-- Insertion de données de test dans la table Travaux
INSERT INTO Travaux (id, nom, idTravaux_mere) VALUES 
('000', 'Travaux préparatoires', NULL),
('001', 'mur de soutenement et demi cloture', '000'),
('100', 'Travaux de terrassement', NULL),
('101', 'Decapage des terrains meubles', '100'),
('102', 'Dressage plateforme', '100'),
('200', 'TRAVAUX EN INFRASTRUCTURE', NULL),
('201', 'beton armee dosee', '200'),
('2011', 'semelle isolee', '201'),
('2012', 'amorce poteaux', '201');




-- Insertion de données de test dans la table detailtravaux
INSERT INTO detailtravaux (quantite, prix, idunite, idtravaux, idmaison, datechangement) VALUES 
(26.98, 190000, 'UNT0000000002', '001', 'MAIS0000000002','2023-10-10'),-- 50 mètres carrés de travaux préparatoires à 1000 euros
(30.85, 15000, 'UNT0000000001', '101', 'MAIS0000000002','2023-10-10'), -- 30 mètres carrés de terrassement en infrastructure à 1500 euros
(5, 200, 'UNT0000000001', '2011', 'MAIS0000000002','2023-10-17'),   -- 5 mètres linéaires de dressage de la plateforme à 200 euros
(2, 500, 'UNT0000000001', '2012', 'MAIS0000000002','2023-10-17');   -- 2 mètres linéaires de hérissonnage à 500 euros


SELECT dt.*, t.nom AS nom_travail, u.nom AS nom_unite
FROM detailtravaux dt
JOIN Travaux t ON dt.idtravaux = t.id
JOIN unite u ON dt.idunite = u.id
WHERE dt.idmaison = 'votre_id_maison';


WITH RECURSIVE TravauxHierarchy AS (
  SELECT id, nom, idTravaux_mere, 0 AS niveau
  FROM Travaux
  WHERE idTravaux_mere IS NULL -- Sélectionner les travaux de niveau supérieur
  UNION ALL
  SELECT t.id, t.nom, t.idTravaux_mere, th.niveau + 1
  FROM Travaux t
  JOIN TravauxHierarchy th ON t.idTravaux_mere = th.id
)
SELECT dt.*, t.nom AS nom_travail, u.nom AS nom_unite,
       COALESCE(mere.nom, 'Sans Mère') AS nom_travail_mere
FROM detailtravaux dt
JOIN TravauxHierarchy th ON dt.idtravaux = th.id
JOIN Travaux t ON dt.idtravaux = t.id
JOIN unite u ON dt.idunite = u.id
LEFT JOIN Travaux mere ON t.idTravaux_mere = mere.id
WHERE dt.idmaison = 'MAIS0000000001';





select 
travaux.id AS id_travaux,
    detailtravaux.*,
    unite.nom nomunite
FROM
    travaux
JOIN
    detailtravaux ON detailtravaux.idtravaux = travaux.id, detailtravaux.
JOIN unite on unite.id = detailtravaux.idunite
ORDER BY
    travaux.id, detailtravaux.idtravaux;


create view v_travaux_lib as
SELECT 
    t.id AS id_travaux,
    dt.*,
    u.nom AS nomunite,
    t.nom as nom_travaux
FROM
    travaux t
JOIN
    (SELECT 
        idtravaux,
        MAX(updated_at) AS max_updated_at
    FROM 
        detailtravaux
    GROUP BY 
        idtravaux) subquery 
    ON t.id = subquery.idtravaux
JOIN 
    detailtravaux dt 
    ON dt.idtravaux = subquery.idtravaux AND dt.updated_at = subquery.max_updated_at
JOIN 
    unite u 
    ON u.id = dt.idunite
ORDER BY 
    t.id, dt.idtravaux;
