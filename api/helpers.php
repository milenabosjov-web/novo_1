<?php

function citajJson($putanja) {
    if (!file_exists($putanja)) {
        return [];
    }
    $sadrzaj = file_get_contents($putanja);
    $podaci = json_decode($sadrzaj, true);
    return $podaci ?? [];
}

function pisiJson($putanja, $podaci) {
    file_put_contents($putanja, json_encode($podaci, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function citajUlazniJson() {
    $sirovo = file_get_contents('php://input');
    $podaci = json_decode($sirovo, true);
    return $podaci ?? [];
}
