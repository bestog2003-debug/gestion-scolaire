<?php
// CRUD des élèves (chaque élève possède aussi un compte de connexion).
function handleListStudents(): void {
    requireRole('admin');
    $search = '%' . ($_GET['search'] ?? '') . '%';
    // Élève + classe + email de connexion, filtrés sur le nom ou le prénom
    $stmt = getDb()->prepare(
        'SELECT st.id, st.first_name, st.last_name, st.class_id, c.name AS class_name, u.email
         FROM students st
         JOIN users u ON u.id = st.user_id
         LEFT JOIN classes c ON c.id = st.class_id
         WHERE st.first_name LIKE :search OR st.last_name LIKE :search
         ORDER BY st.last_name, st.first_name');
    $stmt->execute([':search' => $search]);
    respondJson($stmt->fetchAll());
}

function handleCreateStudent(): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['first_name', 'last_name', 'email', 'password']);
    $db = getDb();
    try {
        $db->beginTransaction();
        $db->prepare('INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)')
           ->execute([$body['email'], password_hash($body['password'], PASSWORD_DEFAULT), 'student']);
        $userId = $db->lastInsertId();
        $db->prepare('INSERT INTO students (user_id, first_name, last_name, class_id) VALUES (?, ?, ?, ?)')
           ->execute([$userId, $body['first_name'], $body['last_name'], ($body['class_id'] ?? null) ?: null]);
        $db->commit();
    } catch (PDOException $error) {
        $db->rollBack();
        failWith(409, 'Cet email est déjà utilisé');
    }
    respondJson(['id' => (int) $db->query('SELECT MAX(id) FROM students')->fetchColumn()], 201);
}

function handleUpdateStudent(int $studentId): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['first_name', 'last_name']);
    getDb()->prepare('UPDATE students SET first_name = ?, last_name = ?, class_id = ? WHERE id = ?')
           ->execute([$body['first_name'], $body['last_name'], ($body['class_id'] ?? null) ?: null, $studentId]);
    respondJson(['ok' => true]);
}

// Supprimer le compte utilisateur supprime en cascade l'élève, ses notes et ses absences.
function handleDeleteStudent(int $studentId): void {
    requireRole('admin');
    getDb()->prepare('DELETE FROM users WHERE id = (SELECT user_id FROM students WHERE id = ?)')->execute([$studentId]);
    respondJson(['ok' => true]);
}
