<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande de Prêt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffff;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        h1 {
            color: #333;
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 1.1em;
            font-weight: 500;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 150px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #636e72, #2d3436);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 110, 114, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 110, 114, 0.4);
        }

        .btn-success {
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.4);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: none;
        }

        .alert-success {
            background: linear-gradient(45deg, rgba(0, 184, 148, 0.1), rgba(0, 206, 201, 0.1));
            color: #00b894;
            border: 2px solid rgba(0, 184, 148, 0.3);
        }

        .alert-error {
            background: linear-gradient(45deg, rgba(231, 76, 60, 0.1), rgba(192, 57, 43, 0.1));
            color: #e74c3c;
            border: 2px solid rgba(231, 76, 60, 0.3);
        }

        .alert-info {
            background: linear-gradient(45deg, rgba(52, 152, 219, 0.1), rgba(41, 128, 185, 0.1));
            color: #3498db;
            border: 2px solid rgba(52, 152, 219, 0.3);
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .info-card {
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .info-card p {
            color: #666;
            line-height: 1.6;
        }

        .required {
            color: #e74c3c;
            font-weight: bold;
        }

        .field-hint {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }

        .montant-display {
            font-size: 1.5em;
            font-weight: bold;
            color: #667eea;
            text-align: center;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    
    
    <div class="container">
        <div class="header">
            <h1>📋 Nouvelle Demande de Prêt</h1>
            <p class="subtitle">Remplissez le formulaire ci-dessous pour soumettre une demande de prêt</p>
        </div>

        <div class="info-card">
            <h3>ℹ️ Informations importantes</h3>
            <p>Cette demande sera soumise pour validation. Assurez-vous que toutes les informations sont correctes avant de soumettre.</p>
        </div>

        <div class="form-container">
            <div id="alertContainer"></div>
            
            <div class="loading" id="loadingIndicator">
                <div class="spinner"></div>
                <p>Chargement en cours...</p>
            </div>

            <form id="demandePretForm">
                <div class="form-group">
                    <label for="id_client">Client <span class="required">*</span></label>
                    <select id="id_client" name="id_client" required>
                        <option value="">-- Sélectionner un client --</option>
                    </select>
                    <div class="field-hint">Choisissez le client qui fait la demande de prêt</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_demande">Date de la demande <span class="required">*</span></label>
                        <input type="date" id="date_demande" name="date_demande" required>
                        <div class="field-hint">Date à laquelle la demande est effectuée</div>
                    </div>
                    <div class="form-group">
                        <label for="duree_demande">Durée demandée (en mois) <span class="required">*</span></label>
                        <input type="number" id="duree_demande" name="duree_demande" min="1" max="360" required>
                        <div class="field-hint">Durée de remboursement souhaitée (1 à 360 mois)</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="montant">Montant demandé (Ariary) <span class="required">*</span></label>
                    <input type="number" id="montant" name="montant" step="0.01" min="0" required>
                    <div class="field-hint">Montant du prêt demandé en ariary</div>
                    <div class="montant-display" id="montantDisplay" style="display: none;"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="assurance">Assurance (%) <span class="required"></span></label>
                        <input value="0" type="number" id="assurance" name="assurance" required>
                        <div class="field-hint">Assurance en %</div>
                    </div>
                    <div class="form-group">
                        <label for="delai">Delai de remboursement (en mois) <span class="required"></span></label>
                        <input value="0" type="number" id="delai" name="delai" min="0" max="360" required>
                        <div class="field-hint">Durée de delai du premier remboursement souhaitée (en mois)</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_debut">Date debut voulue <span class="required">*</span></label>
                        <input type="date" id="date_debut" name="date_debut" required>
                        <div class="field-hint">Date à laquelle le pret commencera</div>
                    </div>
                    <div class="form-group">
                    <label for="id_type_pret">Type de pret <span class="required">*</span></label>
                    <select id="id_type_pret" name="id_type_pret" required>
                        <option value="">-- Sélectionner un type de pret --</option>
                    </select>
                    <div class="field-hint">Choisissez le type de prêt</div>
                </div>
                </div>

                <div class="form-group">
                    <div class="field-hint">La demande sera mise en attente de validation une fois soumise</div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        🔄 Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        💾 Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const apiBase = "<?= API_URL ?>"; 

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

        chargerTypesPret();

        function chargerTypesPret() {
            ajax(
                "GET",
                "/type_prets",
                (data) => {
                    const select = document.getElementById("id_type_pret");
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

        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = message;
            alert.style.display = 'block';
            
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);
            
            if (type === 'success') {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            }
        }

        function showLoading(show = true) {
            const loading = document.getElementById('loadingIndicator');
            const form = document.getElementById('demandePretForm');
            
            if (show) {
                loading.style.display = 'block';
                form.style.display = 'none';
            } else {
                loading.style.display = 'none';
                form.style.display = 'block';
            }
        }

        function chargerClients() {
            ajax(
                "GET",
                "/clients/list",
                (data) => {
                    const select = document.getElementById("id_client");
                    select.innerHTML = '<option value="">-- Sélectionner un client --</option>';
                    data.forEach(client => {
                        const option = document.createElement("option");
                        option.value = client.id;
                        option.textContent = `${client.client_nom} ${client.client_prenom}`;
                        select.appendChild(option);
                    });
                },
                (status, response) => {
                    console.error(`Erreur ${status}: ${response}`);
                    showAlert('❌ Erreur lors du chargement des clients', 'error');
                    
                    // Données de test en cas d'erreur
                    const mockClients = [
                        {id: 1, nom: 'Dupont', prenom: 'Jean'},
                        {id: 2, nom: 'Martin', prenom: 'Sophie'},
                        {id: 3, nom: 'Dubois', prenom: 'Pierre'}
                    ];
                    
                    const select = document.getElementById("id_client");
                    select.innerHTML = '<option value="">-- Sélectionner un client --</option>';
                    mockClients.forEach(client => {
                        const option = document.createElement("option");
                        option.value = client.id;
                        option.textContent = `${client.nom} ${client.prenom}`;
                        select.appendChild(option);
                    });
                }
            );
        }

        
        // Soumettre la demande de prêt
        function soumettredemandePret(formData) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Envoi en cours...';
            
            ajax(
                "POST",
                "/demande_prets/create",
                (data) => {
                    showAlert('✅ Demande de prêt soumise avec succès!', 'success');
                    resetForm();
                    
                    // Redirection optionnelle après 2 secondes
                    setTimeout(() => {
                        if (confirm('Souhaitez-vous revenir à la liste des demandes?')) {
                            window.location.href = '<?= BASE_URL ?>/demandeNonValide';
                        }
                    }, 2000);
                },
                (status, response) => {
                    console.error(`Erreur ${status}: ${response}`);
                    showAlert('❌ Erreur lors de la soumission de la demande', 'error');
                },
                formData
            );
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }

        // Formatage du montant
        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'MGA',
                currencyDisplay: 'symbol' // ou 'code' si tu préfères afficher 'MGA'
            }).format(montant);
        }


        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('demandePretForm').reset();
            document.getElementById('montantDisplay').style.display = 'none';
            document.getElementById('alertContainer').innerHTML = '';
            
            // Définir la date du jour par défaut
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_demande').value = today;
        }

        // Gestion du formulaire
        document.getElementById('demandePretForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const urlEncodedData = new URLSearchParams(formData).toString();
            
            soumettredemandePret(urlEncodedData);
        });

        // Affichage dynamique du montant
        document.getElementById('montant').addEventListener('input', function(e) {
            const montant = parseFloat(e.target.value);
            const display = document.getElementById('montantDisplay');
            
            if (montant > 0) {
                display.textContent = formatMontant(montant);
                display.style.display = 'block';
            } else {
                display.style.display = 'none';
            }
        });

        // Validation de la durée
        document.getElementById('duree_demande').addEventListener('input', function(e) {
            const duree = parseInt(e.target.value);
            const hint = e.target.nextElementSibling;
            
            if (duree > 0) {
                const years = Math.floor(duree / 12);
                const months = duree % 12;
                let durationText = '';
                
                if (years > 0) {
                    durationText += `${years} an${years > 1 ? 's' : ''}`;
                }
                if (months > 0) {
                    if (durationText) durationText += ' et ';
                    durationText += `${months} mois`;
                }
                
                hint.textContent = `Durée: ${durationText}`;
            } else {
                hint.textContent = 'Durée de remboursement souhaitée (1 à 360 mois)';
            }
        });

        // Initialisation de la page
        document.addEventListener('DOMContentLoaded', function() {
            showLoading(true);
            
            // Définir la date du jour par défaut
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_demande').value = today;
            
            // Charger les données
            Promise.all([
                new Promise(resolve => {
                    chargerClients();
                    setTimeout(resolve, 100);
                }),
            ]).then(() => {
                showLoading(false);
            });
        });
    </script>
</body>
</html>