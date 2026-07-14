<?php
header('Content-Type: application/json');
require_once 'helpers.php';

$putanjaZadataka = __DIR__ . '/../data/zadaci.json';
$metoda = $_SERVER['REQUEST_METHOD'];

switch ($metoda) {

    case 'GET':
        $zadaci = citajJson($putanjaZadataka);
        echo json_encode(['success' => true, 'zadaci' => $zadaci]);
        break;

    case 'POST':
        $ulaz = citajUlazniJson();
        $naziv = trim($ulaz['naziv'] ?? '');
        $rok = trim($ulaz['rok'] ?? '');

        if ($naziv === '' || mb_strlen($naziv) > 100) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Naziv zadatka je obavezan (max 100 karaktera).']);
            exit;
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $rok) || !strtotime($rok)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Rok mora biti u formatu GGGG-MM-DD.']);
            exit;
        }

        $zadaci = citajJson($putanjaZadataka);
        $noviId = 1;
        foreach ($zadaci as $z) {
            if ($z['id'] >= $noviId) $noviId = $z['id'] + 1;
        }

        $noviZadatak = [
            'id' => $noviId,
            'naziv' => $naziv,
            'rok' => $rok,
            'zavrsen' => false
        ];

        $zadaci[] = $noviZadatak;
        pisiJson($putanjaZadataka, $zadaci);

        echo json_encode(['success' => true, 'zadatak' => $noviZadatak]);
        break;

    case 'PUT':
        // ID zadatka stiže iz hidden polja na klijentu
        $ulaz = citajUlazniJson();
        $id = intval($ulaz['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Nevažeći ID zadatka.']);
            exit;
        }

        $zadaci = citajJson($putanjaZadataka);
        $pronadjen = false;

        foreach ($zadaci as &$z) {
            if ($z['id'] === $id) {
                $z['zavrsen'] = !$z['zavrsen'];
                $pronadjen = true;
                break;
            }
        }
        unset($z);

        if (!$pronadjen) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Zadatak nije pronađen.']);
            exit;
        }

        pisiJson($putanjaZadataka, $zadaci);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        $ulaz = citajUlazniJson();
        $id = intval($ulaz['id'] ?? 0);

        $zadaci = citajJson($putanjaZadataka);
        $pocetnaDuzina = count($zadaci);
        $zadaci = array_values(array_filter($zadaci, fn($z) => $z['id'] !== $id));

        if (count($zadaci) === $pocetnaDuzina) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Zadatak nije pronađen.']);
            exit;
        }

        pisiJson($putanjaZadataka, $zadaci);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Metoda nije dozvoljena.']);
}
