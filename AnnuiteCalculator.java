public class AnnuiteCalculator {
    public static double calculerAnnuite(double capital, int duree, double tauxMensuel) {
        return capital * tauxMensuel / (1 - Math.pow(1 + tauxMensuel, -duree));
    }

    public static void main(String[] args) {
        double capital = 1000000;         // Montant emprunté
        int duree = 12;                  // Durée en mois
        double taux = 0.06;             // Taux mensuel (ex: 1%)

        double annuite = calculerAnnuite(capital, duree, taux);
        System.out.printf("Annuité mensuelle : %.2f\n", annuite);
    }
}
