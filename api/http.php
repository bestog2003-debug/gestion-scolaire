<?php
// Petites fonctions partagées pour lire la requête et répondre en JSON.
function respondJson($payload, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

function failWith(int $status, string $message): void {
    respondJson(['error' => $message], $status);
}

function readJsonBody(): array {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}

function requireFields(array $body, array $fields): void {
    foreach ($fields as $field) {
        if (!isset($body[$field]) || $body[$field] === '') failWith(422, "Champ requis : $field");
    }
}
