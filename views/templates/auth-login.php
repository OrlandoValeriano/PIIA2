<?php
session_start();

// Verificar si hay un mensaje de error en la sesión
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error']; // Obtener el mensaje de error
    unset($_SESSION['error']); // Eliminar el mensaje de error de la sesión
} else {
    $errorMessage = null; // No hay error
}

// Recuperar el correo electrónico si existe en la cookie
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/icon/icon_piia.png">
    <title>PIIA - Login</title>
    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/simplebar.css">
    <link rel="stylesheet" href="css/feather.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!--=============== JAVASCRIPT ===============-->
    <script src="js/scrollreveal.min.js" defer></script>
    <script src="js/animation.js" defer></script>
    <script src="js/alerts.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>

<body class="light" data-error="<?php echo htmlspecialchars($errorMessage); ?>">
    <div class="main flex">
        <div class="login container flex">
            <!--=============== LOGO PIIA ===============-->
            <div class="login__logos">
                <div class="logo">
                    <img src="assets/images/PIIA_oscuro 1.png" alt="Imagen del PIIA" class="img_login_first">
                </div>
                <img src="assets/images/figure-login.png" alt="" class="figure__login">
            </div>
            <!--=============== LOGO TESCHI ===============-->
            <div class="login__form flex">
                <div class="login__logo">
                    <img src="assets/images/logo-teschi.png" alt="logo del tecnológico" class="img_login_second">
                </div>
                <!--=============== FORMULARIO LOGIN ===============-->
                <form class="form flex" action="../../models/auth.php" method="POST">
                    <div class="form-group">
                        <div class="input-container">
                            <i class="ri-user-line"></i>
                            <input type="email" id="inputUser" name="email" class="input_user txt"
                                placeholder="Correo Electrónico" required value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-container">
                            <i class="ri-lock-line"></i>
                            <input type="password" id="inputPassword" name="password"
                                class="input_pass txt" placeholder="Contraseña"
                                required>
                        </div>
                    </div>
                    <div class="login__check flex">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember_me" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                            <span class="checkbox-box">Recordar mis datos</span>
                        </label>
                        <div class="login__forgot flex">
                            <a href="recuperarPassword.php" class="">Olvidé mi contraseña</a>
                        </div>
                    </div>
                    <button type="submit" class="btn" id="registro">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>