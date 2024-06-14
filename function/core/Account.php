<?php

namespace Core;

use Core\IDGeneratorAccount;
use Core\database;

require_once 'IdGenerator.php';

class account
{
    protected $username, $password, $confirmPassword, $email, $checkbox, $job_desk, $id_user;

    public function __construct($username = "username", $password = "password", $confirmPassword = "confirmPassword", $email = "email", $checkbox = "checkbox", $job_desk = "job_desk", $id_user = "id_user")
    {
        $this->username = $username;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->email = $email;
        $this->checkbox = $checkbox;
        $this->job_desk = $job_desk;
        $this->id_user = $id_user;
    }

    //get the username
    public function getUsername()
    {
        return $this->username;
    }

    //validation(setter)
    public function setUsername($username)
    {
        //jika password sama dengan atau lebih panjang dari 3 huruf maka set username
        if (strlen($username) > 3) {
            $this->username = $username;
        } else {
            echo "<script>
                alert('username tidak boleh kurang dari 3 huruf!');
            </script";
        }
    }
}

class signUp extends account
{
    //construct
    public function __construct($username, $password, $email)
    {
        parent::__construct($username, $password, $email);
    }

    public function signup()
    {
        //connection from database
        $db = new database();

        //connection to generator UUID
        $ID_generator = new IDGeneratorAccount();

        //get last id from database
        $lastIDFromDB = $ID_generator->getLastUserID();  
        $generator = new IDGeneratorAccount($lastIDFromDB);
        $newID = $generator->generateID();

        //get last user id
        // $lastIDFromDB = $db->getConnection();
        // $generator = new IDGeneratorAccount($lastIDFromDB); 
        // $newID = $generator->generateID();

        //check username sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE username = '$this->username'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('username sudah digunakan');
        </script>";
            return false;
        }

        //check email sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE email = '$this->email'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('Email sudah digunakan');
        </script>";
            return false;
        }

        // var_dump($this->password);
        // var_dump($this->confirmPassword);
        // exit;

        // Konfirmasi password
        // if ($this->password != $this->confirmPassword) {
        //     echo "<script>
        // alert('Password tidak sama!');
        // </script>";
        //     return false;
        // }

        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = $db->getConnection()->prepare("INSERT INTO `tb_account`(`id_user`,`username`, `email`, `password`) VALUES (?,?,?,?)");
        $sql->bind_param("ssss", $newID, $this->username, $this->email, $password_hash);

        if ($sql->execute()) {
            echo "<script>
                alert('signup succes!');
            </script>";
            header("Location: login.php");
        } else {
            echo "<script>
                alert('signup failed :(');
            </script>";
            return false;
        }
    }
}

class Login extends account
{
    //construct
    public function __construct($username, $password)
    {
        parent::__construct($username, $password);
    }

    public function login()
    {
        $db = new database();
        $conn = $db->getConnection(); // Dapatkan koneksi database

        // Siapkan query untuk mencari user berdasarkan username
        $query = $conn->prepare("SELECT * FROM tb_account WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();

        $password = $this->password;

        session_start();
        // Cek jika user ditemukan
        if ($user = $result->fetch_assoc()) {
            // Verifikasi password
            // if (password_verify($password, $user['password'])) {
            if($password === $user['password']) {
                //jika chcekbox bernilai 1;
                if ($this->checkbox == 1) {
                    //buat cookie
                    setcookie('id', $user['id_account'], time() + 600);
                    setcookie('key', hash('sha256', $user['username']));
                }

                // Password benar, login berhasil
                $_SESSION['role'] = $user['role'];

                //set id into session
                $_SESSION['id_user'] = $user['id_user'];

                // Redireksi berdasarkan role
                if ($user['role'] == 'admin') {

                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";

                    $_SESSION['username'] = $this->username;
                    header('Location: index.php');
                    exit();
                } else if ($user['role'] == 'user') {
                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";

                    $_SESSION['username'] = $this->username;
                    header('Location: ../index.php');
                    exit(); // Menambahkan exit setelah header
                }
            } else {
                // Password salah
                echo "<script>
                    alert('Password salah!');
                    </script>";
                return false;
            }
        } else {
            // Username tidak ditemukan
            echo "<script>
                alert('Username tidak ditemukan!');
                </script>";
            return false;
        }
    }
}
