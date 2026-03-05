<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio Vulnerable - SQL Injection</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
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
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.2) 0, transparent 50%);
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
            background: linear-gradient(to right, #ec4899, var(--primary));
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
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: #475569;
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
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .lab-badge i {
            margin-right: 0.375rem;
        }

    </style>
</head>
<body>

    <div class="login-container">
        <div class="header">
            <div class="lab-badge">
                🧪 Laboratorio Vulnerable
            </div>
            <h1>Acceso al Sistema</h1>
            <p>Introduce tus credenciales para continuar</p>
        </div>

        <form action="procesar_login.php" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem;">
            <a href="login_seguro.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">
                Ir a la Versión Segura (Ejercicio 4) &rarr;
            </a>
        </div>
    </div>

</body>
</html>
