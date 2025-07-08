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
            <h3>Solde en Cours</h3>
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

        function chargerCapitalEnCours() {
            ajax("GET", "/capitalSolde", null, (data) => {
                const soldeElement = document.getElementById("stat-total-montant");
                if (data && data.montant !== undefined) {
                    soldeElement.textContent = parseFloat(data.montant).toFixed(2);
                } else {
                    soldeElement.textContent = "0.00";
                }
            });
        }

        function chargerMouvements() {
            ajax("GET", "/mouvements", null, (data) => {
                const tbody = document.querySelector("#table-capital");
                tbody.innerHTML = "";
                let montants = [];

                data.forEach(e => {
                    montants.push(parseFloat(e.montant));
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${e.id}</td>
                        <td>${e.date_mouvement}</td>
                        <td>${e.type_mouvement}</td>
                        <td>${e.montant}</td>
                        <td>
                            <button class="btn-danger" onclick='supprimerMouvement(${e.id})'>Supprimer</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                document.getElementById("stat-total").textContent = data.length;
                if (montants.length > 0) {
                    document.getElementById("stat-min").textContent = Math.min(...montants).toFixed(2);
                    document.getElementById("stat-max").textContent = Math.max(...montants).toFixed(2);
                }
                
                // Charger le capital en cours après avoir chargé les mouvements
                chargerCapitalEnCours();
            });
        }


        function ajouterOuModifier() {
    const id = document.getElementById("id").value;
    const date_mouvement = document.getElementById("date_mouvement").value;
    const montant = document.getElementById("montant").value;
    const id_type_mvt = document.getElementById("id_type_mvt").value;
    const data = `date_mouvement=${encodeURIComponent(date_mouvement)}&id_type_mvt=${id_type_mvt}&montant=${montant}`;
    
    if (id) {
        ajax("PUT", `/mouvements/${id}`, data, (response) => {
            if (response.success) {
                resetForm();
                chargerMouvements();
                alert("Mouvement modifié !");
            } else {
                showErrorPopup(response.error || "Erreur lors de la modification");
            }
        });
    } else {
        ajax("POST", "/mouvements", data, (response) => {
            if (response.success) {
                resetForm();
                chargerMouvements();
                alert("Mouvement ajouté !");
            } else {
                showErrorPopup(response.error || "Erreur lors de l'ajout");
            }
        });
    }
}

        function remplirFormulaire(e) {
            document.getElementById("id").value = e.id;
            document.getElementById("date_mouvement").value = e.date_mouvement;
            document.getElementById("montant").value = e.montant;
            document.getElementById("id_type_mvt").value = e.id_type_mvt || "";
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
        }

        document.addEventListener("DOMContentLoaded", () => {
            chargerTypesMouvement();
            chargerMouvements();
        });


        
function showErrorPopup(message) {
    // Créer le popup d'erreur
    const popup = document.createElement('div');
    popup.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        border: 2px solid #dc3545;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-width: 400px;
        text-align: center;
    `;
    
    popup.innerHTML = `
        <div style="color: #dc3545; font-weight: bold; margin-bottom: 15px;">
            ⚠️ Erreur
        </div>
        <div style="margin-bottom: 20px; color: #333;">
            ${message}
        </div>
        <button onclick="closeErrorPopup()" style="
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        ">
            Fermer
        </button>
    `;
    
    // Créer l'overlay
    const overlay = document.createElement('div');
    overlay.id = 'error-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    `;
    
    document.body.appendChild(overlay);
    document.body.appendChild(popup);
    
    // Fermer en cliquant sur l'overlay
    overlay.onclick = closeErrorPopup;
    
    // Stocker la référence du popup
    window.currentErrorPopup = popup;
}

function closeErrorPopup() {
    const popup = window.currentErrorPopup;
    const overlay = document.getElementById('error-overlay');
    
    if (popup) {
        document.body.removeChild(popup);
        window.currentErrorPopup = null;
    }
    
    if (overlay) {
        document.body.removeChild(overlay);
    }
}

    </script>
</body>
</html>