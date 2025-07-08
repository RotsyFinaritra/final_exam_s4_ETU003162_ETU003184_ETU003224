// Simulation de Prêt Script
document.addEventListener('DOMContentLoaded', function () {
    loadTypesForSimulation();
    setupSimulationForm();
    setupSaveSimulationForm();
    loadSavedSimulations();
    setDefaultDate();
});

let currentSimulation = null;

function setDefaultDate() {
    const dateInput = document.getElementById('sim-date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }
}

// function loadTypesForSimulation() {
//     const types = dataManager.getTypesPret().filter(t => t.statut === 'actif');
//     const select = document.getElementById('sim-type');

//     if (select) {
//         select.innerHTML = '<option value="">Sélectionner un type</option>';
//         types.forEach(type => {
//             const option = document.createElement('option');
//             option.value = type.id;
//             option.textContent = `${type.nom} (${dataManager.formatPercent(type.tauxInteretMensuel)})`;
//             option.dataset.taux = type.tauxInteretMensuel;
//             option.dataset.montantMin = type.montantMin;
//             option.dataset.montantMax = type.montantMax;
//             option.dataset.dureeMax = type.dureeMaxMois;
//             select.appendChild(option);
//         });
//     }
// }





function setupSimulationForm() {
    const form = document.getElementById('simulation-form');
    if (!form) return;

    // Real-time validation
    const inputs = form.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        input.addEventListener('input', validateSimulationInputs);
    });
}

function validateSimulationInputs() {
    const typeSelect = document.getElementById('sim-type');
    const montantInput = document.getElementById('sim-montant');
    const dureeInput = document.getElementById('sim-duree');

    if (!typeSelect?.value) return;

    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    const montantMin = parseFloat(selectedOption.dataset.montantMin);
    const montantMax = parseFloat(selectedOption.dataset.montantMax);
    const dureeMax = parseInt(selectedOption.dataset.dureeMax);

    const montant = parseFloat(montantInput?.value);
    const duree = parseInt(dureeInput?.value);

    // Validate montant
    if (montantInput && montant) {
        if (montant < montantMin || montant > montantMax) {
            montantInput.setCustomValidity(`Le montant doit être entre ${dataManager.formatCurrency(montantMin)} et ${dataManager.formatCurrency(montantMax)}`);
        } else {
            montantInput.setCustomValidity('');
        }
    }

    // Validate duree
    if (dureeInput && duree) {
        if (duree > dureeMax) {
            dureeInput.setCustomValidity(`La durée maximale est de ${dureeMax} mois`);
        } else {
            dureeInput.setCustomValidity('');
        }
    }
}

function calculerSimulation() {
    const montant = parseFloat(document.getElementById('sim-montant')?.value);
    const taux = parseFloat(document.getElementById('sim-taux')?.value) / 100; // Convert to decimal
    const duree = parseInt(document.getElementById('sim-duree')?.value);
    const dateDebut = document.getElementById('sim-date')?.value;
    const nomClient = document.getElementById('sim-nom')?.value;
    const assuranceAnnuelPourcent = parseFloat(document.getElementById('sim-assurance')?.value) || 0;
    const assuranceMensuelle = (montant * (assuranceAnnuelPourcent / 100)) / 12;  // assurance en Ar/mois

    // Validation
    if (!montant || !taux || !duree) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }

    if (montant <= 0 || taux < 0 || duree <= 0) {
        showNotification('Veuillez entrer des valeurs valides', 'error');
        return;
    }

    const tauxMensuel = taux;
    let mensualite = (montant * (tauxMensuel * Math.pow(1 + tauxMensuel, duree))) /
        (Math.pow(1 + tauxMensuel, duree) - 1);

    // const totalAPayer = mensualite * duree;
    // const totalInterets = totalAPayer - montant;
    
    const mensualiteSansAssurance = (montant * (taux * Math.pow(1 + taux, duree))) /
    (Math.pow(1 + taux, duree) - 1);
    mensualite =mensualiteSansAssurance
    const mensualiteAvecAssurance = mensualiteSansAssurance + assuranceMensuelle;
    
    const totalAPayer = mensualiteAvecAssurance * duree;
    const totalInterets = (mensualiteSansAssurance * duree) - montant;
    const tauxTotal = (totalInterets / montant) * 100;
    const totalAssurance = assuranceMensuelle * duree;
    const totalGlobal = totalAPayer;

    const tableauAmortissement = generateAmortizationTable(montant, tauxMensuel, duree, dateDebut, assuranceMensuelle);

    currentSimulation = {
        montant,
        tauxMensuel: taux,
        dureeMois: duree,
        mensualite,
        totalInterets,
        totalAPayer,
        tauxTotal,
        tableauAmortissement,
        nomClient,
        dateDebut,
        totalAssurance,
        totalGlobal
    };

    // Display results
    displaySimulationResults(currentSimulation);
}

