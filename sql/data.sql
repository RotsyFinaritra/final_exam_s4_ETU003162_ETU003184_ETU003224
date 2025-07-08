-- Active: 1751603531872@@127.0.0.1@3306@final_exam_s4
-- Active: 1751603531872@@127.0.0.1@3306
-- Base de données
CREATE DATABASE IF NOT EXISTS final_exam_s4;
USE final_exam_s4;

-- Capital initial
INSERT INTO capital (montant) VALUES (1000000.00);

-- Types de mouvement de capital
INSERT INTO type_mouvement (nom, taux) VALUES
('Dépôt initial', 0),
('Rechargement', 0),
('Retrait pour prêt', 0);

-- Mouvements sur capital
INSERT INTO mouvement_capital (date_mouvement, id_type_mvt, montant, id_capital) VALUES
('2025-01-01', 1, 1000000.00, 1),
('2025-03-01', 2, 200000.00, 1),
('2025-06-01', 3, 150000.00, 1);

-- Types de prêt
INSERT INTO type_pret (nom, taux) VALUES
('Prêt personnel', 5.00),
('Prêt immobilier', 6.50),
('Prêt étudiant', 3.00);

-- Modifications de taux (historique)
INSERT INTO modif_taux (id_type_pret, date, taux) VALUES
(1, '2025-01-01', 5.00),
(2, '2025-01-01', 6.50),
(3, '2025-01-01', 3.00);

-- Clients
INSERT INTO client (nom, prenom, email, mdp, solde) VALUES
('Rabe', 'Tiana', 'tiana.rabe@example.com', 'mdp1', 50000.00),
('Rakoto', 'Fetra', 'fetra.rakoto@example.com', 'mdp2', 120000.00),
('Randria', 'Hery', 'hery.randria@example.com', 'mdp3', 70000.00);

-- Types de transaction
INSERT INTO type_transaction (nom) VALUES
('Dépôt'),
('Retrait'),
('Paiement de prêt');

-- Transactions
INSERT INTO transaction (id_client, id_type_transaction, date, montant) VALUES
(1, 1, '2025-03-01', 20000.00),
(2, 1, '2025-03-03', 50000.00),
(3, 2, '2025-03-05', 15000.00);

-- Types de remboursement
INSERT INTO type_remboursement (nom) VALUES
('Mensuel'),
('Unique'),
('Trimestriel');

-- Statuts des prêts
INSERT INTO statut_pret (nom) VALUES
('En cours'),
('Remboursé'),
('En retard');

-- Statuts des demandes
INSERT INTO statut_demande (nom) VALUES
('En attente'),
('Acceptée'),
('Refusée');

-- Prêts accordés
INSERT INTO pret (id_type_pret, id_client, date_debut, duree, date_fin, montant, id_type_remboursement) VALUES
(1, 1, '2025-04-01', 12, '2026-04-01', 60000.00, 1),
(2, 2, '2025-02-15', 24, '2027-02-15', 100000.00, 1);

-- Demandes de prêt
INSERT INTO demande_pret (id_client, date_demande, duree_demande, montant, id_statut_demande, id_type_remboursement) VALUES
(1, '2025-03-25', 12, 60000.00, 2, 1),
(2, '2025-01-10', 24, 100000.00, 2, 1),
(3, '2025-05-05', 6, 30000.00, 1, 2);

-- Administrateurs
INSERT INTO admin (nom, prenom, email, mdp) VALUES
('Admin', 'Principal', 'admin@bank.com', 'admin123'),
('Manager', 'Julie', 'julie.manager@bank.com', 'securepass');

-- Remboursements effectués
INSERT INTO remboursement (id_pret, montant, date_remboursement) VALUES
(1, 5000.00, '2025-05-01'),
(1, 5000.00, '2025-06-01'),
(2, 4166.67, '2025-03-01');


-- Historique des statuts pour prêt 1
INSERT INTO pret_statut_pret (id_pret, id_statut_pret, date) VALUES
(1, 1, '2025-04-01'), -- En cours à la création
(1, 3, '2025-07-01'); -- Devient en retard

-- Historique des statuts pour prêt 2
INSERT INTO pret_statut_pret (id_pret, id_statut_pret, date) VALUES
(2, 1, '2025-02-15'), -- En cours
(2, 2, '2026-03-01'); -- Devient remboursé


SET FOREIGN_KEY_CHECKS = 0;