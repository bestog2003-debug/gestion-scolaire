<?php
// Enregistrement et suivi des absences (justifiées ou non).
function handleCreateAbsence(): void {
    requireRole('admin');
    $body = readJsonBody();
    requireFields($body, ['student_id', 'absent_on']);
    getDb()->prepare('INSERT INTO absences (student_id, absent_on, justified, reason) VALUES (?, ?, ?, ?)')
           ->execute([$body['student_id'], $body['absent_on'], !empty($body['justified']) ? 1 : 0, $body['reason'] ?? null]);
    respondJson(['ok' => true], 201);
}

function handleUpdateAbsence(int $absenceId): void {
    requireRole('admin');
    $body = readJsonBody();
    getDb()->prepare('UPDATE absences SET justified = ?, reason = ? WHERE id = ?')
           ->execute([!empty($body['justified']) ? 1 : 0, $body['reason'] ?? null, $absenceId]);
    respondJson(['ok' => true]);
}

function handleDeleteAbsence(int $absenceId): void {
    requireRole('admin');
    getDb()->prepare('DELETE FROM absences WHERE id = ?')->execute([$absenceId]);
    respondJson(['ok' => true]);
}
