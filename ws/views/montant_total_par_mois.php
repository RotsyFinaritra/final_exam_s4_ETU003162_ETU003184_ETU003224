<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montant - BankLoan Pro</title>
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/main.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/forms.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/components.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/dashboard.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/simulation.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/tables.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include"navbar.php"?>

    <!-- <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h1>BankLoan Pro</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link">Tableau de Bord</a></li>
                <li><a href="capital.html" class="nav-link">Capital</a></li>
                <li><a href="types-pret.html" class="nav-link">Types de Prêt</a></li>
                <li><a href="demandes.html" class="nav-link">Demandes</a></li>
                <li><a href="interets.html" class="nav-link active">Intérêts</a></li>
                <li><a href="simulation.html" class="nav-link">Simulation</a></li>
            </ul>
        </div>
    </nav> -->

    <main class="main-content">
        <div class="section-header">
            <div>
                <h2>Montant par mois</h2>
                <p> Veuillez entrez les mois et dates pour voir le montant percu</p>
            </div>
            <div class="filter-controls">
                <div class="filter-group">
                    <label>Période Début:</label>
                    <!-- <input type="month" id="moisDebut" onchange="filterInterests()"> -->
                    <input type="number" id="moisDebut" >
                    <input type="number" id="anneeDebut">

                </div>
                <div class="filter-group">
                    <label>Période Fin:</label>
                    <!-- <input type="month" id="moisFin" onchange="filterInterests()"> -->
                    <input type="number" id="moisFin" >
                    <input type="number" id="anneeFin">
                </div>
                <button class="btn-secondary" onclick="filterInterests()">Filtrer</button>
                <button class="btn-secondary" onclick="resetFilters()">Réinitialiser</button>
                <button class="btn-primary" onclick="exportData()">Exporter</button>
            </div>
        </div>

        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Reste non emprunté</th>
                        <th>Remboursements</th>
                        <th>Total disponible</th>
                    </tr>
                </thead>
                <tbody id="montantParMoisTable">
                    <tr>
                        <td>01/2024</td>
                        <td>1 000 000 MGA</td>
                        <td>0 MGA</td>
                        <td>1 000 000 MGA</td>
                    </tr>
                    <tr>
                        <td>02/2024</td>
                        <td>800 000 MGA</td>
                        <td>50 000 MGA</td>
                        <td>850 000 MGA</td>
                    </tr>
                    <tr>
                        <td>03/2024</td>
                        <td>600 000 MGA</td>
                        <td>60 000 MGA</td>
                        <td>660 000 MGA</td>
                    </tr>
                    <!-- Tu rempliras dynamiquement ici plus tard -->
                </tbody>
            </table>
        </div>


    <script src="<?= STATIC_URL ?>/js/data.js">

        // console.log(`${STATIC_URL}`)
    </script>

    <!-- <script src="../js/interets.js"></script> -->
    <script>
        const apiBase = "<?=API_URL?>";
        let chartInstance = null;

        function ajax(method, url, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, apiBase + url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        callback(JSON.parse(xhr.responseText));
                    } else {
                        console.error("Erreur Ajax :", xhr.status, xhr.responseText);
                    }
                }
            };
            xhr.send(data);
        }
    </script>
</body>

</html>