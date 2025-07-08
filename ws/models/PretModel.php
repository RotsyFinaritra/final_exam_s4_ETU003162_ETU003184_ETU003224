<?php
require_once 'BaseModel.php';

class PretModel extends BaseModel
{

    public function generateTableauAmortissement($idPret)
    {
        // Récupérer les infos du prêt
        $pret = $this->getById($idPret);
        if (!$pret) {
            throw new Exception("Prêt non trouvé");
        }

        $capital = (float) $pret['montant'];
        $duree = (int) $pret['duree'];
        $assurance_mensuelle = isset($pret['assurance']) ? (float) $pret['assurance'] : 0.0;
        $date_debut = new DateTime($pret['date_debut']);

        $idTypePret = $pret['id_type_pret'];

        $typePretModel = new TypePretModel();
        $typePret = $typePretModel->getById($idTypePret);

        $taux_mensuel = $typePret['taux'] / 100;


        // Calcul de l'annuité
        $annuite = $capital * (($taux_mensuel * pow(1 + $taux_mensuel, $duree)) / (pow(1 + $taux_mensuel, $duree) - 1));
        // $annuite = $capital * $taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree));
        $capital_restant = $capital;

        // Préparer l'insertion
        $sql = "INSERT INTO tableau_amortissement (
            id_pret, numero_mois, annee, capital_restant, amortissement, interet, assurance, annuite, date_paiement
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $delai = $pret['delai'];

        for ($i = 0; $i < $duree; $i++) {
            $interet = $capital_restant * $taux_mensuel;
            $amortissement = $annuite - $interet;
            if ($amortissement < 0)
                $amortissement = 0;


            $date_paiement = clone $date_debut;
            $date_paiement->modify("+$delai months");
            $date_paiement->modify("+$i months");

            $mois = (int) $date_paiement->format('n'); // 1-12
            $annee = (int) $date_paiement->format('Y');
            $capital_restant -= $amortissement;

            $stmt->execute([
                $idPret,
                $mois,
                $annee,
                round($capital_restant, 2),
                round($amortissement, 2),
                round($interet, 2),
                round($assurance_mensuelle, 2),
                round($annuite, 2),
                $date_paiement->format("Y-m-d")
            ]);


        }

        return true;
    }



