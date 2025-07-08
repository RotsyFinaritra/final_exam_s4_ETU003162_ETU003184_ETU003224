function ajax(method, url, callback, errorCallback, data = null) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, apiBase + url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                callback(JSON.parse(xhr.responseText));
            } else {
                errorCallback(xhr.status, xhr.responseText);
            }
        }
    };
    xhr.send(data);
}

function chargerPrets() {
    ajax(
        "GET",
        "/prets/list",
        (data) => {
            console.log(";lkjhgf");
            const tbody = document.querySelector("#table-prets tbody");
            tbody.innerHTML = "";
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7">Aucun prêt disponible.</td></tr>';
            } else {
                data.forEach(pret => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${pret.id}</td>
                        <td>${pret.type_pret}</td>
                        <td>${pret.client_nom} ${pret.client_prenom}</td>
                        <td>${pret.date_debut}</td>
                        <td>${pret.duree}</td>
                        <td>${pret.date_fin}</td>
                        <td>${pret.montant}</td>
                        <td>${pret.statut_pret}</td>
                        <td><button><a href='exporter?id=${pret.id}'>Exporter PDF</a></button></td>
                        
                    `;
                    tbody.appendChild(tr);
                });
            }
        },
        (status, response) => {
            console.error(`Erreur ${status}: ${response}`);
            const tbody = document.querySelector("#table-prets tbody");
            tbody.innerHTML = '<tr><td colspan="7">Erreur lors du chargement des prêts.</td></tr>';
        }
    );
}

function chargerTypesPret() {
    ajax(
        "GET",
        "/type_prets",
        (data) => {
            const select = document.getElementById("filtreTypePret");
            select.innerHTML = '<option value="">-- Tous --</option>';
            data.forEach(type => {
                const option = document.createElement("option");
                option.value = type.id;
                option.textContent = type.nom;
                select.appendChild(option);
            });
        },
        (status, response) => {
            console.error(`Erreur ${status}: ${response}`);
        }
    );
}

function chargerClients() {
    ajax(
        "GET",
        "/clients/list",
        (data) => {
            const select = document.getElementById("filtreClient");
            select.innerHTML = '<option value="">-- Tous --</option>';
            data.forEach(client => {
                const option = document.createElement("option");
                option.value = client.id;
                option.textContent = `${client.client_nom} ${client.client_prenom}`;
                select.appendChild(option);
            });
        },
        (status, response) => {
            console.error(`Erreur ${status}: ${response}`);
        }
    );
}

function filtrerPrets() {
    const criteria = {
        id_type_pret: document.getElementById('filtreTypePret').value,
        id_client: document.getElementById('filtreClient').value,
        date_debut: document.getElementById('filtreDateDebut').value,
        date_fin: document.getElementById('filtreDateFin').value
    };

    const query = Object.keys(criteria)
        .filter(key => criteria[key])
        .map(key => `${key}=${encodeURIComponent(criteria[key])}`)
        .join('&');

    ajax(
        "GET",
        `/pret/filter${query ? '?' + query : ''}`,
        (data) => {
            const tbody = document.querySelector("#table-prets tbody");
            tbody.innerHTML = "";
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7">Aucun prêt trouvé.</td></tr>';
            } else {
                data.forEach(pret => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${pret.id}</td>
                        <td>${pret.type_pret}</td>
                        <td>${pret.client_nom} ${pret.client_prenom}</td>
                        <td>${pret.date_debut}</td>
                        <td>${pret.duree}</td>
                        <td>${pret.date_fin}</td>
                        <td>${pret.montant}</td>
                        <td>${pret.statut_pret}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        },
        (status, response, url) => {
            console.error(`Erreur ${status} pour l'URL ${url}: ${response}`);
            const tbody = document.querySelector("#table-prets tbody");
            tbody.innerHTML = '<tr><td colspan="7">Erreur lors du filtrage.</td></tr>';
        }
    );
}

function resetFiltre() {
    document.getElementById('filtreTypePret').value = '';
    document.getElementById('filtreClient').value = '';
    document.getElementById('filtreDateDebut').value = '';
    document.getElementById('filtreDateFin').value = '';
    chargerPrets();
}