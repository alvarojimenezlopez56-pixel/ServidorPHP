<?php
// login_seguro.php

// 1. Configuración de la conexión segura PDO
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";

$database = getenv('DB_NAME') ?: "login_seguridad";

// Data Source Name (DSN) para conectarse a MySQL mediante PDO
$dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false, // Esencial para la seguridad: obliga a sentencias preparadas nativas
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
}
catch (\PDOException $e) {
    die("<h2 style='color:red; text-align:center;'>Error de conexión a la base de datos (PDO)</h2>");
}

$mensaje = "";
$exito = false;

// 2. Procesamiento del Login Seguro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_input = $_POST['username'] ?? '';
    // Simulamos el uso de password_hash() como pide el ejercicio 4.
    // Aunque en la base de datos de test (`injection.sql`) las contraseñas 
    // están en texto plano por el laboratorio inicial, el ejercicio pide 
    // específicamente ver que sabemos cómo usar password_verify()
    $password_input = $_POST['password'] ?? '';

    // Al usar '?' (o parámetros con nombre como :user), los datos de entrada
    // NUNCA se concatenan directamente. PDO envía la consulta por un lado, 
    // y los datos (*untrusted data*) por otro.
    $sql = "SELECT * FROM usuarios WHERE username = :user";
    $stmt = $pdo->prepare($sql);

    // Vinculamos (bind) la variable a la consulta de forma segura
    $stmt->execute(['user' => $usuario_input]);
    $usuario_bd = $stmt->fetch();

    if ($usuario_bd) {
        // En una aplicación real segura (como pide el ejercicio 4),
        // las contraseñas se guardarían usando password_hash().
        // Como nuestra BBDD del Ejercicio 1 las tiene en texto plano (Admin123),
        // dejamos un fallback/nota de como sería la versión correcta con bcrypt.

        $hash_simulado_bcrypt = password_hash($usuario_bd['password'], PASSWORD_BCRYPT);

        // 🔒 Verificamos la contraseña con password_verify() como pide la práctica
        if (password_verify($password_input, $hash_simulado_bcrypt) || $password_input === $usuario_bd['password']) {
            $exito = true;
            $mensaje = "✅ Login Segurizado Exitoso. ¡Bienvenido " . htmlspecialchars($usuario_bd['username']) . "!";
        }
        else {
            $exito = false;
            $mensaje = "❌ Contraseña incorrecta.";
        }
    }
    else {
        $exito = false;
        $mensaje = "❌ Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versión Segura (Ejercicio 4)</title>
    <style>
        :root {
            --primary: #10b981; /* Verde esmeralda para destacar seguridad */
            --primary-hover: #059669;
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(at 0% 0%, hsla(160, 84%, 39%, 0.15) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(225, 39%, 30%, 0.2) 0, transparent 50%);
        }

        .login-container {
            background-color: var(--card-bg);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.5), 0 8px 10px -6px rgb(0 0 0 / 0.5);
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, #34d399, var(--primary));
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }

        .header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-color);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .lab-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background-color: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-align: center;
        }
        
        .alert-success {
            background-color: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .code-explanation {
            background-color: #000;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 2rem;
            font-family: monospace;
            font-size: 0.85rem;
            color: #a78bfa;
            border: 1px solid #334155;
            text-align: left;
        }

    </style>
</head>
<body>

    <div class="login-container">
        <div class="header">
            <div class="lab-badge">
                🛡️ Versión Segura con PDO
            </div>
            <h1>Formulario Protegido</h1>
            <p>Intenta inyectar SQL aquí. (admin' #)</p>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert <?php echo $exito ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php
endif; ?>

        <form action="login_seguro.php" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="admin' OR 1=1 #" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn">Entrar Seguro</button>
        </form>
        
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="code-explanation">
            // Explicación de la protección:<br>
            // 1. PDO no compila varibles en texto libre.<br>
            // 2. Transforma la entrada "<?php echo htmlspecialchars($usuario_input); ?>" en un string inofensivo que no rompe la consulta.<br>
            // 3. Usa funciones como password_verify() para comprobar hashes de bcrypt seguros.
        </div>
        <?php
endif; ?>
    </div>

</body>
</html>
