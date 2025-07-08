<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de Prêt - BankLoan Pro</title>
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/main.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/components.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/forms.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/tables.css">
</head>
<body>
    <?php include"navbar.php"?>

    <main class="main-content">
        <div class="section-header">
            <div>
                <h2>Demandes de Prêt</h2>
                <p>Gérez les demandes de prêt des clients</p>
            </div>
            <a href="<?= BASE_URL ?>/demande_form_view"><button class="btn-primary" >
                Nouvelle Demande
            </button></a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Total Demandes</h3>
                    <p class="stat-value" id="total-demandes">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>En Attente</h3>
                    <p class="stat-value" id="demandes-attente">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Approuvées</h3>
                    <p class="stat-value" id="demandes-approuvees">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Refusées</h3>
                    <p class="stat-value" id="demandes-refusees">0</p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Liste des Demandes</h3>
                <div class="table-filters">
                    <label>Date début: <input type="date" id="dateDebut" class="search-input"></label>
                    <label>Date fin: <input type="date" id="dateFin" class="search-input"></label>
                    <select id="statut" class="filter-select">
                        <option value="">-- Tous --</option>
                    </select>
                    <button class="btn-primary" onclick="filtrerDemandes()">Filtrer</button>
                    <button class="btn-secondary" onclick="chargerDemandes()">Réinitialiser</button>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Client</th>
                        <th>Montant</th>
                        <th>Durée</th>
                        <th>Date Demande</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="demandes-table">
                    <!-- Dynamically populated -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const apiBase = "<?= API_URL ?>";

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

        document.addEventListener('DOMContentLoaded', function() {
            chargerDemandes();
            chargerStatistiques();
            chargerStatutsDemande();
        });

        function chargerStatutsDemande() {
            ajax(
                "GET",
                "/statut_demandes",
                null, // il faut mettre null ici pour le 3e paramètre "data"
                (data) => {
                    const select = document.getElementById("statut");
                    select.innerHTML = '<option value="">-- Tous --</option>';
                    data.forEach(statut => {
                        const option = document.createElement("option");
                        option.value = statut.id;
                        option.textContent = statut.nom;
                        select.appendChild(option);
                    });
                }
            );
        }

        function filtrerDemandes() {
            const dateDebut = document.getElementById('dateDebut').value;
            const dateFin = document.getElementById('dateFin').value;
            const statut = document.getElementById('statut').value;

            // Construire les paramètres pour l'URL
            const params = [];
            if (dateDebut) params.push(`date_debut=${encodeURIComponent(dateDebut)}`);
            if (dateFin) params.push(`date_fin=${encodeURIComponent(dateFin)}`);
            if (statut) params.push(`statut=${encodeURIComponent(statut)}`);

            const query = params.length > 0 ? '?' + params.join('&') : '';

            ajax('GET', '/demandes/filtrer' + query, null, function(data) {
                afficherDemandes(data);
                mettreAJourStatistiques(data);
            });
        }

        function chargerDemandes() {
            // Réinitialiser les filtres
            document.getElementById('dateDebut').value = '';
            document.getElementById('dateFin').value = '';
            document.getElementById('statut').value = '';
            
            ajax('GET', '/demandes/filtrer', null, function(data) {
                afficherDemandes(data);
                mettreAJourStatistiques(data);
            });

            chargerStatutsDemande();
        }

        function chargerStatistiques() {
            ajax('GET', '/demandes/filtrer', null, function(data) {
                mettreAJourStatistiques(data);
            });
        }

        function afficherDemandes(data) {
            const tbody = document.getElementById('demandes-table');
            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 6;
                td.textContent = "Aucune demande";
                td.style.textAlign = "center";
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }

            // Récupérer la valeur du statut sélectionné
            const statutSelectionne = document.getElementById('statut').value;
            
            data.forEach(item => {
                const tr = document.createElement("tr");
                
                // Afficher les boutons seulement si le statut est "Non Validé" (1) ou "Tous" ("")
                // ET si l'item a effectivement le statut "Non Validé" (1)
                const afficherBoutons = (statutSelectionne === "" || statutSelectionne === "1") && item.id_statut_demande == 1;
                
                const boutons = afficherBoutons ? `
                    <button class="btn-primary" onclick='remplirFormulaire(${JSON.stringify(item)})'>Valider</button>
                    <button class="btn-secondary" onclick='supprimerEtudiant(${JSON.stringify(item)})'>Rejeter</button>
                ` : '<span>-</span>';

                // Convertir le statut en texte
                const statutTexte = getStatutTexte(item.id_statut_demande);
                
                tr.innerHTML = `
                    <td>${item.id_client}</td>
                    <td>${item.montant}</td>
                    <td>${item.duree_demande} mois</td>
                    <td>${item.date_demande}</td>
                    <td><span class="status status-${getStatutClass(item.id_statut_demande)}">${statutTexte}</span></td>
                    <td>
                        ${boutons}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function mettreAJourStatistiques(data) {
            if (!data || data.length === 0) {
                document.getElementById('total-demandes').textContent = '0';
                document.getElementById('demandes-attente').textContent = '0';
                document.getElementById('demandes-approuvees').textContent = '0';
                document.getElementById('demandes-refusees').textContent = '0';
                return;
            }

            const total = data.length;
            const enAttente = data.filter(item => item.id_statut_demande == 1).length;
            const approuvees = data.filter(item => item.id_statut_demande == 2).length;
            const refusees = data.filter(item => item.id_statut_demande == 3).length;

            document.getElementById('total-demandes').textContent = total;
            document.getElementById('demandes-attente').textContent = enAttente;
            document.getElementById('demandes-approuvees').textContent = approuvees;
            document.getElementById('demandes-refusees').textContent = refusees;
        }

        function getStatutTexte(idStatut) {
            switch(idStatut) {
                case 1: return 'En Attente';
                case 2: return 'Approuvé';
                case 3: return 'Reffusé';
                default: return 'Inconnu';
            }
        }

        function getStatutClass(idStatut) {
            switch(idStatut) {
                case 1: return 'pending';
                case 2: return 'approved';
                case 3: return 'completed';
                default: return 'unknown';
            }
        }

        function toUrlEncoded(obj) {
            return Object.keys(obj)
                .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(obj[k]))
                .join('&');
        }

        // Fonctions pour les actions
        function remplirFormulaire(item) {
            if(confirm(`Voulez-vous vraiment valider la demande du client ${item.id_client} ?`)) {
                const dataEncoded = toUrlEncoded(item);
                ajax("POST", "/demandes", dataEncoded, () => {
                    console.log('Demande validée:', item);
                    chargerDemandes();
                });
            }
        }

        function supprimerEtudiant(item) {
            if(confirm(`Voulez-vous vraiment rejeter la demande du client ${item.id_client} ?`)) {
                const dataEncoded = toUrlEncoded(item);
                ajax("POST", "/demandes/rejeter", dataEncoded, () => {
                    console.log('Demande validée:', item);
                    chargerDemandes();
                });
            }
}
        function showModal(modalId) {
            // Fonction pour afficher la modal (à implémenter selon votre modal)
            alert("Fonctionnalité nouvelle demande à implémenter");
        }
    </script>
</body>
</html>