function generateAmortizationTable(montant, tauxMensuel, duree, dateDebut, assuranceMensuelle) {
    const tableau = [];
    let soldeRestant = montant;
    let currentDate = dateDebut ? new Date(dateDebut) : new Date();

    const mensualiteBase = (montant * (tauxMensuel * Math.pow(1 + tauxMensuel, duree))) /
        (Math.pow(1 + tauxMensuel, duree) - 1);

    for (let i = 1; i <= duree; i++) {
        const interetsMois = soldeRestant * tauxMensuel;
        const capitalMois = mensualiteBase - interetsMois;
        soldeRestant = Math.max(0, soldeRestant - capitalMois);

        const mensualiteTotale = mensualiteBase + assuranceMensuelle;

        const echeanceDate = new Date(currentDate);
        echeanceDate.setMonth(currentDate.getMonth() + i);

        tableau.push({
            numero: i,
            date: echeanceDate,
            montantCapital: capitalMois,
            montantInteret: interetsMois,
            assurance: assuranceMensuelle,
            mensualite: mensualiteTotale,
            soldeRestant: soldeRestant,
            pourcentageRembourse: ((montant - soldeRestant) / montant) * 100
        });
    }

    return tableau;
}

function displaySimulationResults(simulation) {
    // Show results section
    const resultsSection = document.getElementById('simulation-results');
    const amortissementSection = document.getElementById('amortissement-container');

    if (resultsSection) {
        resultsSection.style.display = 'block';

        document.getElementById('result-mensualite').textContent =
            dataManager.formatCurrency(simulation.mensualite);
        document.getElementById('result-total').textContent =
            dataManager.formatCurrency(simulation.totalAPayer);
        document.getElementById('result-interets').textContent =
            dataManager.formatCurrency(simulation.totalInterets);
        document.getElementById('result-taux-total').textContent =
            simulation.tauxTotal.toFixed(2) + '%';
    }

    if (amortissementSection) {
        amortissementSection.style.display = 'block';
        displayAmortizationTable(simulation.tableauAmortissement);
        updateAmortizationSummary(simulation);
    }

    // Scroll to results
    resultsSection?.scrollIntoView({ behavior: 'smooth' });
}

function displayAmortizationTable(tableau) {
    const tableBody = document.getElementById('amortissement-table');
    if (!tableBody) return;

    const viewMode = document.getElementById('view-mode')?.value || 'all';
    let displayTableau = tableau;

    switch (viewMode) {
        case 'yearly':
            displayTableau = tableau.filter((_, index) => (index + 1) % 12 === 0 || index === tableau.length - 1);
            break;
        case 'first-year':
            displayTableau = tableau.slice(0, 12);
            break;
        default:
            displayTableau = tableau;
    }

    tableBody.innerHTML = displayTableau.map(echeance => `
        <tr>
            <td><strong>${echeance.numero}</strong></td>
            <td>${dataManager.formatDate(echeance.date)}</td>
            <td>${dataManager.formatCurrency(echeance.mensualite)}</td>
            <td>${dataManager.formatCurrency(echeance.montantCapital)}</td>
            <td>${dataManager.formatCurrency(echeance.montantInteret)}</td>
            <td>${dataManager.formatCurrency(echeance.soldeRestant)}</td>
            <td>
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${echeance.pourcentageRembourse.toFixed(1)}%"></div>
                    </div>
                    <span class="progress-text">${echeance.pourcentageRembourse.toFixed(1)}%</span>
                </div>
            </td>
        </tr>
    `).join('');
}

function updateAmortizationSummary(simulation) {
    document.getElementById('summary-capital').textContent =
        dataManager.formatCurrency(simulation.montant);
    document.getElementById('summary-interets').textContent =
        dataManager.formatCurrency(simulation.totalInterets);
    document.getElementById('summary-total').textContent =
        dataManager.formatCurrency(simulation.totalAPayer);
}

