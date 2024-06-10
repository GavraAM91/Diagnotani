<?php
namespace Core;

use Core\database;

class IDGeneratorAccount
{
    private $lastID;

    public function __construct($lastID = 0)
    {
        $this->lastID = $lastID;
    }

    public function generateID()
    {
        $this->lastID++;
        return "USR" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }

    public function getLastUserID()
    {
        $db = new database();

        $connection_database = $db->getConnection();
        $result = $connection_database->query("SELECT id_user FROM tb_account ORDER BY id_user DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_user'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }

}
