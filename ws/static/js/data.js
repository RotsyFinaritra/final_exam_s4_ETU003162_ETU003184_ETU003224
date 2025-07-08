// Data Storage and Management
class DataManager {
    constructor() {
        this.initializeData();
    }

    initializeData() {
        // Initialize with sample data if not exists
        if (!localStorage.getItem('bankLoanData')) {
            const initialData = {
                capital: [
                    {
                        id: '1',
                        montant: 1000000,
                        description: 'Capital initial de la banque',
                        dateCreation: new Date('2024-01-01').toISOString(),
                        statut: 'actif'
                    }
                ],
                typesPret: [
                    {
                        id: '1',
                        nom: 'Prêt Personnel',
                        tauxInteretMensuel: 1.5,
                        dureeMaxMois: 60,
                        montantMin: 1000,
                        montantMax: 50000,
                        description: 'Prêt pour besoins personnels',
                        dateCreation: new Date().toISOString(),
                        statut: 'actif'
                    },
                    {
                        id: '2',
                        nom: 'Prêt Immobilier',
                        tauxInteretMensuel: 0.8,
                        dureeMaxMois: 300,
                        montantMin: 50000,
                        montantMax: 500000,
                        description: 'Prêt pour achat immobilier',
                        dateCreation: new Date().toISOString(),
                        statut: 'actif'
                    },
                    {
                        id: '3',
                        nom: 'Prêt Auto',
                        tauxInteretMensuel: 1.2,
                        dureeMaxMois: 84,
                        montantMin: 5000,
                        montantMax: 80000,
                        description: 'Prêt pour véhicule',
                        dateCreation: new Date().toISOString(),
                        statut: 'actif'
                    }
                ],
                demandes: [],
                interets: [],
                simulations: []
            };
            this.saveData(initialData);
        }
    }

    getData() {
        return JSON.parse(localStorage.getItem('bankLoanData') || '{}');
    }

    saveData(data) {
        localStorage.setItem('bankLoanData', JSON.stringify(data));
    }

    // Capital Management
    getCapital() {
        return this.getData().capital || [];
    }

    addCapital(capital) {
        const data = this.getData();
        capital.id = this.generateId();
        capital.dateCreation = new Date().toISOString();
        data.capital = data.capital || [];
        data.capital.push(capital);
        this.saveData(data);
        return capital;
    }

    updateCapital(id, updates) {
        const data = this.getData();
        const index = data.capital.findIndex(c => c.id === id);
        if (index !== -1) {
            data.capital[index] = { ...data.capital[index], ...updates };
            this.saveData(data);
            return data.capital[index];
        }
        return null;
    }

    deleteCapital(id) {
        const data = this.getData();
        data.capital = data.capital.filter(c => c.id !== id);
        this.saveData(data);
    }

    // Types de Prêt Management
    getTypesPret() {
        return this.getData().typesPret || [];
    }

    addTypePret(type) {
        const data = this.getData();
        type.id = this.generateId();
        type.dateCreation = new Date().toISOString();
        data.typesPret = data.typesPret || [];
        data.typesPret.push(type);
        this.saveData(data);
        return type;
    }

    updateTypePret(id, updates) {
        const data = this.getData();
        const index = data.typesPret.findIndex(t => t.id === id);
        if (index !== -1) {
            data.typesPret[index] = { ...data.typesPret[index], ...updates };
            this.saveData(data);
            return data.typesPret[index];
        }
        return null;
    }

    deleteTypePret(id) {
        const data = this.getData();
        data.typesPret = data.typesPret.filter(t => t.id !== id);
        this.saveData(data);
    }

    // Demandes Management
    getDemandes() {
        return this.getData().demandes || [];
    }

    addDemande(demande) {
        const data = this.getData();
        demande.id = this.generateId();
        demande.dateCreation = new Date().toISOString();
        demande.statut = 'en_attente';
        data.demandes = data.demandes || [];
        data.demandes.push(demande);
        this.saveData(data);
        return demande;
    }

    updateDemande(id, updates) {
        const data = this.getData();
        const index = data.demandes.findIndex(d => d.id === id);
        if (index !== -1) {
            data.demandes[index] = { ...data.demandes[index], ...updates };
            if (updates.statut && updates.statut !== 'en_attente') {
                data.demandes[index].dateValidation = new Date().toISOString();
            }
            this.saveData(data);
            return data.demandes[index];
        }
        return null;
    }

    // Intérêts Management
    getInterets() {
        return this.getData().interets || [];
    }

    addInteret(interet) {
        const data = this.getData();
        interet.id = this.generateId();
        interet.dateCalcul = new Date().toISOString();
        data.interets = data.interets || [];
        data.interets.push(interet);
        this.saveData(data);
        return interet;
    }

    // Simulations Management
    getSimulations() {
        return this.getData().simulations || [];
    }

    addSimulation(simulation) {
        const data = this.getData();
        simulation.id = this.generateId();
        simulation.dateCreation = new Date().toISOString();
        data.simulations = data.simulations || [];
        data.simulations.push(simulation);
        this.saveData(data);
        return simulation;
    }

    deleteSimulation(id) {
        const data = this.getData();
        data.simulations = data.simulations.filter(s => s.id !== id);
        this.saveData(data);
    }

    // Utility Methods
    generateId() {
        return Date.now().toString() + Math.random().toString(36).substr(2, 9);
    }

    formatCurrency(amount) {
        console.log("lkfdnlkn");
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'MGA'
        }).format(amount);
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('fr-FR').format(new Date(date));
    }

    formatPercent(value) {
        return value.toFixed(2) + '%';
    }

    // Dashboard Statistics
    getDashboardStats() {
        const capital = this.getCapital();
        const typesPret = this.getTypesPret();
        const demandes = this.getDemandes();
        const interets = this.getInterets();

        const totalCapital = capital
            .filter(c => c.statut === 'actif')
            .reduce((sum, c) => sum + c.montant, 0);

        const totalTypes = typesPret.filter(t => t.statut === 'actif').length;
        
        const pendingRequests = demandes.filter(d => d.statut === 'en_attente').length;
        
        const currentMonth = new Date().getMonth() + 1;
        const currentYear = new Date().getFullYear();
        const monthlyInterest = interets
            .filter(i => i.mois === currentMonth && i.annee === currentYear)
            .reduce((sum, i) => sum + i.totalInterets, 0);

        return {
            totalCapital,
            totalTypes,
            pendingRequests,
            monthlyInterest
        };
    }
}

// Global instance
const dataManager = new DataManager();

// Utility Functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Reset hidden fields
            const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
            hiddenInputs.forEach(input => input.value = '');
        }
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        event.target.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
});

// Add notification styles
const notificationStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    }
    
    .notification-info { background: #3b82f6; }
    .notification-success { background: #10b981; }
    .notification-warning { background: #f59e0b; }
    .notification-error { background: #ef4444; }
    
    .notification button {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;

// Add styles to head
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);