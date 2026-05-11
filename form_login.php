<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Page</title>

<link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>

body {
    background-image: url('img/login.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
}

.login-container {
    margin-left: 53%;
    margin-top: 4%;
}

.login-card {
    border: 0;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 10px;
}

.login-header h1 {
    color: black;
    font-weight: bold;
}

.login-header h1 strong {
    color: #6F8FAF;
}

.btn-login {
    background-color: black;
    color: #ffffff;
    border-radius: 50px;
}

.btn-login:hover {
    background-color: #333;
    color: white;
}

.text-center a {
    display: block;
    margin-top: 10px;
}

.app-name {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 40px;
    font-weight: bold;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    background-color: rgba(0, 0, 0, 0.5);
    padding: 10px 20px;
    border-radius: 8px;
}

/* ALERT */
.custom-alert {
    width: 100%;
    padding: 15px;
    background-color: #28a745;
    color: white;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}

</style>

</head>

<body>

<!-- LOGO -->
<div class="app-name">
    Aplikasi <strong>SIGAP</strong>
</div>

<div class="container">

    <!-- ALERT -->
    <?php if (isset($_SESSION['message'])): ?>

        <div class="custom-alert">

            <?= htmlspecialchars($_SESSION['message']); ?>

        </div>

        <?php unset($_SESSION['message']); ?>

    <?php endif; ?>

    <!-- LOGIN -->
    <div class="row justify-content-center">

        <div class="col-md-5 login-container">

            <div class="card o-hidden login-card my-5">

                <div class="card-body p-0">

                    <div class="col-12 mx-0 px-0">

                        <div class="p-5">

                            <!-- HEADER -->
                            <div class="text-center login-header">

                                <h1 class="h4 mb-4">

                                    LOGIN

                                </h1>

                            </div>

                            <!-- FORM -->
                            <form action="aksi_login.php"
                                  method="POST"
                                  class="user">

                                <!-- NIP -->
                                <div class="form-group">

                                    <input class="form-control form-control-user"
                                           type="text"
                                           name="NIP"
                                           placeholder="NIP"
                                           required>

                                </div>

                                <!-- PASSWORD -->
                                <div class="form-group">

                                    <input class="form-control form-control-user"
                                           type="password"
                                           name="password"
                                           placeholder="Password"
                                           required>

                                </div>

                                <!-- BUTTON -->
                                <button class="btn btn-user btn-block btn-login"
                                        type="submit">

                                    Login

                                </button>

                            </form>

                            <!-- REGISTER -->
                            <div class="text-center">

                                <a class="small"
                                   href="registrasi.php">

                                    Belum punya akun?

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
