<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intérêts Gagnés - BankLoan Pro</title>
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
                <h2>Intérêts Gagnés</h2>
                <p>Suivi des revenus d'intérêts de l'établissement financier, Veuillez entrez les mois et dates pour voir l interet percu</p>
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

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Total Intérêts</h3>
                    <p class="stat-value" id="total-interets">0 MGA</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Ce Mois</h3>
                    <p class="stat-value" id="interets-mois">0 MGA</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18" />
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3" />
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Moyenne Mensuelle</h3>
                    <p class="stat-value" id="moyenne-mensuelle">0 MGA</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v6" />
                        <path d="m21 12-6 0m-6 0-6 0" />
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Prêts Actifs</h3>
                    <p class="stat-value" id="prets-actifs">0</p>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3>Évolution des Intérêts Mensuels</h3>
                <!-- <div class="chart-controls">
                    <select id="chart-period" onchange="updateChart()">
                        <option value="12">12 derniers mois</option>
                        <option value="6">6 derniers mois</option>
                        <option value="24">24 derniers mois</option>
                    </select>
                </div> -->
            </div>
            <canvas id="interets-chart"></canvas>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Détail des Intérêts par Mois</h3>
                <div class="table-filters">
                    <input type="text" id="search-interets" placeholder="Rechercher..." class="search-input">
                    <select id="filter-annee" class="filter-select" onchange="filterInterests()">
                        <option value="">Toutes les années</option>
                    </select>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Période</th>
                        <th>Total Intérêts</th>
                        <th>Nombre de Prêts</th>
                        <th>Intérêt Moyen</th>
                        <th>Évolution</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="interets-table">
                    <!-- Dynamically populated -->
                </tbody>
            </table>
        </div>

        <div class="detailed-breakdown">
            <h3>Répartition Détaillée</h3>
            <div class="breakdown-charts">
                <div class="chart-container">
                    <h4>Par Type de Prêt</h4>
                    <canvas id="types-chart"></canvas>
                </div>
                <div class="chart-container">
                    <h4>Par Tranche de Montant</h4>
                    <canvas id="montants-chart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Détails Mois -->
    <div id="details-modal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3>Détails du Mois</h3>
                <span class="close" onclick="closeModal('details-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div id="month-details">
                    <!-- Populated dynamically -->
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('details-modal')">Fermer</button>
                <button type="button" class="btn-primary" onclick="exportMonthDetails()">Exporter</button>
            </div>
        </div>
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

        function filterInterests() {
            const moisDebut = document.getElementById('moisDebut').value;
            const anneeDebut = document.getElementById('anneeDebut').value;
            const moisFin = document.getElementById('moisFin').value;
            const anneeFin = document.getElementById('anneeFin').value;


            // if (!moisDebut || !moisFin) {
            //     alert("Veuillez sélectionner les deux périodes.");
            //     return;
            // }

            // const [anneeDebut, moisDebutNum] = moisDebut.split('-');
            // const [anneeFin, moisFinNum] = moisFin.split('-');

            const params = `mois_debut=${parseInt(moisDebut)}&annee_debut=${anneeDebut}&mois_fin=${parseInt(moisFin)}&annee_fin=${anneeFin}`;

            ajax('GET', `/interets/par-mois?${params}`, null, function(data) {
                afficherInterets(data);
                updateChartWithData(data);
            });
        }

        function afficherInterets(data) {
            const tbody = document.getElementById('interets-table');
            tbody.innerHTML = '';

            let totalInterets = 0;
            let totalPrets = 0;

            for (const [mois, valeur] of Object.entries(data)) {
                const interet = valeur || 0; // valeur est le montant directement
                const nombrePrets = 1; // tu peux ajuster si tu as ce chiffre quelque part
                const interetMoyen = interet; // ou mettre interet / nombrePrets si pertinent

                totalInterets += interet;
                totalPrets += nombrePrets;

                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${mois}</td>
            <td>${interet.toLocaleString()} Ar</td>
            <td>${nombrePrets}</td>
            <td>${interet.toLocaleString()} Ar</td>
            <td>—</td>
            <td><button onclick="showDetails('${mois}')" class='btn-secondary'>Voir</button></td>
        `;
                tbody.appendChild(tr);
            }
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth()+1).padStart(2,'0');
            const moisActuel= `${year}-${month}`;
            const interetMoisActuel = data[moisActuel]!== undefined ? data[moisActuel] : 0 ;
            document.getElementById('interets-mois').innerText = `${interetMoisActuel}`

            document.getElementById('total-interets').innerText = `${totalInterets.toLocaleString()} Ar`;
            const moyenne = totalPrets ? (totalInterets / totalPrets).toFixed(0) : 0;
            document.getElementById('moyenne-mensuelle').innerText = `${moyenne} Ar`;
        }


        function updateChartWithData(data) {
            const labels = Object.keys(data);
            // const values = Object.values(data).map(item => item.interet_total || 0);
            const values = Object.values(data).map(item => item || 0);

            const ctx = document.getElementById('interets-chart').getContext('2d');

            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Intérêts Gagnés',
                        data: values,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        fill: true,
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>