function changeViewMode() {
    if (currentSimulation) {
        displayAmortizationTable(currentSimulation.tableauAmortissement);
    }
}

function genererPDF() {
    if (!currentSimulation) {
        showNotification('Aucune simulation à exporter', 'error');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Header
    doc.setFontSize(20);
    doc.text(' BankLoan Pro', 20, 20);
    doc.setFontSize(16);
    doc.text('Tableau d\'Amortissement', 20, 35);

    // Client info
    if (currentSimulation.nomClient) {
        doc.setFontSize(12);
        doc.text(`Client: ${currentSimulation.nomClient}`, 20, 50);
    }

    doc.text(`Date: ${new Date().toLocaleDateString('fr-FR')}`, 20, 60);

    // Loan summary
    doc.setFontSize(14);
    doc.text('Résumé du Prêt:', 20, 80);
    doc.setFontSize(11);

    const summaryData = [
        ['Montant du prêt', dataManager.formatCurrency(currentSimulation.montant)],
        ['Taux d\'intérêt mensuel', currentSimulation.tauxMensuel.toFixed(2) + '%'],
        ['Durée', currentSimulation.dureeMois + ' mois'],
        ['Mensualité', dataManager.formatCurrency(currentSimulation.mensualite)],
        ['Total à payer', dataManager.formatCurrency(currentSimulation.totalAPayer)],
        ['Total intérêts', dataManager.formatCurrency(currentSimulation.totalInterets)]
    ];

    doc.autoTable({
        head: [['Description', 'Valeur']],
        body: summaryData,
        startY: 90,
        margin: { left: 20, right: 20 },
        headStyles: { fillColor: [59, 130, 246] },
        styles: { fontSize: 10 }
    });

    // Amortization table
    const tableColumns = [
        'Échéance', 'Date', 'Mensualité', 'Capital', 'Intérêts', 'Solde Restant'
    ];

    const tableRows = currentSimulation.tableauAmortissement.map(echeance => [
        echeance.numero.toString(),
        dataManager.formatDate(echeance.date),
        dataManager.formatCurrency(echeance.mensualite),
        dataManager.formatCurrency(echeance.montantCapital),
        dataManager.formatCurrency(echeance.montantInteret),
        dataManager.formatCurrency(echeance.soldeRestant)
    ]);

    doc.autoTable({
        head: [tableColumns],
        body: tableRows,
        startY: doc.lastAutoTable.finalY + 20,
        margin: { left: 20, right: 20 },
        headStyles: { fillColor: [59, 130, 246] },
        styles: { fontSize: 8 },
        columnStyles: {
            0: { halign: 'center' },
            2: { halign: 'right' },
            3: { halign: 'right' },
            4: { halign: 'right' },
            5: { halign: 'right' }
        }
    });

    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.text(`Page ${i} sur ${pageCount}`,
            doc.internal.pageSize.width - 40,
            doc.internal.pageSize.height - 10);
    }

    // Save PDF
    const filename = `tableau_amortissement_${currentSimulation.nomClient || 'simulation'}_${new Date().toISOString().split('T')[0]}.pdf`;
    doc.save(filename);

    showNotification('PDF généré avec succès', 'success');
}

