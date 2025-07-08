<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types de Prêt - BankLoan Pro</title>
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
                <h2>Types de Prêt</h2>
                <p>Gérez les différents types de prêt disponibles</p>
            </div>
            <button class="btn-primary" onclick="showModal('typepret-modal')">
                Nouveau Type de Prêt
            </button>
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
                    <h3>Total Types</h3>
                    <p class="stat-value" id="total-types">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Taux Moyen</h3>
                    <p class="stat-value" id="taux-moyen">0%</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Taux Min</h3>
                    <p class="stat-value" id="taux-min">0%</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>Taux Max</h3>
                    <p class="stat-value" id="taux-max">0%</p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Liste des Types de Prêt</h3>
                <div class="table-filters">
          
                </div>
            </div>
            <div class="form-container" style="margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h4>Ajouter / Modifier un Type de Prêt</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="hidden" id="id">
                    <input type="text" id="nom" placeholder="Nom du type de prêt" class="form-input" style="flex: 1;">
                    <input type="number" id="taux" placeholder="Taux (%)" step="0.01" class="form-input" style="flex: 1;">
                    <button class="btn-primary" onclick="ajouterOuModifier()">Ajouter / Modifier</button>
                    <button class="btn-secondary" onclick="resetForm()">Annuler</button>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Taux (%)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="typepret-table">
                    <!-- Dynamically populated -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const apiBase = "<?= BASE_URL ?>";

        function ajax(method, url, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, apiBase + url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    callback(JSON.parse(xhr.responseText));
                }
            };
            xhr.send(data);
        }

        document.addEventListener('DOMContentLoaded', function() {
            chargerTypePrets();
        });

        function chargerTypePrets() {
            ajax("GET", "/typeprets", null, (data) => {
                afficherTypePrets(data);
                mettreAJourStats(data);
            });
        }

        function afficherTypePrets(data) {
            const tbody = document.querySelector("#typepret-table");
            tbody.innerHTML = "";
            
            if (!data || data.length === 0) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 3;
                td.textContent = "Aucun type de prêt";
                td.style.textAlign = "center";
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }

            data.forEach(e => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${e.nom}</td>
                    <td>${e.taux}%</td>
                    <td>
                        <button class="btn-primary" onclick='remplirFormulaire(${JSON.stringify(e)})'>Modifier</button>
                        <button class="btn-danger" onclick='supprimer(${e.id})'>Supprimer</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function mettreAJourStats(data) {
            const totalTypes = data.length;
            document.getElementById('total-types').textContent = totalTypes;
            
            if (totalTypes > 0) {
                const taux = data.map(item => parseFloat(item.taux));
                const tauxMoyen = (taux.reduce((a, b) => a + b, 0) / totalTypes).toFixed(2);
                const tauxMin = Math.min(...taux).toFixed(2);
                const tauxMax = Math.max(...taux).toFixed(2);
                
                document.getElementById('taux-moyen').textContent = tauxMoyen + '%';
                document.getElementById('taux-min').textContent = tauxMin + '%';
                document.getElementById('taux-max').textContent = tauxMax + '%';
            } else {
                document.getElementById('taux-moyen').textContent = '0%';
                document.getElementById('taux-min').textContent = '0%';
                document.getElementById('taux-max').textContent = '0%';
            }
        }

        function filtrerTypes() {
            const searchTerm = document.getElementById('search-typepret').value.toLowerCase();
            const tauxMin = parseFloat(document.getElementById('filter-taux-min').value) || 0;
            const tauxMax = parseFloat(document.getElementById('filter-taux-max').value) || Infinity;

            ajax("GET", "/typeprets", null, (data) => {
                const filteredData = data.filter(item => {
                    const nomMatch = item.nom.toLowerCase().includes(searchTerm);
                    const tauxMatch = parseFloat(item.taux) >= tauxMin && parseFloat(item.taux) <= tauxMax;
                    return nomMatch && tauxMatch;
                });
                afficherTypePrets(filteredData);
            });
        }

        function resetFilters() {
            document.getElementById('search-typepret').value = '';
            document.getElementById('filter-taux-min').value = '';
            document.getElementById('filter-taux-max').value = '';
            chargerTypePrets();
        }

        function ajouterOuModifier() {
            const id = document.getElementById("id").value;
            const nom = document.getElementById("nom").value;
            const taux = document.getElementById("taux").value;
            
            if (!nom || !taux) {
                alert("Veuillez remplir tous les champs");
                return;
            }

            const data = `nom=${encodeURIComponent(nom)}&taux=${taux}`;

            if (id) {
                ajax("PUT", `/typeprets/${id}`, data, () => {
                    resetForm();
                    chargerTypePrets();
                    alert("Type de prêt modifié avec succès");
                });
            } else {
                ajax("POST", "/typeprets", data, () => {
                    resetForm();
                    chargerTypePrets();
                    alert("Type de prêt ajouté avec succès");
                });
            }
        }

        function remplirFormulaire(e) {
            document.getElementById("id").value = e.id;
            document.getElementById("nom").value = e.nom;
            document.getElementById("taux").value = e.taux;
            
            // Scroll vers le formulaire
            document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });
        }

        function supprimer(id) {
            if (confirm("Supprimer ce type de prêt ?")) {
                ajax("DELETE", `/typeprets/${id}`, null, () => {
                    chargerTypePrets();
                    alert("Type de prêt supprimé avec succès");
                });
            }
        }

        function resetForm() {
            document.getElementById("id").value = "";
            document.getElementById("nom").value = "";
            document.getElementById("taux").value = "";
        }

        // Fonction pour afficher les modales (si nécessaire)
        function showModal(modalId) {
            // Code pour afficher la modale
            console.log("Afficher modal:", modalId);
        }
    </script>
</body>
</html>