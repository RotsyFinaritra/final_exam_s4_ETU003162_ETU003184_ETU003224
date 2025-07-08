<!DOCTYPE html>
<html>

<head>
    <title>Demandes Non Validées</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 700px;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters label {
            margin-right: 10px;
        }

        .filters input,
        .filters select {
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <h1>Demandes Non Validées</h1>
    <div class="filters">
        <label>Date début: <input type="date" id="dateDebut"></label>
        <label>Date fin: <input type="date" id="dateFin"></label>
        <label>Statut:
            <select id="statut">
                <option value="">Tous</option>
                <option value="1">Non Validé</option>
                <option value="2">Validé</option>
                <option value="3">Remboursé</option>
            </select>
        </label>
        <button onclick="filtrerDemandes()">Filtrer</button>
        <button onclick="chargerDemandes()">Réinitialiser</button>
    </div>
    <div>
        <table id="tableDemandes">
            <thead>
                <tr>
                    <th>ID Client</th>
                    <th>Montant</th>
                    <th>Date Demande</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les lignes seront ajoutées ici -->
            </tbody>
        </table>
    </div>

    <script>
        const apiBase = "http://localhost:80/final_exam_s4/ws";

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
        });

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
            });
        }

        function chargerDemandes() {
            // Réinitialiser les filtres
            document.getElementById('dateDebut').value = '';
            document.getElementById('dateFin').value = '';
            document.getElementById('statut').value = '';
            
            ajax('GET', '/demandes/non-valide', null, function(data) {
                afficherDemandes(data);
            });
        }

        function afficherDemandes(data) {
            const tbody = document.querySelector('#tableDemandes tbody');
            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 4;
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
                    <button onclick='remplirFormulaire(${JSON.stringify(item)})'>Valider</button>
                    <button onclick='supprimerEtudiant(${item.id_client})'>Rejeter</button>
                ` : '<span>-</span>';
                
                tr.innerHTML = `
                    <td>${item.id_client}</td>
                    <td>${item.montant}</td>
                    <td>${item.date_demande}</td>
                    <td>
                        ${boutons}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function toUrlEncoded(obj) {
            return Object.keys(obj)
                .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(obj[k]))
                .join('&');
        }

        // Exemple de fonctions à définir
        function remplirFormulaire(item) {
            alert("Valider demande de client " + item.id_client);
            const dataEncoded = toUrlEncoded(item);
            ajax("POST", "/demandes", dataEncoded, () => {
                console.log(item);
                chargerDemandes();
            });
        }

        function supprimerEtudiant(id_client) {
            alert("Rejeter demande de client " + id_client);
            // Code pour rejeter la demande
        }
    </script>

</body>
</html>
