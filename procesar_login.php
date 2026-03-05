<?php
// procesar_login.php

// 1. Conexión a la base de datos
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";

$database = getenv('DB_NAME') ?: "login_seguridad";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("<h2 style='color:red; font-family:sans-serif; text-align:center;'>Error de Conexión: " . $conn->connect_error . "</h2>");
}

// 2. Extracción de variables POST
$usuario_input = $_POST['username'] ?? '';
$password_input = $_POST['password'] ?? '';

// 3.  VULNERABLE: Concatenación directa de strings (SQL INJECTION)
$sql = "SELECT * FROM usuarios WHERE username = '" . $usuario_input . "' AND password = '" . $password_input . "'";

// 4. Ejecutar consulta
$resultado = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Login</title>
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #1e293b;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.5);
            border: 1px solid #334155;
        }
        .success { color: #22c55e; }
        .error { color: #ef4444; }
        .sql-query {
            background-color: #000;
            color: #eab308;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: monospace;
            overflow-x: auto;
            margin: 1rem 0;
            border: 1px solid #334155;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            background-color: #0f172a;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #334155;
        }
        th {
            background-color: #334155;
            color: #f8fafc;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #1e293b;
        }
        .btn-back {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background-color: #334155;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #475569;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Análisis de la Petición</h2>
    
    <p><strong>SQL Ejecutada:</strong></p>
    <div class="sql-query"><?php echo htmlspecialchars($sql); ?></div>

    <?php
if ($conn->error) {
    // En caso de error de sintaxis SQL (muy común inyectando)
    echo "<h2 class='error'>❌ Error SQL: " . htmlspecialchars($conn->error) . "</h2>";
}
else if ($resultado && $resultado->num_rows > 0) {
    // Encontramos resultados! (Login correcto o volcado exitoso)
    echo "<h2 class='success'>✅ Consulta Ejecutada Exitosamente</h2>";
    echo "<p>Se han encontrado <b>" . $resultado->num_rows . "</b> registros:</p>";

    // Generamos la tabla para mostrar los resultados
    echo "<table>";
    echo "<thead><tr>";

    // Obtener nombres de columnas dinámicamente
    $campos = $resultado->fetch_fields();
    foreach ($campos as $campo) {
        echo "<th>" . htmlspecialchars($campo->name) . "</th>";
    }
    echo "</tr></thead><tbody>";

    // Volver a posición 0 antes de recorrer
    $resultado->data_seek(0);

    // Filas de datos
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        foreach ($fila as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }
        echo "</tr>";
    }

    echo "</tbody></table>";

    // Obtener el primer registro para dar la bienvenida en caso de login simple
    $resultado->data_seek(0);
    $primer_usuario = $resultado->fetch_assoc();
    if (isset($primer_usuario['username'])) {
        echo "<h3 style='margin-top: 2rem; color: #38bdf8;'>¡Bienvenido, " . htmlspecialchars($primer_usuario['username']) . "!</h3>";
    }

}
else {
    // Cero resultados
    echo "<h2 class='error'>❌ Login Incorrecto</h2>";
    echo "<p>No se ha encontrado ningún usuario con esos datos.</p>";
}

$conn->close();
?>

    <a href="index.php" class="btn-back">← Volver al Login</a>
</div>

</body>
</html>
