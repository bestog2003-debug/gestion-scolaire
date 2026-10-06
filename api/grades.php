<?php
// Notes (saisie, modification, suppression) et calcul des moyennes d'un élève.
function handleCreateGrade(): void {
    requireRole('admin');
    $body = validateGradeBody(readJsonBody());
    getDb()->prepare('INSERT INTO grades (student_id, subject_id, value, coefficient, graded_on) VALUES (?, ?, ?, ?, ?)')
           ->execute([$body['student_id'], $body['subject_id'], $body['value'], $body['coefficient'], $body['graded_on']]);
    respondJson(['ok' => true], 201);
}

function handleUpdateGrade(int $gradeId): void {
    requireRole('admin');
    $body = validateGradeBody(readJsonBody());
    getDb()->prepare('UPDATE grades SET subject_id = ?, value = ?, coefficient = ?, graded_on = ? WHERE id = ?')
           ->execute([$body['subject_id'], $body['value'], $body['coefficient'], $body['graded_on'], $gradeId]);
    respondJson(['ok' => true]);
}

function handleDeleteGrade(int $gradeId): void {
    requireRole('admin');
    getDb()->prepare('DELETE FROM grades WHERE id = ?')->execute([$gradeId]);
    respondJson(['ok' => true]);
}

function validateGradeBody(array $body): array {
    requireFields($body, ['student_id', 'subject_id', 'value', 'graded_on']);
    if ($body['value'] < 0 || $body['value'] > 20) failWith(422, 'La note doit être comprise entre 0 et 20');
    $body['coefficient'] = $body['coefficient'] ?? 1;
    return $body;
}

function handleStudentReport(int $studentId): void {
    requireRole('admin');
    respondJson(buildStudentReport($studentId));
}

function handleMyReport(): void {
    $user = requireRole('student');
    respondJson(buildStudentReport((int) $user['studentId']));
}

// Relevé complet : notes groupées par matière, moyenne par matière, moyenne générale et absences.
function buildStudentReport(int $studentId): array {
    $db = getDb();
    $stmt = $db->prepare('SELECT id, first_name, last_name FROM students WHERE id = ?');
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();
    if (!$student) failWith(404, 'Élève introuvable');

    $stmt = $db->prepare(
        'SELECT g.id, g.subject_id, g.value, g.coefficient, g.graded_on, s.name AS subject_name, s.coefficient AS subject_coefficient
         FROM grades g JOIN subjects s ON s.id = g.subject_id
         WHERE g.student_id = ? ORDER BY s.name, g.graded_on');
    $stmt->execute([$studentId]);

    $subjects = [];
    foreach ($stmt->fetchAll() as $grade) {
        $subjectId = $grade['subject_id'];
        $subjects[$subjectId] ??= ['id' => $subjectId, 'name' => $grade['subject_name'], 'coefficient' => (float) $grade['subject_coefficient'], 'grades' => []];
        $subjects[$subjectId]['grades'][] = $grade;
    }
    foreach ($subjects as &$subject) {
        $subject['average'] = weightedAverage(array_column($subject['grades'], 'value'), array_column($subject['grades'], 'coefficient'));
    }
    unset($subject);
    $subjects = array_values($subjects);

    $stmt = $db->prepare('SELECT id, absent_on, justified, reason FROM absences WHERE student_id = ? ORDER BY absent_on DESC');
    $stmt->execute([$studentId]);

    return [
        'student' => $student,
        'subjects' => $subjects,
        // La moyenne générale pondère chaque moyenne de matière par le coefficient de la matière
        'generalAverage' => weightedAverage(array_column($subjects, 'average'), array_column($subjects, 'coefficient')),
        'absences' => $stmt->fetchAll(),
    ];
}

// Moyenne pondérée arrondie à 2 décimales ; null s'il n'y a rien à calculer.
function weightedAverage(array $values, array $weights): ?float {
    $totalWeight = array_sum($weights);
    if (!$values || $totalWeight == 0) return null;
    $weightedSum = 0;
    foreach ($values as $index => $value) $weightedSum += $value * $weights[$index];
    return round($weightedSum / $totalWeight, 2);
}
