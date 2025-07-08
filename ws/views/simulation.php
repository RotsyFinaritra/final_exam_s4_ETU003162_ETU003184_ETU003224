<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Prêt - BankLoan Pro</title>
    <!-- <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/components.css">
    <link rel="stylesheet" href="../styles/forms.css">
    <link rel="stylesheet" href="../styles/tables.css"> -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/main.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/components.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/dashboard.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/forms.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/tables.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/simulation.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
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
                <li><a href="interets.html" class="nav-link">Intérêts</a></li>
                <li><a href="simulation.html" class="nav-link active">Simulation</a></li>
            </ul>
        </div>
    </nav> -->
    <?php include "navbar.php" ?>


    <main class="main-content">
        <div class="section-header">
            <div class="header-content">
                <h2>Simulateur de Prêt</h2>
                <p>Calculez votre tableau d'amortissement et générez un PDF professionnel</p>
            </div>
            <div class="header-actions">
                <button class="btn-secondary" onclick="resetSimulation()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="m3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                    </svg>
                    Réinitialiser
                </button>
                <button class="btn-primary" onclick="showModal('save-simulation-modal')" style="display: none;" id="save-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17,21 17,13 7,13 7,21" />
                        <polyline points="7,3 7,8 15,8" />
                    </svg>
                    Sauvegarder
                </button>
            </div>
        </div>

        <div class="simulation-workspace">
            <div class="simulation-form-container">
                <div class="form-card">
                    <div class="form-header">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                            </svg>
                            Paramètres du Prêt
                        </h3>
                        <p>Configurez les paramètres de votre simulation</p>
                    </div>

                    <form id="simulation-form" class="simulation-form">
                        <div class="form-group">
                            <label for="sim-type">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Type de Prêt
                            </label>
                            <select id="sim-type" name="typePret" onchange="updateTypeInfo()">
                                <option value="">Sélectionner un type de prêt</option>
                            </select>
                            <div id="type-info" class="type-info" style="display: none;">
                                <!-- Type information will be displayed here -->
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sim-montant">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2v20m8-10H4" />
                                    </svg>
                                    Montant du Prêt (MGA) *
                                </label>
                                <input type="number" id="sim-montant" name="montant" required min="0" step="0.01" placeholder="Ex: 50 000">
                                <div class="input-hint" id="montant-hint"></div>
                            </div>
                            <div class="form-group">
                                <label for="sim-taux">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
                                    </svg>
                                    Taux d'Intérêt Mensuel (%) *
                                </label>
                                <input type="number" id="sim-taux" name="taux" required min="0" step="0.01" placeholder="Ex: 1.5">
                            </div>
                            <!-- <div class="form-group">
                                <label for="sim-taux">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
                                    </svg>
                                    Assurance 
                                </label>
                                <input class="input" type="number"  name="assurance" required min="0" step="0.01" placeholder="Ex: 1.5">
                            </div> -->
                            <div class="form-group">
                                <label for="sim-assurance">Assurance (% annuel)</label>
                                <input type="number" id="sim-assurance" placeholder="Ex: 1" step="0.01">
                            </div>


                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sim-duree">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12,6 12,12 16,14" />
                                    </svg>
                                    Durée (mois) *
                                </label>
                                <input type="number" id="sim-duree" name="duree" required min="1" placeholder="Ex: 60">
                                <div class="input-hint" id="duree-hint"></div>
                            </div>
                            <div class="form-group">
                                <label for="sim-date">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    Date de Début
                                </label>
                                <input type="date" id="sim-date" name="dateDebut">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sim-nom">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Nom du Client
                            </label>
                            <input type="text" id="sim-nom" name="nomClient" placeholder="Nom pour le document PDF (optionnel)">
                        </div>

                        <button type="button" class="btn-primary btn-large" onclick="calculerSimulation()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                            </svg>
                            Calculer la Simulation
                        </button>
                    </form>
                </div>
            </div>

            <div class="simulation-results-container" id="simulation-results" style="display: none;">
                <div class="results-card">
                    <div class="results-header">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3v18h18" />
                                <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3" />
                            </svg>
                            Résultats de la Simulation
                        </h3>
                        <p>Synthèse financière de votre prêt</p>
                    </div>

                    <div class="result-summary">
                        <div class="result-item highlight">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                    <line x1="2" y1="9" x2="22" y2="9" />
                                </svg>
                                Mensualité
                            </div>
                            <span id="result-mensualite" class="result-value primary">0 MGA</span>
                        </div>
                        <div class="result-item">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20m8-10H4" />
                                </svg>
                                Total à Payer
                            </div>
                            <span id="result-total" class="result-value">0 MGA</span>
                        </div>
                        <div class="result-item">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
                                </svg>
                                Total Intérêts
                            </div>
                            <span id="result-interets" class="result-value">0 MGA</span>
                        </div>
                        <div class="result-item">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M12 1v6m0 6v6" />
                                </svg>
                                Taux Total
                            </div>
                            <span id="result-taux-total" class="result-value">0%</span>
                        </div>
                    </div>

                    <div class="result-actions">
                        <button class="btn-primary" onclick="simuler()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14,2 14,8 20,8" />
                            </svg>
                            Générer PDF
                        </button>
                        <button class="btn-secondary" onclick="showModal2('save-simulation-modal')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17,21 17,13 7,13 7,21" />
                                <polyline points="7,3 7,8 15,8" />
                            </svg>
                            Sauvegarder
                        </button>
                        <button class="btn-secondary" onclick="soumettre_demande()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17,21 17,13 7,13 7,21" />
                                <polyline points="7,3 7,8 15,8" />
                            </svg>
                            Soumettre en tant que Demande Pret
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="amortissement-section" id="amortissement-container" style="display: none;">
            <div class="amortissement-card">
                <div class="amortissement-header">
                    <div class="header-info">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3v18h18" />
                                <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3" />
                            </svg>
                            Tableau d'Amortissement
                        </h3>
                        <p>Détail des échéances de remboursement</p>
                    </div>
                    <div class="amortissement-controls">
                        <select id="view-mode" onchange="changeViewMode()" class="control-select">
                            <option value="all">Toutes les échéances</option>
                            <option value="yearly">Vue annuelle</option>
                            <option value="first-year">Première année</option>
                        </select>
                        <button class="btn-secondary btn-sm" onclick="exportTableauCSV()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7,10 12,15 17,10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table amortissement-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Date</th>
                                <th>Mensualité</th>
                                <th>Capital</th>
                                <th>Intérêts</th>
                                <th>Capital Restant</th>
                                <th>% Remboursé</th>
                            </tr>
                        </thead>
                        <tbody id="amortissement-table">
                            <!-- Dynamically populated -->
                        </tbody>
                    </table>
                </div>

                <div class="amortissement-summary">
                    <h4>Récapitulatif</h4>
                    <div class="summary-stats">
                        <div class="summary-item">
                            <span>Total Capital:</span>
                            <span id="summary-capital" class="summary-value">0 MGA</span>
                        </div>
                        <div class="summary-item">
                            <span>Total Intérêts:</span>
                            <span id="summary-interets" class="summary-value">0 MGA</span>
                        </div>
                        <div class="summary-item">
                            <span>Total Général:</span>
                            <span id="summary-total" class="summary-value primary">0 MGA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="saved-simulations-section">
            <div class="saved-simulations-card">
                <div class="section-header-small">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17,21 17,13 7,13 7,21" />
                            <polyline points="7,3 7,8 15,8" />
                        </svg>
                        Simulations Sauvegardées
                    </h3>
                    <p>Accédez à vos simulations précédentes</p>
                    <button onclick="comparer2simulations()" class="btn-primary">Comparer les simulations</button>
                </div>
                <div class="simulations-list" id="simulations-list">
                    <!-- Populated dynamically -->
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Sauvegarde Simulation -->
    <div id="save-simulation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17,21 17,13 7,13 7,21" />
                        <polyline points="7,3 7,8 15,8" />
                    </svg>
                    Sauvegarder la Simulation
                </h3>
                <span class="close" onclick="closeModal('save-simulation-modal')">&times;</span>
            </div>
            <!-- <form id="save-simulation-form"> -->
            <!-- <div class="form-group">
                    <label for="save-nom">Nom de la Simulation *</label>
                    <input type="text" id="save-nom" name="nom" required placeholder="Ex: Prêt Personnel - M. Dupont">
                </div>
                <div class="form-group">
                    <label for="save-description">Description</label>
                    <textarea id="save-description" name="description" rows="3" placeholder="Description optionnelle de la simulation..."></textarea>
                </div> -->
            <div id="simulation-details"></div>
            <!-- <button onclick="closeModal('save-simulation-modal')">Fermer</button> -->
            <div>
                <button type="button" class="btn-secondary" onclick="closeModal('save-simulation-modal')">Annuler</button>
                <button class="btn-primary" onclick='sauvegarder()'>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17,21 17,13 7,13 7,21" />
                        <polyline points="7,3 7,8 15,8" />
                    </svg>
                    Sauvegarder
                </button>
            </div>
            <!-- </form> -->
        </div>
    </div>
    <!-- <div id="save-simulation-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Simulation sauvegardée</h3>
            <div id="simulation-details"></div>
            <button onclick="closeModal('save-simulation-modal')">Fermer</button>
        </div>
    </div> -->
    <div id="comparaison-container" style="display: flex; gap: 20px; margin-top: 20px;"></div>
    <script src="<?= STATIC_URL ?>/js/data.js"></script>

    <script src="<?= STATIC_URL ?>/js/simulation.js"></script>

    <script>
        let All_taux_pret = null;

        function simuler() {
            if (!currentSimulation || !currentSimulation.tableauAmortissement) {
                alert('Veuillez d’abord effectuer une simulation.');
                return;
            }

            const data = {
                tableau: currentSimulation.tableauAmortissement,
                nomClient: currentSimulation.nomClient || 'Simulation',
                montant: currentSimulation.montant,
                mensualite: currentSimulation.mensualite,
                totalInterets: currentSimulation.totalInterets,
                totalAPayer: currentSimulation.totalAPayer,
                tauxTotal: currentSimulation.tauxTotal
            };

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= API_URL ?>/export-pdf', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        console.log(response);
                        if (response.success) {
                            const blob = b64toBlob(response.pdfBase64, 'application/pdf');
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${currentSimulation.nomClient || 'Simulation'}.pdf`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                        } else {
                            alert('Erreur : ' + response.message);
                        }
                    } else {
                        alert('Erreur lors de la génération du PDF');
                    }
                }
            };
            xhr.send(JSON.stringify(data));
        }
        document.addEventListener('DOMContentLoaded', function() {
            // simuler();
            loadSavedSimulations();
            get_All_taux_pret();
        });

        function get_All_taux_pret() {
            ajax('GET', '/type_prets', null, function(types) {
                // select.innerHTML = '<option value="">Sélectionner un type</option>';
                All_taux_pret = types;
            });
        }

        function ajax(method, url, data, callback) {
            const apiBase = "<?= API_URL ?>";
            const xhr = new XMLHttpRequest();
            xhr.open(method, apiBase + url, true);
            console.log(apiBase + url)
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

        function soumettre_demande() {
            const simulationToSave = {
                id_type_pret: currentSimulation.id_type_pret,
                date_debut: currentSimulation.dateDebut,
                duree_demande: currentSimulation.dureeMois,
                date_demande: currentSimulation.dateDebut,
                montant: currentSimulation.montant,
                id_type_remboursement: currentSimulation.id_type_remboursement || null,
                assurance: currentSimulation.totalAssurance || 0,
                id_client: 1
            };

            const jsonData = JSON.stringify(simulationToSave); // string JSON

            ajax(
                "POST",
                "/demande_prets/create",
                jsonData, // le corps est la string JSON
                (data) => {
                    showAlert('✅ Demande de prêt soumise avec succès!', 'success');
                    resetForm();

                    // setTimeout(() => {
                    //     if (confirm('Souhaitez-vous revenir à la liste des demandes?')) {
                    //         window.location.href = '<?= BASE_URL ?>/demandeNonValide';
                    //     }
                    // }, 2000);
                },
                (status, response) => {
                    console.error(`Erreur ${status}: ${response}`);
                    showAlert('❌ Erreur lors de la soumission de la demande', 'error');
                }
            );
        }


        function loadTypesForSimulation() {
            const select = document.getElementById('sim-type');

            if (!select) return;

            select.innerHTML = '<option value="">Chargement...</option>';

            ajax('GET', '/type_prets', null, function(types) {
                select.innerHTML = '<option value="">Sélectionner un type</option>';

                types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = `${type.nom} (${(type.taux)}%)`;
                    option.dataset.taux = type.taux;
                    select.appendChild(option);
                });
            });
        }

        function updateTypeInfo() {
            const select = document.getElementById('sim-type');
            const typeInfo = document.getElementById('type-info');
            const tauxInput = document.getElementById('sim-taux');
            const montantInput = document.getElementById('sim-montant');
            const dureeInput = document.getElementById('sim-duree');

            if (!select || !select.value) {
                if (typeInfo) typeInfo.style.display = 'none';
                return;
            }

            const selectedOption = select.options[select.selectedIndex];
            const taux = parseFloat(selectedOption.dataset.taux);
            console.log("Taux sélectionné:", taux);
            // Auto-fill taux
            if (tauxInput) tauxInput.value = taux;

            if (typeInfo) {
                typeInfo.innerHTML = `
            <div class="type-details">
                <strong>Informations du type de prêt:</strong>
                <div class="type-detail-grid">
                    <div> Taux: ${dataManager.formatPercent(taux)}</div>
                </div>
            </div>
        `;
                typeInfo.style.display = 'block';
            }
        }

        function exporterPDF() {
            if (!currentSimulation || !currentSimulation.tableauAmortissement) {
                alert('Veuillez d’abord effectuer une simulation.');
                return;
            }

            const data = 'tableau=' + encodeURIComponent(JSON.stringify(currentSimulation.tableauAmortissement)) +
                '&nomClient=' + encodeURIComponent(currentSimulation.nomClient || 'Simulation') +
                '&montant=' + currentSimulation.montant +
                '&mensualite=' + currentSimulation.mensualite +
                '&totalInterets=' + currentSimulation.totalInterets +
                '&totalAPayer=' + currentSimulation.totalAPayer +
                '&tauxTotal=' + currentSimulation.tauxTotal;

            ajax('POST', 'export-pdf', data, function(response) {
                if (response.success) {
                    const blob = b64toBlob(response.pdfBase64, 'application/pdf');
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${currentSimulation.nomClient || 'Simulation'}.pdf`;
                    a.click();
                } else {
                    alert('Erreur : ' + response.message);
                }
            });
        }


        // Convertir base64 en Blob (utilisé car XMLHttpRequest ne gère pas directement le téléchargement binaire)
        function b64toBlob(b64Data, contentType = '', sliceSize = 512) {
            const byteCharacters = atob(b64Data);
            const byteArrays = [];

            for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                const slice = byteCharacters.slice(offset, offset + sliceSize);
                const byteNumbers = Array.from(slice).map(char => char.charCodeAt(0));
                const byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            return new Blob(byteArrays, {
                type: contentType
            });
        }

        function getPretSimule_checked() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            return Array.from(checkboxes);
        }

        function comparer2simulations() {
            const checkedBoxes = getPretSimule_checked();

            if (checkedBoxes.length !== 2) {
                alert("Veuillez sélectionner exactement 2 simulations pour comparer.");
                return;
            }
            const simIds = checkedBoxes.map(cb => cb.dataset.id);
            const simsToCompare = simIds.map(id => allsimulations.find(s => s.id == id));

            if (simsToCompare.includes(undefined)) {
                alert("Simulation introuvable.");
                return;
            }

            const container = document.getElementById('comparaison-container');

            // Ajout du style pour le conteneur principal
            container.style.cssText = `
        display: flex;
        gap: 20px;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    `;

            // Construire l'HTML en une seule fois
            container.innerHTML = simsToCompare.map(sim => {
                // Calculer la simulation à partir de la donnée
                const nouvelleSimulation = calculerSimulation2(sim);
console.log(nouvelleSimulation)
                return `
        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px;
            width: 45%;
            min-width: 350px;
            box-sizing: border-box;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)'" 
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'">
            
            <h3 style="
                color: #2c3e50;
                margin-bottom: 20px;
                font-size: 1.3em;
                font-weight: 600;
                text-align: center;
                padding-bottom: 15px;
                border-bottom: 2px solid #ecf0f1;
            ">Simulation: ${sim.nomClient || 'Sans nom'}</h3>
            
            <div style="margin-bottom: 20px;">
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 8px 0;
                    border-bottom: 1px solid #f8f9fa;
                    font-size: 0.95em;
                "><strong style="color: #34495e;">Montant:</strong> 
                <span style="color: #2c3e50; font-weight: 600;">${sim.montant.toLocaleString()} €</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 8px 0;
                    border-bottom: 1px solid #f8f9fa;
                    font-size: 0.95em;
                "><strong style="color: #34495e;">Durée:</strong> 
                <span style="color: #2c3e50; font-weight: 600;">${sim.dureeMois || sim.duree} mois</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 8px 0;
                    border-bottom: 1px solid #f8f9fa;
                    font-size: 0.95em;
                "><strong style="color: #34495e;">Taux Mensuel:</strong> 
                <span style="color: #2c3e50; font-weight: 600;">${nouvelleSimulation.tauxMensuel}%</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 8px 0;
                    border-bottom: 1px solid #f8f9fa;
                    font-size: 0.95em;
                "><strong style="color: #34495e;">Date Début:</strong> 
                <span style="color: #2c3e50; font-weight: 600;">${sim.date_debut || sim.dateDebut}</span></p>
            </div>

            <hr style="
                margin: 20px 0;
                border: none;
                height: 2px;
                background: linear-gradient(90deg, transparent, #667eea, transparent);
            "/>

            <div style="
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 8px;
                padding: 15px;
                margin-top: 15px;
            ">
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 10px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.5);
                    font-size: 0.95em;
                "><strong style="color: #495057;">Mensualité:</strong> 
                <span style="color: #28a745; font-weight: 700; font-size: 1.05em;">${Math.round(nouvelleSimulation.mensualite)} Ar</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 10px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.5);
                    font-size: 0.95em;
                "><strong style="color: #495057;">Total à Payer:</strong> 
                <span style="color: #dc3545; font-weight: 700; font-size: 1.05em;">${Math.round(nouvelleSimulation.totalAPayer)} Ar</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 10px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.5);
                    font-size: 0.95em;
                "><strong style="color: #495057;">Total Intérêts:</strong> 
                <span style="color: #ffc107; font-weight: 700; font-size: 1.05em;">${Math.round(nouvelleSimulation.totalInterets) } Ar</span></p>
                
                <p style="
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    padding: 10px 0;
                    font-size: 0.95em;
                "><strong style="color: #495057;">Taux Total:</strong> 
                <span style="color: #17a2b8; font-weight: 700; font-size: 1.05em;">${Math.round(nouvelleSimulation.tauxTotal)} %</span></p>
            </div>
        </div>
        `;
            }).join('');
        }


        function calculerSimulation2(simulation) {
            const montant = parseFloat(simulation.montant);
            const tauxvariable = All_taux_pret.find(a => a.id === simulation.id_type_pret);
            // const simulation = simulations.find(s => s.id === id);
            const taux = tauxvariable.taux;
            console.log(taux);
            const duree = parseInt(simulation.duree);
            const dateDebut = simulation.date_debut;
            const nomClient = simulation.nomClient || '';
            const id_type_pret = simulation.id_type_pret || null;
            const assuranceAnnuelPourcent = parseFloat(simulation.assurance || 0);
            const assuranceMensuelle = (montant * (assuranceAnnuelPourcent / 100)) / 12; // assurance en Ar/mois

            // Validation basique
            if (!montant || !taux || !duree) {
                showNotification('Veuillez remplir tous les champs obligatoires', 'error');
                return null;
            }
            if (montant <= 0 || taux < 0 || duree <= 0) {
                showNotification('Veuillez entrer des valeurs valides', 'error');
                return null;
            }

            const tauxMensuel = taux;
            let mensualiteSansAssurance = (montant * (tauxMensuel * Math.pow(1 + tauxMensuel, duree))) /
                (Math.pow(1 + tauxMensuel, duree) - 1);
            const mensualiteAvecAssurance = mensualiteSansAssurance + assuranceMensuelle;

            const totalAPayer = mensualiteAvecAssurance * duree;
            const totalInterets = (mensualiteSansAssurance * duree) - montant;
            const tauxTotal = (totalInterets / montant);
            const totalAssurance = assuranceMensuelle * duree;
            const totalGlobal = totalAPayer;

            // const tableauAmortissement = generateAmortizationTable(montant, tauxMensuel, duree, dateDebut, assuranceMensuelle);

            const nouvelleSimulation = {
                montant,
                id_type_pret,
                tauxMensuel: taux, // si tu veux garder en %, sinon garder taux simple
                dureeMois: duree,
                mensualite: mensualiteSansAssurance,
                totalInterets,
                totalAPayer,
                tauxTotal,
                // tableauAmortissement,
                nomClient,
                dateDebut,
                totalAssurance,
                totalGlobal,
                assuranceAnnuelPourcent
            };

            // Optionnel : afficher directement les résultats
            // displaySimulationResults(nouvelleSimulation);

            return nouvelleSimulation;
        }

        function showModal2(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                afficherSimulationDetails(); // ➕ on appelle l’affichage ici
            }
        }

        // function closeModal(modalId) {
        //     const modal = document.getElementById(modalId);
        //     if (modal) {
        //         modal.style.display = 'none';
        //     }
        // }

        function afficherSimulationDetails() {
            const container = document.getElementById('simulation-details');
            if (!container || !currentSimulation) return;

            const {
                montant,
                tauxMensuel,
                dureeMois,
                mensualite,
                totalInterets,
                totalAPayer,
                nomClient,
                dateDebut,
                totalAssurance
            } = currentSimulation;

            container.innerHTML = `
        <p><strong>Client :</strong> ${nomClient || '-'}</p>
        <p><strong>Date début :</strong> ${dateDebut || '-'}</p>
        <p><strong>Montant :</strong> ${montant.toLocaleString()} Ar</p>
        <p><strong>Taux mensuel :</strong> ${(tauxMensuel * 100).toFixed(2)}%</p>
        <p><strong>Durée :</strong> ${dureeMois} mois</p>
        <p><strong>Mensualité :</strong> ${mensualite.toLocaleString(undefined, {maximumFractionDigits:0})} Ar</p>
        <p><strong>Total Intérêts :</strong> ${totalInterets.toLocaleString()} Ar</p>
        <p><strong>Total Assurance :</strong> ${totalAssurance.toLocaleString()} Ar</p>
        <p><strong>Total à payer :</strong> ${totalAPayer.toLocaleString()} Ar</p>
    `;
        }

        function calculerDateFin(dateDebut, dureeMois) {
            const date = new Date(dateDebut);
            date.setMonth(date.getMonth() + dureeMois);
            return date.toISOString().split('T')[0]; // "YYYY-MM-DD"
        }

        function sauvegarder() {
            // console.log(currentSimulation);
            // Étape 1 : Calcul de la date de fin
            const dateFin = calculerDateFin(currentSimulation.dateDebut, currentSimulation.dureeMois);

            // Étape 2 : Création du nouvel objet conforme à la base de données
            const simulationToSave = {
                id_type_pret: currentSimulation.id_type_pret,
                date_debut: currentSimulation.dateDebut,
                duree: currentSimulation.dureeMois,
                date_fin: dateFin,
                montant: currentSimulation.montant,
                id_type_remboursement: currentSimulation.id_type_remboursement || null,
                assurance: currentSimulation.totalAssurance || 0
            };
            console.log("etoo");
            console.log(simulationToSave);
            if (!currentSimulation) {
                alert('Aucune simulation à sauvegarder');
                return;
            }
            const data = 'simulation=' + encodeURIComponent(JSON.stringify(simulationToSave));

            ajax('POST', '/simulations/save', data, function(response) {
                if (response.success) {
                    showNotification('Simulation sauvegardée avec succès', 'success');
                    closeModal('save-simulation-modal');
                } else {
                    showNotification('Échec de la sauvegarde', 'error');
                }
            });
        }

        function loadSimulation(id) {
            const simulations = allsimulations;
            const simulation = simulations.find(s => s.id === id);

            if (!simulation) return;

            // Fill form with simulation data
            document.getElementById('sim-montant').value = simulation.montant;
            document.getElementById('sim-taux').value = simulation.tauxMensuel;
            document.getElementById('sim-duree').value = simulation.dureeMois;
            if (simulation.nomClient) {
                document.getElementById('sim-nom').value = simulation.nomClient;
            }
            if (simulation.dateDebut) {
                document.getElementById('sim-date').value = simulation.dateDebut.split('T')[0];
            }

            currentSimulation = simulation;
            displaySimulationResults(simulation);

            showNotification('Simulation chargée avec succès', 'success');
        }
        let allsimulations = null;

        function loadSavedSimulations() {
            ajax('GET', '/simulations/getAll', null, function(response) {
                if (response.success) {
                    const simulations = response.data;
                    allsimulations = simulations;
                    const container = document.getElementById('simulations-list');
                    if (!container) return;

                    if (simulations.length === 0) {
                        container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17,21 17,13 7,13 7,21"/>
                            <polyline points="7,3 7,8 15,8"/>
                        </svg>
                    </div>
                    <p>Aucune simulation sauvegardée</p>
                </div>
                `;
                        return;
                    }

                    container.innerHTML = simulations.map(sim => `
                <div class="simulation-card">
                    <div class="simulation-header">
                        <h4>${sim.nomClient || ''}</h4>
                        <div class="simulation-actions">
                            <button class="btn-sm btn-secondary" onclick="loadSimulation('${sim.id}')">Charger</button>
                            <button class="btn-sm btn-danger" onclick="deleteSimulation('${sim.id}')">Supprimer</button>
                            <input type="checkbox" class="simulation-checkbox" data-id="${sim.id}">
                        </div>
                    </div>
                    <div class="simulation-details">
                        <div class="simulation-stat">
                            <span>Montant:</span>
                            <span>${dataManager.formatCurrency(sim.montant)}</span>
                        </div>
                        <div class="simulation-stat">
                            <span>Durée:</span>
                            <span>${sim.dureeMois || sim.duree} mois</span>
                        </div>
                        <div class="simulation-stat">
                            <span>Montant du pret:</span>
                            <span>${dataManager.formatCurrency(sim.montant)}</span>
                        </div>
                    </div>
                    ${sim.description ? `<p class="simulation-description">${sim.description}</p>` : ''}
                    <div class="simulation-date">
                        Créée le ${dataManager.formatDate(sim.dateCreation || sim.date_debut)}
                    </div>
                </div>
            `).join('');

                } else {
                    showNotification('Échec du chargement des simulations', 'error');
                }
            });
        }
    </script>
</body>

</html>