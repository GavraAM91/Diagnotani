<?php

namespace Core;

use Core\Question;
use Core\ShowData;
use Core\Database;
use Core\History;
use Core\SolusiJagung;
use Core\SolusiPenyakit;
use Core\DataPenyakitJagung;
use Core\DataPenyakitGejalaJagung;

require_once '../function/init.php';

//start session
session_start();

//insialisasi database
$db = new database();

// Periksa apakah combined_data ada dalam sesi
if (!isset($_SESSION['combined_data']) || empty($_SESSION['combined_data'])) {
    echo "Tidak ada data gejala yang diproses.";
    exit;
}

// Hitung nilai CF akhir
$final_cf = array_reduce($_SESSION['combined_data'], function ($carry, $item) {
    $combined_cf = $item['combined_cf'];
    $carry = $carry + $combined_cf * (1 - $carry);
    return $carry;
}, 0);

$data_penyakit = $_SESSION['combined_data'];

$filtered_gejala = array_filter($data_penyakit, function ($item) {
    return $item['nilai_gejala'] > 0;
});

var_dump($filtered_gejala);
echo "</br>";

$id_gejala = array_column($filtered_gejala, 'id_gejala');

if (!empty($id_gejala)) {
    $placeholders = implode(',', array_fill(0, count($id_gejala), '?'));
    $sql_penyakit = "SELECT pg.id_penyakit, p.nama_penyakit
    FROM tb_penyakit_gejala_jagung pg
    JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
    WHERE pg.id_gejala IN ($placeholders)";

    $sql_penyakit = $db->getConnection()->prepare($sql_penyakit);
    $types = str_repeat('s', count($id_gejala));
    $sql_penyakit->bind_param($types, ...$id_gejala);
    $sql_penyakit->execute();
    $result = $sql_penyakit->get_result();

    // Variable untuk menyimpan data penyakit
    $penyakit_counts = [];
    while ($row = $result->fetch_assoc()) {
        $id_penyakit = $row['id_penyakit'];
        $nama_penyakit = $row['nama_penyakit'];
        if (!isset($penyakit_counts[$nama_penyakit])) {
            $penyakit_counts[$nama_penyakit] = 0;
        }
        $penyakit_counts[$nama_penyakit]++;
    }

    //inisialisasi SolusiJagung.php
    var_dump($id_penyakit);
    // $solusiJagung = new SolusiJagung($id_penyakit);
    // $solusiJagung->SolusiPenyakitJagung();
    // echo $solusiJagung->getSolusi();       

    // echo "<hr>";
    echo "</br>";
    echo $nama_penyakit;
    echo "</br>";
    echo $solusi;
    echo "</br>";

    //for testing
    // echo "<pre>";
    // print_r($penyakit_counts);
    // echo "</pre>";
}


$final_cf_percentage = $final_cf * 100;
echo $final_cf_percentage . '%';

//send data to history class
$data_history = new History($id_penyakit, $id_user, $final_cf_percentage);
$data_history->addHistory();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>