    public function getAll()
    {
        $sql = "
        SELECT 
            p.id,
            p.date_debut,
            p.duree,
            p.date_fin,
            p.montant,
            tp.id AS id_type_pret,
            tp.nom AS type_pret,
            c.id AS id_client,
            c.nom AS client_nom,
            c.prenom AS client_prenom,
            tr.id AS id_type_remboursement,
            tr.nom AS type_remboursement,
            sp.id AS id_statut_pret,
            sp.nom AS statut_pret
        FROM pret p
        JOIN type_pret tp ON p.id_type_pret = tp.id
        JOIN client c ON p.id_client = c.id
        LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
        LEFT JOIN (
            SELECT ps1.id_pret, ps1.id_statut_pret
            FROM pret_statut_pret ps1
            INNER JOIN (
                SELECT id_pret, MAX(date) AS max_date
                FROM pret_statut_pret
                GROUP BY id_pret
            ) ps2 ON ps1.id_pret = ps2.id_pret AND ps1.date = ps2.max_date
        ) dernier_statut ON p.id = dernier_statut.id_pret
        LEFT JOIN statut_pret sp ON dernier_statut.id_statut_pret = sp.id
    ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
        SELECT 
            p.*,
            tp.nom AS type_pret,
            c.nom AS client_nom,
            c.prenom AS client_prenom,
            tr.nom AS type_remboursement,
            sp.id AS id_statut_pret,
            sp.nom AS statut_pret
        FROM pret p
        JOIN type_pret tp ON p.id_type_pret = tp.id
        JOIN client c ON p.id_client = c.id
        LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
        LEFT JOIN (
            SELECT ps1.id_pret, ps1.id_statut_pret
            FROM pret_statut_pret ps1
            INNER JOIN (
                SELECT id_pret, MAX(date) AS max_date
                FROM pret_statut_pret
                GROUP BY id_pret
            ) ps2 ON ps1.id_pret = ps2.id_pret AND ps1.date = ps2.max_date
        ) dernier_statut ON p.id = dernier_statut.id_pret
        LEFT JOIN statut_pret sp ON dernier_statut.id_statut_pret = sp.id
        WHERE p.id = ?
    ";

        return $this->fetchOne($sql, [$id]);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO pret (
                id_type_pret, id_client, date_debut, duree, date_fin, montant, id_type_remboursement, assurance, delai
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->execute($sql, [
            $data['id_type_pret'],
            $data['id_client'],
            $data['date_debut'],
            $data['duree'],
            $data['date_fin'],
            $data['montant'],
            $data['id_type_remboursement'] ?? null,
            $data['assurance'] ?? 0,
            $data['delai'] ?? 0
        ]);
    }


    public function update($id, $data)
    {
        $sql = "UPDATE pret SET 
                id_type_pret = ?, 
                id_client = ?, 
                date_debut = ?, 
                duree = ?, 
                date_fin = ?, 
                montant = ?, 
                id_type_remboursement = ?,
                assurance = ?,
                delai = ?
            WHERE id = ?";

        return $this->execute($sql, [
            $data['id_type_pret'],
            $data['id_client'],
            $data['date_debut'],
            $data['duree'],
            $data['date_fin'],
            $data['montant'],
            $data['id_type_remboursement'] ?? null,
            $data['assurance'] ?? 0,
            $data['delai'] ?? 0,
            $id
        ]);
    }


    public function delete($id)
    {
        return $this->execute("DELETE FROM pret WHERE id = ?", [$id]);
    }

    public function getPretsByCriteria($criteria)
    {
        $sql = "
            SELECT 
                p.id,
                p.date_debut,
                p.duree,
                p.date_fin,
                p.montant,
                tp.id AS id_type_pret,
                tp.nom AS type_pret,
                c.id AS id_client,
                c.nom AS client_nom,
                c.prenom AS client_prenom,
                tr.id AS id_type_remboursement,
                tr.nom AS type_remboursement
            FROM pret p
            JOIN type_pret tp ON p.id_type_pret = tp.id
            JOIN client c ON p.id_client = c.id
            LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
            WHERE 1=1
        ";

        $params = [];
        if (!empty($criteria['id_type_pret'])) {
            $sql .= " AND p.id_type_pret = ?";
            $params[] = $criteria['id_type_pret'];
        }
        if (!empty($criteria['id_client'])) {
            $sql .= " AND p.id_client = ?";
            $params[] = $criteria['id_client'];
        }
        if (!empty($criteria['date_debut'])) {
            $sql .= " AND p.date_debut >= ?";
            $params[] = $criteria['date_debut'];
        }
        if (!empty($criteria['date_fin'])) {
            $sql .= " AND p.date_fin <= ?";
            $params[] = $criteria['date_fin'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getInteretsGagnesParMois($moisDebut, $anneeDebut, $moisFin, $anneeFin)
    {
        $resultats = [];

        // Création des objets DateTime
        $dateDebut = DateTime::createFromFormat('Y-m', $anneeDebut . '-' . str_pad($moisDebut, 2, '0', STR_PAD_LEFT));
        $dateFin = DateTime::createFromFormat('Y-m', $anneeFin . '-' . str_pad($moisFin, 2, '0', STR_PAD_LEFT));

        if (!$dateDebut || !$dateFin || $dateDebut > $dateFin) {
            return [];  // Dates invalides
        }

        // On récupère les prêts validés dont la date_debut est compatible
        $sql = "SELECT * FROM pret WHERE  date_debut <= :date_fin AND date_fin >= :date_debut";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':date_fin' => $dateFin->format('Y-m-d'),
            ':date_debut' => $dateDebut->format('Y-m-d')
        ]);
        $prets = $stmt->fetchAll();
        // On boucle mois par mois
        $currentDate = clone $dateDebut;

        while ($currentDate <= $dateFin) {
            $moisCle = $currentDate->format('Y-m');
            $interetMois = 0;
            $interval = $dateDebut->diff($currentDate);
            $numeroMois = ($interval->y * 12) + $interval->m + 1;
            $annee = $currentDate->format('Y');
            // echo "sklfhi";
            foreach ($prets as $pret) {
                // Chercher le paiement pour ce prêt et ce mois précis dans le tableau d'amortissement
                echo ($pret['id']);
                echo ($moisCle);
                $sqlAmort = "SELECT interet FROM tableau_amortissement 
                         WHERE id_pret = :id_pret 
                         AND numero_mois = :mois and annee=:annee";
                $stmtAmort = $this->db->prepare($sqlAmort);
                $stmtAmort->execute([
                    ':id_pret' => $pret['id'],
                    ':mois' => $numeroMois,
                    ':annee' => $annee
                ]);
                $amortissement = $stmtAmort->fetch();

                if ($amortissement) {
                    $interetMois += $amortissement['interet'];
                }
            }

            $resultats[$moisCle] = round($interetMois, 2);
            $currentDate->modify('+1 month');
        }
        return $resultats;
    }
    public function getByIdWithClient($id)
    {
        return $this->fetchOne("SELECT * FROM pret p join client c on p.id_client=c.id WHERE p.id = ?", [$id]);
    }
    public function generateTableauAmortissement2($idPret)
    {
        // Récupérer les infos du prêt
        $pdf = new AmortissementPDF();
        $pret = $this->getByIdWithClient($idPret);
        if (!$pret) {
            throw new Exception("Prêt non trouvé");
        }

        $capital = (float) $pret['montant'];
        $duree = (int) $pret['duree'];
        $assurance_mensuelle = isset($pret['assurance']) ? (float) $pret['assurance'] : 0.0;
        $date_debut = new DateTime($pret['date_debut']);

        $idTypePret = $pret['id_type_pret'];

        $typePretModel = new TypePretModel();
        $typePret = $typePretModel->getById($idTypePret);

        $taux_mensuel = $typePret['taux'] / 100;

        $annuite = $capital * (($taux_mensuel * pow(1 + $taux_mensuel, $duree)) / (pow(1 + $taux_mensuel, $duree) - 1));
        $capital_restant = $capital;

        $delai = $pret['delai'];

        $tableau = [];

        for ($i = 0; $i < $duree; $i++) {
            $interet = $capital_restant * $taux_mensuel;
            $amortissement = $annuite - $interet;
            if ($amortissement < 0)
                $amortissement = 0;

            $date_paiement = clone $date_debut;
            $date_paiement->modify("+$delai months");
            $date_paiement->modify("+$i months");

            $mois = (int) $date_paiement->format('n'); // 1-12
            $annee = (int) $date_paiement->format('Y');
            $capital_restant -= $amortissement;

            $tableau[] = [
                // 'client'=>$pret['nom'],
                // 'montant'=>$pret['montant'],
                // 'duree'=>$pret['duree'],
                'numero' => $i + 1,
                'mois' => $mois,
                'annee' => $annee,
                'date_paiement' => $date_paiement->format("Y-m-d"),
                'capital_restant' => round($capital_restant, 2),
                'amortissement' => round($amortissement, 2),
                'interet' => round($interet, 2),
                'assurance' => round($assurance_mensuelle, 2),
                'annuite' => round($annuite, 2),
            ];
        }
        $pdf->AddPage();
        $pdf->generateAmortissementTable2($tableau, $pret['nom'], $pret['montant'], $pret['duree']);
        $pdfContent = $pdf->Output('', 'S'); // S = retourne le PDF en string

        // Encode en base64
        $pdfBase64 = base64_encode($pdfContent);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="tableau_amortissement.pdf"');
        echo $pdf->Output('S');
        // Répond en JSON
        echo json_encode([
            'success' => true,
            'pdfBase64' => $pdfBase64
        ]);
        // return $tableau;
    }
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }
}
