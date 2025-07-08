-- Active: 1751603531872@@127.0.0.1@3306@final_exam_s4
CREATE DATABASE final_exam_s4;
-- Table capital
CREATE TABLE capital (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(15,2) NOT NULL
);

-- Table type_mouvement
CREATE TABLE type_mouvement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux DECIMAL(5,2) DEFAULT 0
);

-- Table mouvement_capital
CREATE TABLE mouvement_capital (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_mouvement DATE NOT NULL,
    id_type_mvt INT NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    id_capital INT NOT NULL,
    FOREIGN KEY (id_type_mvt) REFERENCES type_mouvement(id),
    FOREIGN KEY (id_capital) REFERENCES capital(id)
);

-- Table type_pret
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux DECIMAL NOT NULL
);

-- Table modif_taux
CREATE TABLE modif_taux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_type_pret INT NOT NULL,
    date DATE NOT NULL,
    taux DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (id_type_pret) REFERENCES type_pret(id)
);

-- Table client
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL,
    solde DECIMAL(15,2) DEFAULT 0
);

-- Table type_transaction
CREATE TABLE type_transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table transaction
CREATE TABLE transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_type_transaction INT NOT NULL,
    date DATE NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_type_transaction) REFERENCES type_transaction(id)
);
CREATE TABLE type_remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);
-- Table pret
CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_type_pret INT NOT NULL,
    id_client INT NOT NULL,
    date_debut DATE NOT NULL,
    duree INT NOT NULL,
    date_fin DATE NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    id_type_remboursement int,
    FOREIGN KEY (id_type_remboursement) REFERENCES type_remboursement(id),
    FOREIGN KEY (id_type_pret) REFERENCES type_pret(id),
    FOREIGN KEY (id_client) REFERENCES client(id)
);

-- Table statut_pret
CREATE TABLE statut_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE OR REPLACE TABLE pret_statut_pret(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT,
    id_statut_pret INT,
    date DATE,
    FOREIGN KEY (id_pret) REFERENCES pret(id),
    FOREIGN KEY (id_statut_pret) REFERENCES statut_pret(id)
)

-- Table statut_demande
CREATE TABLE statut_demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table demande_pret
CREATE TABLE demande_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date_demande DATE NOT NULL,
    duree_demande INT NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    id_statut_demande int,
    id_type_remboursement int,
    FOREIGN KEY (id_type_remboursement) REFERENCES type_remboursement(id),
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_statut_demande) REFERENCES statut_demande(id)
);

-- Table admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
);

-- Table remboursement
CREATE TABLE remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    date_remboursement DATE NOT NULL,
    FOREIGN KEY (id_pret) REFERENCES pret(id)
);
