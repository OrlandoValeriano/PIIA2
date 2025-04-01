<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8d7da;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .error-icon {
            font-size: 60px;
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="error-container">
    <span class="error-icon">🚫</span>
    <h1 class="mt-3 text-danger">Acceso Denegado</h1>
    <p>No tienes permisos para acceder a esta página.</p>
    <a href="../../index.php" class="btn btn-danger">Volver al Inicio</a>
</div>

</body>
</html>
