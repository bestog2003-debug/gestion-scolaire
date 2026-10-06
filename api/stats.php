<?php
// Statistiques du tableau de bord : totaux, moyennes par classe, élèves en difficulté.
function handleStats(): void {
    requireRole('admin');
    $db = getDb();
    $students = $db->query('SELECT st.id, st.first_name, st.last_name, c.name AS class_name FROM students st LEFT JOIN classes c ON c.id = st.class_id')->fetchAll();

    $atRiskStudents = [];
    $averagesByClass = [];
    $allAverages = [];
    foreach ($students as $student) {
        $average = buildStudentReport($student['id'])['generalAverage'];
        if ($average === null) continue;
        $allAverages[] = $average;
        $averagesByClass[$student['class_name'] ?? 'Sans classe'][] = $average;
        if ($average < 10) $atRiskStudents[] = $student + ['average' => $average];
    }

    $classAverages = [];
    foreach ($averagesByClass as $className => $averages) {
        $classAverages[] = ['class_name' => $className, 'average' => round(array_sum($averages) / count($averages), 2)];
    }

    respondJson([
        'studentCount' => count($students),
        'classCount' => (int) $db->query('SELECT COUNT(*) FROM classes')->fetchColumn(),
        'unjustifiedAbsenceCount' => (int) $db->query('SELECT COUNT(*) FROM absences WHERE justified = 0')->fetchColumn(),
        'schoolAverage' => $allAverages ? round(array_sum($allAverages) / count($allAverages), 2) : null,
        'classAverages' => $classAverages,
        'atRiskStudents' => $atRiskStudents,
    ]);
}
