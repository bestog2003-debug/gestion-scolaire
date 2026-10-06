<?php
// Connexion SQLite ; crée le schéma et le compte admin au tout premier lancement.
function getDb(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $path = __DIR__ . '/data/school.sqlite';
    $isNewDatabase = !file_exists($path);
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    if ($isNewDatabase) {
        $pdo->exec(file_get_contents(__DIR__ . '/schema.sql'));
        createFirstAdmin($pdo);
    }
    return $pdo;
}

// Le mot de passe admin vient de ADMIN_PASSWORD, sinon il est généré et écrit dans data/ (ignoré par git).
function createFirstAdmin(PDO $pdo): void {
    $password = getenv('ADMIN_PASSWORD') ?: bin2hex(random_bytes(5));
    $email = getenv('ADMIN_EMAIL') ?: 'admin@ecole.local';
    $pdo->prepare('INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)')
        ->execute([$email, password_hash($password, PASSWORD_DEFAULT), 'admin']);
    if (!getenv('ADMIN_PASSWORD')) file_put_contents(__DIR__ . "/data/first-admin.txt", "$email / $password\n");
}