function exportTableauCSV() {
    if (!currentSimulation) {
        showNotification('Aucune simulation à exporter', 'error');
        return;
    }

    const headers = ['Échéance', 'Date', 'Mensualité', 'Capital', 'Intérêts', 'Solde Restant', '% Remboursé'];
    const rows = currentSimulation.tableauAmortissement.map(echeance => [
        echeance.numero,
        dataManager.formatDate(echeance.date),
        echeance.mensualite.toFixed(2),
        echeance.montantCapital.toFixed(2),
        echeance.montantInteret.toFixed(2),
        echeance.soldeRestant.toFixed(2),
        echeance.pourcentageRembourse.toFixed(2)
    ]);

    const csvContent = [headers.join(','), ...rows.map(row => row.join(','))].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `tableau_amortissement_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();

    showNotification('Tableau exporté en CSV avec succès', 'success');
}

function resetSimulation() {
    // Reset form
    document.getElementById('simulation-form').reset();
    setDefaultDate();

    // Hide results
    document.getElementById('simulation-results').style.display = 'none';
    document.getElementById('amortissement-container').style.display = 'none';
    document.getElementById('type-info').style.display = 'none';

    // Clear hints
    document.getElementById('montant-hint').textContent = '';
    document.getElementById('duree-hint').textContent = '';

    currentSimulation = null;

    showNotification('Simulation réinitialisée', 'info');
}

function setupSaveSimulationForm() {
    const form = document.getElementById('save-simulation-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!currentSimulation) {
            showNotification('Aucune simulation à sauvegarder', 'error');
            return;
        }

        const formData = new FormData(form);
        const simulationToSave = {
            ...currentSimulation,
            nom: formData.get('nom'),
            description: formData.get('description')
        };

        dataManager.addSimulation(simulationToSave);
        showNotification('Simulation sauvegardée avec succès', 'success');

        closeModal('save-simulation-modal');
        loadSavedSimulations();
    });
}

function loadSavedSimulations() {
    const simulations = dataManager.getSimulations();
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
                <h4>${sim.nom}</h4>
                <div class="simulation-actions">
                    <button class="btn-sm btn-secondary" onclick="loadSimulation('${sim.id}')">
                        Charger
                    </button>
                    <button class="btn-sm btn-danger" onclick="deleteSimulation('${sim.id}')">
                        Supprimer
                    </button>
                </div>
            </div>
            <div class="simulation-details">
                <div class="simulation-stat">
                    <span>Montant:</span>
                    <span>${dataManager.formatCurrency(sim.montant)}</span>
                </div>
                <div class="simulation-stat">
                    <span>Durée:</span>
                    <span>${sim.dureeMois} mois</span>
                </div>
                <div class="simulation-stat">
                    <span>Mensualité:</span>
                    <span>${dataManager.formatCurrency(sim.mensualite)}</span>
                </div>
            </div>
            ${sim.description ? `<p class="simulation-description">${sim.description}</p>` : ''}
            <div class="simulation-date">
                Créée le ${dataManager.formatDate(sim.dateCreation)}
            </div>
        </div>
    `).join('');
}

function loadSimulation(id) {
    const simulations = dataManager.getSimulations();
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

function deleteSimulation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette simulation ?')) {
        dataManager.deleteSimulation(id);
        loadSavedSimulations();
        showNotification('Simulation supprimée avec succès', 'success');
    }
}

// Add CSS for simulation components
const simulationStyles = `
    .simulation-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .simulation-form,
    .simulation-results {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }
    
    .simulation-form h3,
    .simulation-results h3 {
        color: #1e40af;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }
    
    .btn-large {
        width: 100%;
        padding: 1rem;
        font-size: 1.1rem;
        margin-top: 1rem;
    }
    
    .result-summary {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        border-left: 4px solid #3b82f6;
    }
    
    .result-value {
        font-weight: 700;
        color: #1e40af;
        font-size: 1.1rem;
    }
    
    .result-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .result-actions button {
        flex: 1;
        min-width: 120px;
    }
    
    .amortissement-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .amortissement-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .amortissement-summary {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        background: white;
        border-radius: 0.375rem;
        font-weight: 600;
    }
    
    .progress-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
    }
    
    .progress-bar {
        flex: 1;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #1e40af);
        transition: width 0.3s ease;
    }
    
    .progress-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #374151;
        min-width: 35px;
    }
    
    .saved-simulations {
        margin-top: 2rem;
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .saved-simulations h3 {
        color: #1e40af;
        margin-bottom: 1rem;
    }
    
    .simulation-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .simulation-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }
    
    .simulation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .simulation-header h4 {
        color: #1e40af;
        margin: 0;
        font-size: 1.1rem;
    }
    
    .simulation-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .simulation-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .simulation-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }
    
    .simulation-stat span:last-child {
        font-weight: 600;
        color: #1e40af;
    }
    
    .simulation-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0.5rem 0;
        font-style: italic;
    }
    
    .simulation-date {
        font-size: 0.75rem;
        color: #9ca3af;
        text-align: right;
    }
    
    .type-details {
        padding: 0.75rem;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }
    
    .type-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .simulation-container {
            grid-template-columns: 1fr;
        }
        
        .result-actions {
            flex-direction: column;
        }
        
        .result-actions button {
            width: 100%;
        }
        
        .amortissement-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .amortissement-controls {
            justify-content: space-between;
        }
        
        .simulation-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }
        
        .simulation-actions {
            justify-content: space-between;
        }
    }
`;

const styleSheet3 = document.createElement('style');
styleSheet3.textContent = simulationStyles;
document.head.appendChild(styleSheet3);