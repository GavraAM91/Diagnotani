<?php
namespace Core;

class DataGejalaJagung {
    private $gejala = [];

    public function __construct($gejala = []) {
        $this->gejala = $gejala;
    }

    public function dataGejala() {
        // Inisialisasi database
        $db = new Database();

        $sql = "SELECT * FROM tb_gejala_jagung";
        $result = $db->getConnection()->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->gejala[] = $row;
            }
        }
    }

    public function getGejala() {
        return $this->gejala;
    }

    public function countGejala() {
        $db = new database();

        $sql_count_gejala = "SELECT COUNT(*) AS total FROM tb_gejala_jagung";
        $result =  $db->getConnection()->query($sql_count_gejala);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total']; 
        } else {
            return 0; 
        }
    }

}
