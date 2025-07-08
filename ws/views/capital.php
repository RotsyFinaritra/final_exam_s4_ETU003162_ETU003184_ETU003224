<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mouvements de Capital - BankLoan Pro</title>
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
                <h2>Mouvements de Capital</h2>
                <p>Suivi des mouvements financiers liés au capital</p>
            </div>
        </div>

        <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 3h18v18H3z"/>
                <path d="M9 3v18"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Mouvements</h3>
            <p class="stat-value" id="stat-total">0</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Montant Total</h3>
            <p class="stat-value" id="stat-total-montant">0</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
                <polyline points="17 18 23 18 23 12"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Montant Min</h3>
            <p class="stat-value" id="stat-min">0</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                <polyline points="17 6 23 6 23 12"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Montant Max</h3>
            <p class="stat-value" id="stat-max">0</p>
        </div>
    </div>
</div>

        <div class="table-container">
            <div class="form-container" style="margin-bottom: 20px;">
                <h4>Ajouter / Modifier un Mouvement</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="hidden" id="id">
                    <input type="date" id="date_mouvement" class="form-input" style="flex: 1;">
                    <input type="number" step="0.01" id="montant" placeholder="Montant" class="form-input" style="flex: 1;">
                    <select id="id_type_mvt" class="form-input" style="flex: 1;"></select>
                    <input type="number" id="id_capital" placeholder="ID Capital" class="form-input" style="flex: 1;">
                    <button class="btn-primary" onclick="ajouterOuModifier()">Ajouter / Modifier</button>
                    <button class="btn-secondary" onclick="resetForm()">Annuler</button>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Type de mouvement</th>
                        <th>Montant</th>
                        <th>ID Capital</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-capital"></tbody>
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

        function chargerTypesMouvement() {
            ajax("GET", "/types-mouvement", null, (data) => {
                const select = document.getElementById("id_type_mvt");
                select.innerHTML = "";
                data.forEach(type => {
                    const option = document.createElement("option");
                    option.value = type.id;
                    option.textContent = type.nom;
                    select.appendChild(option);
                });
            });
        }

        function chargerMouvements() {
            ajax("GET", "/mouvements", null, (data) => {
                const tbody = document.querySelector("#table-capital");
                tbody.innerHTML = "";

                let montantDepot = 0;
                let montantInteret = 0;
                let montantRetrait = 0;
                let montants = [];

                data.forEach(e => {
                    const montant = parseFloat(e.montant);
                    montants.push(montant);

                    // Calcul du montant total en fonction du type
                    switch (parseInt(e.id_type_mouvement)) {
                        case 1:
                            montantDepot += montant;
                            break;
                        case 2:
                            montantInteret += montant;
                            break;
                        case 3:
                            montantRetrait += montant;
                            break;
                    }

                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${e.id}</td>
                        <td>${e.date_mouvement}</td>
                        <td>${e.type_mouvement}</td>
                        <td>${montant.toFixed(2)}</td>
                        <td>${e.id_capital}</td>
                        <td>
                            <button class="btn-primary" onclick='remplirFormulaire(${JSON.stringify(e)})'>Modifier</button>
                            <button class="btn-danger" onclick='supprimerMouvement(${e.id})'>Supprimer</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Mise à jour des statistiques
                document.getElementById("stat-total").textContent = data.length;

                const montantTotal = montantDepot + montantInteret - montantRetrait;
                document.getElementById("stat-total-montant").textContent = montantTotal.toFixed(2);

                if (montants.length > 0) {
                    document.getElementById("stat-min").textContent = Math.min(...montants).toFixed(2);
                    document.getElementById("stat-max").textContent = Math.max(...montants).toFixed(2);
                }
            });
        }


        function ajouterOuModifier() {
            const id = document.getElementById("id").value;
            const date_mouvement = document.getElementById("date_mouvement").value;
            const montant = document.getElementById("montant").value;
            const id_type_mvt = document.getElementById("id_type_mvt").value;
            const id_capital = document.getElementById("id_capital").value;

            const data = `date_mouvement=${encodeURIComponent(date_mouvement)}&id_type_mvt=${id_type_mvt}&montant=${montant}&id_capital=${id_capital}`;

            if (id) {
                ajax("PUT", `/mouvements/${id}`, data, () => {
                    resetForm();
                    chargerMouvements();
                    alert("Mouvement modifié !");
                });
            } else {
                ajax("POST", "/mouvements", data, () => {
                    resetForm();
                    chargerMouvements();
                    alert("Mouvement ajouté !");
                });
            }
        }

        function remplirFormulaire(e) {
            document.getElementById("id").value = e.id;
            document.getElementById("date_mouvement").value = e.date_mouvement;
            document.getElementById("montant").value = e.montant;
            document.getElementById("id_type_mvt").value = e.id_type_mvt || "";
            document.getElementById("id_capital").value = e.id_capital;
        }

        function supprimerMouvement(id) {
            if (confirm("Supprimer ce mouvement ?")) {
                ajax("DELETE", `/mouvements/${id}`, null, () => {
                    chargerMouvements();
                    alert("Mouvement supprimé !");
                });
            }
        }

        function resetForm() {
            document.getElementById("id").value = "";
            document.getElementById("date_mouvement").value = "";
            document.getElementById("montant").value = "";
            document.getElementById("id_type_mvt").value = "";
            document.getElementById("id_capital").value = "";
        }

        document.addEventListener("DOMContentLoaded", () => {
            chargerTypesMouvement();
            chargerMouvements();
        });
    </script>
</body>
</html>
