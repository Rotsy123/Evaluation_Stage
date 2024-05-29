    delete from historique_detailtravaux_devis;
    delete from detailstravaux_history;
    delete from paiementdevis;
    delete from historiquepaiement;
    delete from paiement;
    delete from typepaiement;
    delete from history_prix_devis;
    delete from devis;
    delete from detailtravaux;
    delete from finition;
    delete from maison;
    ALTER SEQUENCE seqMaison RESTART WITH 1;

    delete from travaux;
    delete from unite;
    delete from client;




DELETE FROM tbl_users;

ALTER SEQUENCE seqUser RESTART WITH 1;
