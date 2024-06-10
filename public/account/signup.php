<?php

namespace Core;

use Core\signUp;

require_once '../../function/init.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql_signup = new signUp($username, $password, $confirmpassword, $email);
    $sql_signup->signup();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Diagnotani | SignUp Page</title>
    <link rel="stylesheet" href="../style/style.css" />
</head>

<body>
    <div class="container" id="container">
        <!--login-right-->
        <div class="login-right-signup"></div>
        <!-- login left -->
        <div class="login-left">
            <header class="header">
                <h1>Sign Up</h1>
                <p>Please fill your information below</p>
            </header>

            <!-- form input -->
            <form class="form-signin" method="POST">
                <!-- user -->
                <div class="input-user">
                    <img src="../assets/person.svg" alt="icon-user" />
                    <input type="text" required />
                    <label class="label-group">Username</label>
                </div>
                <!-- Email -->
                <div class="input-user">
                    <img src="../assets/mail.svg" alt="icon-mail" />
                    <input type="email" required />
                    <label class="label-group">E-mail</label>
                </div>
                <!-- Password -->
                <div class="input-user">
                    <img src="../assets/key.svg" alt="icon-mail" />
                    <input type="password" required />
                    <label class="label-group">password</label>
                </div>
                <!-- Confirm Password -->
                <div class="input-user">
                    <img src="../assets/key.svg" alt="icon-mail" />
                    <input type="password" required />
                    <label class="label-group">confirm password</label>
                </div>
            </form>
            <!-- button -->
            <button type="submit" class="button-signup">
                <span class="button-text-signup">
                    <a href="../index.php" class="url-signup">signUp</a>
                </span>
                <span class="button-icon">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </span>
            </button>

            <div class="border"></div>
            <!--footer-->
            <footer class="footer-login">
                <p>Already have an account</p>
                <a href="login.php">Login to your account</a>
            </footer>
        </div>
    </div>

    <!--👇 javascript code file 👇 -->
    <script src="../js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>