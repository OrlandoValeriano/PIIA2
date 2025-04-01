<?php
if (isset($_POST['logout'])) {
    $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
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

<body class="light">
    <div class="main flex">
        <div class="password container flex">
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
                <!--=============== FORMULARIO RECUPERAR CONTRASEÑA ===============-->
                <form class="form flex">
                    <h1 class="pass__title"> Recuperar mi contraseña.</h1>
                    <p class="pass__text"> Proporcione la dirección de correo electrónico asociada con su cuenta para recuperar su contraseña.</p>
                    <div class="form-group">
                        <div class="input-container">
                            <i class="ri-mail-line"></i>
                            <input type="email" id="inputUser" class="txt"
                                placeholder="Correo electrónico">
                        </div>
                    </div>
                    <button class="btn" id="recuperar">Recuperar</button>
                    <a href="auth-login.php" class="btn__back btn" id="regresar">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>