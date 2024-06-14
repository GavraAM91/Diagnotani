<?php

namespace Core;

use Core\database;

class SolusiPenyakit
{

    protected $id_penyakit;

    public function __construct($id_penyakit)
    {
        $this->id_penyakit = $id_penyakit;
    }
}

class SolusiJagung extends SolusiPenyakit
{

    private $solusi;

    public function __construct($id_penyakit)
    {
        parent::__construct($id_penyakit);
    }

    public function SolusiPenyakitJagung()
    {
        $db = new database();

        //open sql data solusi penyembuhan penyakit jagung
        $sql_solusi = "SELECT p.id_penyakit, sj.id_penyakit, sj.id_solusi, sj.solusi
        FROM tb_solusi_jagung sj JOIN tb_penyakit_jagung p ON p.id_penyakit = sj.id_penyakit
        WHERE p.id_penyakit = ?";

        $sql_solusi = $db->getConnection()->prepare($sql_solusi);
        $sql_solusi->bind_param("s", $this->id_penyakit);
        $sql_solusi->execute();

        $result = $sql_solusi->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->solusi = $row['solusi'];
            }
        }
        $sql_solusi->close();
    }

    public function getSolusi()
    {
        return $this->solusi;
    }
}
