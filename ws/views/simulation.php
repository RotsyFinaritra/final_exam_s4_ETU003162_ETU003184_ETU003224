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
    <?php include"navbar.php"?>


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
                                    Montant du Prêt (€) *
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
                            <span id="result-mensualite" class="result-value primary">0 €</span>
                        </div>
                        <div class="result-item">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20m8-10H4" />
                                </svg>
                                Total à Payer
                            </div>
                            <span id="result-total" class="result-value">0 €</span>
                        </div>
                        <div class="result-item">
                            <div class="result-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
                                </svg>
                                Total Intérêts
                            </div>
                            <span id="result-interets" class="result-value">0 €</span>
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
                        <button class="btn-secondary" onclick="showModal('save-simulation-modal')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17,21 17,13 7,13 7,21" />
                                <polyline points="7,3 7,8 15,8" />
                            </svg>
                            Sauvegarder
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
                            <span id="summary-capital" class="summary-value">0 €</span>
                        </div>
                        <div class="summary-item">
                            <span>Total Intérêts:</span>
                            <span id="summary-interets" class="summary-value">0 €</span>
                        </div>
                        <div class="summary-item">
                            <span>Total Général:</span>
                            <span id="summary-total" class="summary-value primary">0 €</span>
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
            <form id="save-simulation-form">
                <div class="form-group">
                    <label for="save-nom">Nom de la Simulation *</label>
                    <input type="text" id="save-nom" name="nom" required placeholder="Ex: Prêt Personnel - M. Dupont">
                </div>
                <div class="form-group">
                    <label for="save-description">Description</label>
                    <textarea id="save-description" name="description" rows="3" placeholder="Description optionnelle de la simulation..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('save-simulation-modal')">Annuler</button>
                    <button type="submit" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17,21 17,13 7,13 7,21" />
                            <polyline points="7,3 7,8 15,8" />
                        </svg>
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= STATIC_URL?>/js/data.js"></script>
    <!-- <script src="<?= STATIC_URL ?>/js/simulation.js"></script> -->
    <script src="<?= STATIC_URL?>/js/simulation.js"></script>

    <script>
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
            xhr.open('POST', '<?=API_URL?>/export-pdf', true);
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
        });

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
    </script>
</body>

</html>