<?php
// Point d'entrée de l'API : on construit une clé "METHODE ressource" puis on appelle la bonne fonction.
require __DIR__ . '/db.php';
require __DIR__ . '/http.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/students.php';
require __DIR__ . '/catalog.php';
require __DIR__ . '/grades.php';
require __DIR__ . '/absences.php';
require __DIR__ . '/stats.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = '/' . trim(preg_replace('#^/api#', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
$parts = explode('/', trim($path, '/'));
$resource = $parts[0];
$id = (isset($parts[1]) && ctype_digit($parts[1])) ? (int) $parts[1] : 0;
$sub = $id ? ($parts[2] ?? '') : ($parts[1] ?? '');
$key = $method . ' ' . $resource . ($id ? ' {id}' : '') . ($sub !== '' ? ' ' . $sub : '');

try {
    switch ($key) {
        case 'POST login': handleLogin(); break;
        case 'POST logout': handleLogout(); break;
        case 'GET me': handleMe(); break;
        case 'GET me report': handleMyReport(); break;
        case 'GET stats': handleStats(); break;
        case 'GET students': handleListStudents(); break;
        case 'POST students': handleCreateStudent(); break;
        case 'PUT students {id}': handleUpdateStudent($id); break;
        case 'DELETE students {id}': handleDeleteStudent($id); break;
        case 'GET students {id} report': handleStudentReport($id); break;
        case 'GET classes': handleListClasses(); break;
        case 'POST classes': handleCreateClass(); break;
        case 'PUT classes {id}': handleUpdateClass($id); break;
        case 'DELETE classes {id}': handleDeleteClass($id); break;
        case 'GET subjects': handleListSubjects(); break;
        case 'POST subjects': handleCreateSubject(); break;
        case 'PUT subjects {id}': handleUpdateSubject($id); break;
        case 'DELETE subjects {id}': handleDeleteSubject($id); break;
        case 'POST grades': handleCreateGrade(); break;
        case 'PUT grades {id}': handleUpdateGrade($id); break;
        case 'DELETE grades {id}': handleDeleteGrade($id); break;
        case 'POST absences': handleCreateAbsence(); break;
        case 'PUT absences {id}': handleUpdateAbsence($id); break;
        case 'DELETE absences {id}': handleDeleteAbsence($id); break;
        default: failWith(404, 'Route inconnue');
    }
} catch (Throwable $error) {
    failWith(500, $error->getMessage());
}