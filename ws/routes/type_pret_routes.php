<?php
require_once __DIR__ . '/../models/TypePretModel.php';

Flight::route('GET /type_prets', function () {
    $model = new TypePretModel();
    Flight::json($model->getAll());
});

Flight::route('GET /type_pret/@id', function ($id) {
    $model = new TypePretModel();
    $data = $model->getById($id);
    if ($data) {
        Flight::json($data);
    } else {
        Flight::halt(404, 'Type de prêt non trouvé');
    }
});

Flight::route('POST /type_pret', function () {
    $data = Flight::request()->data;
    $model = new TypePretModel();
    $id = $model->create($data->nom, $data->taux);
    Flight::json(['message' => 'Type de prêt créé', 'id' => $id]);
});

Flight::route('PUT /type_pret/@id', function ($id) {
    parse_str(Flight::request()->getBody(), $putData);
    $model = new TypePretModel();
    $affected = $model->update($id, $putData['nom'], $putData['taux']);
    Flight::json(['message' => 'Type de prêt modifié', 'affected' => $affected]);
});

Flight::route('DELETE /type_pret/@id', function ($id) {
    $model = new TypePretModel();
    $affected = $model->delete($id);
    Flight::json(['message' => 'Type de prêt supprimé', 'affected' => $affected]);
});
