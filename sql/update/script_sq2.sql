-- Active: 1751603531872@@127.0.0.1@3306@final_exam_s4
CREATE TABLE tableau_amortissement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT NOT NULL,                  -- Lien vers le prêt concerné
    numero_mois INT NOT NULL,              -- N° de la mensualité (1, 2, 3, ...)
    capital_restant DECIMAL(15,2) NOT NULL,    -- Capital restant dû avant paiement
    amortissement DECIMAL(15,2) NOT NULL,      -- Montant du capital remboursé ce mois
    interet DECIMAL(15,2) NOT NULL,            -- Montant des intérêts payés ce mois
    assurance DECIMAL(15,2) NOT NULL,          -- Montant de l’assurance ce mois
    annuite DECIMAL(15,2) NOT NULL,            -- Total payé ce mois (annuité constante)
    date_paiement DATE NULL,                   -- Date prévue ou effective du paiement
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pret) REFERENCES pret(id)
);
ALTER TABLE tableau_amortissement
ADD COLUMN annee INT DEFAULT 0;
INSERT INTO pret (id_type_pret, id_client, date_debut, duree, date_fin, montant, id_type_remboursement, assurance) VALUES
(1, 1, '2025-07-10', 12, '2026-07-10', 800000, 1, 0.02),
(1, 2, '2025-06-05', 24, '2027-06-05', 1200000, 1, 0.015),
(1, 3, '2025-05-15', 6,  '2025-11-15', 500000, 1, 0.025);


INSERT INTO tableau_amortissement 
(id_pret, numero_mois, capital_restant, amortissement, interet, assurance, annuite, date_paiement) VALUES
(1, 1, 800000, 63000, 8000, 16000, 87000, '2025-08-10'),
(1, 2, 737000, 64000, 7370, 14740, 86410, '2025-09-10');

INSERT INTO tableau_amortissement 
(id_pret, numero_mois, capital_restant, amortissement, interet, assurance, annuite, date_paiement) VALUES
(2, 1, 1200000, 45000, 10000, 18000, 73000, '2025-07-05'),
(2, 2, 1155000, 46000, 9625, 17325, 72950, '2025-08-05');

INSERT INTO tableau_amortissement 
(id_pret, numero_mois, capital_restant, amortissement, interet, assurance, annuite, date_paiement) VALUES
(3, 1, 500000, 80000, 5833, 12500, 96333, '2025-06-15'),
(3, 2, 420000, 83000, 4900, 10500, 98400, '2025-07-15');
