<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Liste des prêts</title>
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/main.css">
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/components.css">
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/forms.css">
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/tables.css">
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/loan-page.css">
  
</head>

<body>

  <?php include "navbar.php" ?>


  <h1>Liste des prêts</h1>

  <div style="margin-bottom: 10px;">
    <label>Type prêt :</label>
    <select id="filtreTypePret">
      <option value="">-- Tous --</option>
    </select>

    <label>Client :</label>
    <select id="filtreClient">
      <option value="">-- Tous --</option>
    </select>

    <label>Date début :</label>
    <input type="date" id="filtreDateDebut">

    <label>Date fin :</label>
    <input type="date" id="filtreDateFin">

    <button onclick="filtrerPrets()">Filtrer</button>
    <button onclick="resetFiltre()">Réinitialiser</button>
  </div>


  <table id="table-prets">
    <thead>
      <tr>
        <th>ID</th>
        <th>Type Prêt</th>
        <th>Client</th>
        <th>Date Début</th>
        <th>Durée (mois)</th>
        <th>Date Fin</th>
        <th>Montant</th>
        <th>Statut</th>

        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    const apiBase = "<?= API_URL ?>";
  </script>
<script src="<?= STATIC_URL ?>/js/script.js">
  console.log()
  </script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    console.log("lkjhg")
    chargerPrets();
    chargerClients();
    chargerTypesPret();
  });
//   function chargerPrets() {
//     ajax(
//         "GET",
//         "/prets/list",
//         (data) => {
//             console.log(";lkjhgf");
//             const tbody = document.querySelector("#table-prets tbody");
//             tbody.innerHTML = "";
//             if (data.length === 0) {
//                 tbody.innerHTML = '<tr><td colspan="7">Aucun prêt disponible.</td></tr>';
//             } else {
//                 data.forEach(pret => {
//                     const tr = document.createElement("tr");
//                     tr.innerHTML = `
//                         <td>${pret.id}</td>
//                         <td>${pret.type_pret}</td>
//                         <td>${pret.client_nom} ${pret.client_prenom}</td>
//                         <td>${pret.date_debut}</td>
//                         <td>${pret.duree}</td>
//                         <td>${pret.date_fin}</td>
//                         <td>${pret.montant}</td>
                        
//                     `;
//                     tbody.appendChild(tr);
//                 });
//             }
//         },
//         (status, response) => {
//             console.error(`Erreur ${status}: ${response}`);
//             const tbody = document.querySelector("#table-prets tbody");
//             tbody.innerHTML = '<tr><td colspan="7">Erreur lors du chargement des prêts.</td></tr>';
//         }
//     );
// }
  
  </script>
</body>

</html>