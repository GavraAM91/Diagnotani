<?php

namespace Core;

use Core\database;

class History
{
    private $id_penyakit, $id_user, $value;

    public function __construct($id_penyakit, $id_user, $value)
    {
        $this->id_penyakit = $id_penyakit;
        $this->id_user = $id_user;
        $this->value = $value;
    }

    public function addHistory()
    {
        $db = new database();

        $sql_history = "INSERT INTO `tb_history`(`id_history`,`id_penyakit`, `id_user`, `value`) 
                VALUES (?,?,?)";
        $sql_history = $db->getConnection()->prepare($sql_history);
        $sql_history->bind_param("sss", $this->id_penyakit, $this->id_user, $this->value);
        $sql_history->execute();
    }
}
