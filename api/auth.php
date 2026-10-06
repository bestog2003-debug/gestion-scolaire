<?php
// Connexion, déconnexion et contrôle d'accès par rôle (sessions PHP).
session_start();

function handleLogin(): void {
    $body = readJsonBody();
    requireFields($body, ['email', 'password']);
    $stmt = getDb()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$body['email']]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($body['password'], $user['password_hash'])) failWith(401, 'Email ou mot de passe incorrect');
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    respondJson(toPublicUser($user));
}

function handleLogout(): void {
    session_destroy();
    respondJson(['ok' => true]);
}

function handleMe(): void {
    $user = currentUser();
    if (!$user) failWith(401, 'Non connecté');
    respondJson($user);
}

// Utilisateur de la session, avec l'id de sa fiche élève si c'est un élève.
function currentUser(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = getDb()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ? toPublicUser($user) : null;
}

function toPublicUser(array $user): array {
    $stmt = getDb()->prepare('SELECT id FROM students WHERE user_id = ?');
    $stmt->execute([$user['id']]);
    return ['id' => $user['id'], 'email' => $user['email'], 'role' => $user['role'], 'studentId' => $stmt->fetchColumn() ?: null];
}

function requireRole(string $role): array {
    $user = currentUser();
    if (!$user) failWith(401, 'Connexion requise');
    if ($user['role'] !== $role) failWith(403, 'Accès refusé');
    return $user;
}
