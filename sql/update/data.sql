USE final_exam_s4;

-- capital initial
INSERT INTO capital (montant) VALUES (100000000.00);

-- types de mouvements
INSERT INTO type_mouvement (nom, taux) VALUES 
('Dépôt initial', 0),
('Intérêt perçu', 2.50),
('Retrait capital', 0);

-- mouvement capital
INSERT INTO mouvement_capital (date_mouvement, id_type_mvt, montant, id_capital) VALUES 
(CURDATE(), 1, 50000000.00, 1),
(DATE_SUB(CURDATE(), INTERVAL 10 DAY), 2, 150000.00, 1);

-- types de prêts
INSERT INTO type_pret (nom, taux) VALUES 
('Prêt personnel', 5.5),
('Prêt immobilier', 4.0);

-- modification de taux
INSERT INTO modif_taux (id_type_pret, date, taux) VALUES 
(1, '2025-01-01', 5.0),
(2, '2025-03-01', 3.8);

-- clients
INSERT INTO client (nom, prenom, email, mdp, solde) VALUES 
('Randriamifidisoa', 'Finaritra', 'fina@example.com', '12345', 200000.00),
('Rakoto', 'Jean', 'jean@example.com', '12345', 500000.00);

-- types de transaction
INSERT INTO type_transaction (nom) VALUES 
('Versement'),
('Retrait');

-- transactions
INSERT INTO transaction (id_client, id_type_transaction, date, montant) VALUES 
(1, 1, CURDATE(), 150000.00),
(2, 2, CURDATE(), 30000.00);

-- types de remboursement
INSERT INTO type_remboursement (nom) VALUES 
('Mensuel'),
('Trimestriel');

-- statuts de prêt
INSERT INTO statut_pret (nom) VALUES 
('En cours'),
('Remboursé');

-- statut demande
INSERT INTO statut_demande (nom) VALUES 
('En attente'),
('Acceptée'),
('Rejetée');

-- prêts
INSERT INTO pret (id_type_pret, id_client, date_debut, duree, date_fin, montant, id_type_remboursement, assurance) VALUES 
(1, 1, '2025-01-01', 12, '2025-12-31', 1000000.00, 1, 10000.00),
(2, 2, '2025-02-01', 24, '2027-01-31', 5000000.00, 2, 30000.00);

-- lien prêt -> statut_prêt
INSERT INTO pret_statut_pret (id_pret, id_statut_pret, date) VALUES 
(1, 1, CURDATE()),
(2, 1, CURDATE());

-- demandes de prêt
INSERT INTO demande_pret (id_client, date_demande, duree_demande, montant, id_statut_demande, id_type_remboursement) VALUES 
(1, '2025-05-01', 12, 1500000.00, 1, 1),
(2, '2025-05-02', 18, 2000000.00, 2, 2);

-- historique des statuts de demande
INSERT INTO demande_statut_demmande (id_demande, id_statut_demande) VALUES 
(1, 1),
(2, 2);

-- administrateurs
INSERT INTO admin (nom, prenom, email, mdp) VALUES 
('Admin', 'Principal', 'admin@example.com', 'adminpass');

-- remboursements
INSERT INTO remboursement (id_pret, montant, date_remboursement) VALUES 
(1, 85000.00, '2025-02-01'),
(1, 85000.00, '2025-03-01'),
(2, 250000.00, '2025-03-15');
