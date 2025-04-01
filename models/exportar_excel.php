<?php
// Incluir la conexión a la base de datos
require_once '../controllers/db.php';

// Nombre del archivo de salida
$filename = "BaseDeDatos_" . date("Y-m-d") . ".xls";

// Configurar las cabeceras para la descarga del archivo Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Usar la conexión correcta
$database = new Database('localhost', 'piia', 'root', '1234'); 
$conn = $database->getConnection();

// Verificar si la conexión es válida
if (!$conn) {
    die("Error en la conexión a la base de datos.");
}

// Obtener todas las tablas de la base de datos
$tablas_result = $conn->query("SHOW TABLES");
$tablas = [];
while ($row = $tablas_result->fetch(PDO::FETCH_NUM)) {
    $tablas[] = $row[0];
}

// Iniciar el contenido del archivo Excel con formato
echo '<html><head><meta charset="UTF-8"></head><body>';
echo '<table border="1" style="border-collapse: collapse;">';

// Aplicar estilo al encabezado
$styleHeader = 'style="background-color: #4CAF50; color: white; font-weight: bold; text-align: center;"';
$styleData = 'style="text-align: center;"';

// Exportar datos de cada tabla
foreach ($tablas as $tabla) {
    echo "<tr><td colspan='100' style='background-color: #ddd; font-weight: bold; text-align: center;'>Tabla: $tabla</td></tr>";
    
    // Obtener las columnas
    $result = $conn->query("SELECT * FROM $tabla");
    if ($result->rowCount() > 0) {
        $columnas = array_keys($result->fetch(PDO::FETCH_ASSOC));
        
        // Encabezados
        echo "<tr>";
        foreach ($columnas as $col) {
            echo "<th $styleHeader>$col</th>";
        }
        echo "</tr>";

        // Reiniciar el puntero y recorrer los datos
        $result = $conn->query("SELECT * FROM $tabla");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $dato) {
                echo "<td $styleData>" . htmlspecialchars($dato) . "</td>";
            }
            echo "</tr>";
        }
    }
}

// Cerrar tabla y HTML
echo '</table></body></html>';
exit();
?>
