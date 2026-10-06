<?php
// CRUD des classes et des matières (référentiel de l'école).
function handleListClasses(): void {
    requireLogin();
    respondJson(getDb()->query('SELECT id, name FROM classes ORDER BY name')->fetchAll());
}

function handleCreateClass(): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['name']);
    getDb()->prepare('INSERT OR IGNORE INTO classes (name) VALUES (?)')->execute([$body['name']]);
    respondJson(['ok' => true], 201);
}

function handleUpdateClass(int $classId): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['name']);
    getDb()->prepare('UPDATE classes SET name = ? WHERE id = ?')->execute([$body['name'], $classId]);
    respondJson(['ok' => true]);
}

function handleDeleteClass(int $classId): void {
    requireRole('admin');
    getDb()->prepare('DELETE FROM classes WHERE id = ?')->execute([$classId]);
    respondJson(['ok' => true]);
}

function handleListSubjects(): void {
    requireLogin();
    respondJson(getDb()->query('SELECT id, name, coefficient FROM subjects ORDER BY name')->fetchAll());
}

function handleCreateSubject(): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['name', 'coefficient']);
    getDb()->prepare('INSERT OR IGNORE INTO subjects (name, coefficient) VALUES (?, ?)')->execute([$body['name'], $body['coefficient']]);
    respondJson(['ok' => true], 201);
}

function handleUpdateSubject(int $subjectId): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['name', 'coefficient']);
    getDb()->prepare('UPDATE subjects SET name = ?, coefficient = ? WHERE id = ?')->execute([$body['name'], $body['coefficient'], $subjectId]);
    respondJson(['ok' => true]);
}

function handleDeleteSubject(int $subjectId): void {
    requireRole('admin');
    getDb()->prepare('DELETE FROM subjects WHERE id = ?')->execute([$subjectId]);
    respondJson(['ok' => true]);
}

function requireLogin(): void {
    if (!currentUser()) failWith(401, 'Connexion requise');
}
