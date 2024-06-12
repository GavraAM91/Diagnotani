<?php

namespace Core;

session_start();

require_once '../function/init.php';

use Core\DataGejalaJagung;
use Core\Question;
use Core\ShowData;

// Inisialisasi objek DataGejala
$datagejala = new DataGejalaJagung();

//mendapatkan seluruh total pertanyaan / gejala dari database
$totalGejala = $datagejala->countGejala();


// Periksa apakah pertanyaan saat ini sudah ada dalam sesi
if (!isset($_SESSION['current_question'])) {
    $datagejala->dataGejala();
    $gejala = $datagejala->getGejala();

    // Simpan semua gejala dalam sesi untuk akses berikutnya
    $_SESSION['gejala'] = $gejala;

    // Periksa apakah ada pertanyaan
    if (!empty($gejala)) {
        // Jika ada, simpan pertanyaan pertama dalam sesi
        $_SESSION['current_question_index'] = 0;
        $_SESSION['current_question'] = $gejala[0];
    }
}

// Ambil pertanyaan yang akan ditampilkan
$current_question_index = $_SESSION['current_question_index'] ?? 0;
$gejala = $_SESSION['gejala'] ?? [];
$current_question = $gejala[$current_question_index] ?? null;

// Jika jawaban dikirim oleh user
if (isset($_POST['submit'])) {
    $id_gejala_user = $_POST['id_gejala'];
    $value_gejala_user = $_POST['nilai_gejala'];

    // Pastikan combined_data disimpan dalam sesi dan adalah array
    if (!isset($_SESSION['combined_data'])) {
        $_SESSION['combined_data'] = [];
    }
    // Instantiate ShowData and process the question
    $sql_data = new ShowData($id_gejala_user, $value_gejala_user);
    $combined_cf = $sql_data->question_process();

    // Simpan id_gejala dan nilai_gejala ke dalam array dalam sesi bersama combined_cf
    $_SESSION['combined_data'][] = [
        'id_gejala' => $id_gejala_user,
        'nilai_gejala' => $value_gejala_user,
        'combined_cf' => $combined_cf,
    ];


    // Pindah ke pertanyaan berikutnya
    $current_question_index++;
    if ($current_question_index < count($gejala)) {
        $_SESSION['current_question_index'] = $current_question_index;
        $_SESSION['current_question'] = $gejala[$current_question_index];
    } else {
        // Jika tidak ada pertanyaan berikutnya, hapus sesi pertanyaan saat ini
        unset($_SESSION['current_question']);
        unset($_SESSION['current_question_index']);
    }

    // Redirect to result page if all questions have been answered
    if (!isset($_SESSION['current_question'])) {
        header('Location: result.php');
        exit;
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DiagnoTani || Question Page</title>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="style/style.css" />
</head>

<body>
    <div class="container">
        <main class="main-content">
            <?php
            if (isset($current_question)) :
            ?>
                <header class="header-question-page">
                    <h3>1 / <?= $totalGejala?></h3>
                    <p> Apakah <?= $current_question['gejala'];?></p>
                </header>

                <form class="opsi" method="post" action="">
                    <div class="pilihan-1">
                        <input type="hidden" name="id_gejala" value="<?= $current_question['id_gejala'] ?>">
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="1.0" />
                            <span class="checkmark"></span>
                            <span>Sangat Yakin</span>
                        </label>
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="0.8" />
                            <span class="checkmark"></span>
                            <span>Yakin</span>
                        </label>
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="0.6" />
                            <span class="checkmark"></span>
                            <span>Sedikit yakin</span>
                        </label>
                    </div>
                    <!-- batas pilihan 1 dan pilihan 2 -->
                    <div class="pilihan-2">
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="0.6" />
                            <span class="checkmark"></span>
                            <span>Cukup yakin</span>
                        </label>
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="0.2" />
                            <span class="checkmark"></span>
                            <span>Tidak Tahu</span>
                        </label>
                        <label>
                            <input type="radio" name="nilai_gejala" id="pilihan" value="0.0" />
                            <span class="checkmark"></span>
                            <span>Tidak</span>
                        </label>
                    </div>

                    <!-- button -->
                    <button class="next-button" type="submit" name="submit">
                        selanjutnya
                    </button>
                </form>
            <?php else : ?>
                <p>Tidak ada data gejala.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>

