create table historique_detailtravaux_devis(
    iddevis varchar(200),
    iddetailtravaux int,
    quantite decimal(50,2),
    prix decimal(50,2),
    idunite varchar(200),
    idtravaux varchar(200),
    FOREIGN KEY (idtravaux) REFERENCES travaux(id),
    FOREIGN KEY(idunite) REFERENCES unite(id),
    FOREIGN KEY (iddetailtravaux) REFERENCES detailtravaux(iddetailtravaux),
    FOREIGN KEY (iddevis) REFERENCES devis(id)
);

INSERT INTO historique_detailtravaux_devis (iddevis, iddetailtravaux, quantite, prix, idunite, idtravaux)
SELECT devis.id, iddetailtravaux, quantite, prix, idunite, idtravaux
FROM detailtravaux
join devis on devis.idmaison = detailtravaux.idmaison
where devis.id='DVS0000000003';


(d.debut + (m.duree_jours || ' days')::interval) AS fin

create view v_detailtravaux as(
    select historique_detailtravaux_devis.*,devis.idmaison, devis.debut as debut, (devis.debut + (maison.duree_jours || 'days')::interval)as fin,  quantite*prix as total, unite.nom as nomunite, travaux.nom as nomtravaux, maison.nom as nommaison  
    from historique_detailtravaux_devis
    join devis on devis.id = historique_detailtravaux_devis.iddevis
    join maison on maison.id= devis.idmaison
    join travaux on travaux.id= historique_detailtravaux_devis.idtravaux
    join unite on unite.id = historique_detailtravaux_devis.idunite
);

create view v_detailtravaux as(
    select historique_detailtravaux_devis.*, quantite*prix as total, unite.nom as nomunite, travaux.nom as nomtravaux  
    from historique_detailtravaux_devis
    join devis on devis.id = historique_detailtravaux_devis.iddevis
    join travaux on travaux.id= historique_detailtravaux_devis.idtravaux
    join unite on unite.id = historique_detailtravaux_devis.idunite
);


CREATE TABLE public.detailstravaux_history (
    iddetailtravaux varchar,
    idtravaux varchar,
    idunite varchar,
    quantite double precision,
    prix double precision,
    idmaison varchar,
    action varchar default 'update' ,
    action_date timestamp DEFAULT CURRENT_TIMESTAMP
);




CREATE OR REPLACE FUNCTION detailstravaux_history_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
        INSERT INTO detailstravaux_history VALUES (OLD.iddetailtravaux, OLD.idtravaux, OLD.idunite, OLD.quantite, OLD.prix, OLD.idmaison, 'update', CURRENT_TIMESTAMP);
        RETURN NEW;
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO detailstravaux_history VALUES(OLD.iddetailtravaux, OLD.idtravaux, OLD.idunite, OLD.quantite, OLD.prix, OLD.idmaison, 'delete', CURRENT_TIMESTAMP);
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;




CREATE TRIGGER detailstravaux_history
AFTER UPDATE OR DELETE ON public.detailtravaux
FOR EACH ROW
WHEN (OLD IS NOT NULL)
EXECUTE PROCEDURE detailstravaux_history_trigger();