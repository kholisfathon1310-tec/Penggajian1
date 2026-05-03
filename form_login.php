<?php
if (isset($_SESSION['message'])) {
    echo '<div style="padding: 20px; background-color: #28a745; color: white; text-align: center; font-size: 20px;">
            ' . $_SESSION['message'] . '
          </div>';
    
    // Hapus pesan setelah ditampilkan
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/login.jpeg');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            margin-left: 53%;
            margin-top: 4%;
        }

        .login-card {
            border: 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .login-header h1 {
            color: black;
        }

        .login-header h1 strong {
            color:  #6F8FAF;
        }

        .btn-login {
            background-color: black;
            color: #ffffff;
        }

        .text-center a {
            display: block;
            margin-top: 10px;
        }

        /* Styling for Aplikasi Penggajian text */
        .app-name {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 40px;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Aplikasi Penggajian text on the top left with background -->
    <div class="app-name">
        Aplikasi <strong>SIGAP</strong>
    </div>

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-md-5 login-container">
                <div class="card o-hidden login-card my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="col-12 mx-0 px-0">
                            <div class="p-5">
                                <div class="text-center login-header">
                                    <h1 class="h4 mb-4">LOGIN</h1>
                                </div>
                                <form action="aksi_login.php" method="post" class="user">
                                    <div class="form-group">
                                        <input class="form-control form-control-user" type="NIP" name="NIP" placeholder="NIP" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control form-control-user" type="password" name="password" placeholder="Password" required>
                                    </div>
                                    <button class="btn btn-user btn-block btn-login" type="submit">Login</button>
                                </form>
                                <div class="text-center">
                                    <a class="small" href="registrasi.php">Belum punya akun?</a>
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
