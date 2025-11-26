<?php
session_start();

// Se já estiver logado, redireciona para a loja
if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true) {
    header("Location: lojacosmeticos.php");
    exit;
}

// Processamento do login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Conexão com o banco
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_loja";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Busca usuário pelo email
        $sql = "SELECT id_usuario, nome_usuario, email_usuario, senha_usuario, tipo_usuario FROM tb_usuario WHERE email_usuario = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
            // Login bem-sucedido
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome_usuario'];
            $_SESSION['usuario_email'] = $usuario['email_usuario'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
            
            header("Location: lojacosmeticos.php");
            exit;
        } else {
            $erro_login = "E-mail ou senha incorretos!";
        }
    } catch(PDOException $e) {
        $erro_login = "Erro ao conectar com o banco de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ayla's Cosmetic</title>
    <style>
        :root {
            --primary-color: #e91e63;
            --primary-dark: #d81b60;
            --secondary-color: #ffc0cb;
            --secondary-light: #ffeef2;
            --danger-color: #dc3545;
            --success-color: #28a745;
            --background-light: #fafafa;
            --text-dark: #333;
            --text-light: #666;
            --border-color: #e0e0e0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--secondary-light) 0%, #ffffff 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .login-header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .login-header p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--background-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.3);
        }

        .alert {
            padding: 15px 20px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .login-footer p {
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .btn-secondary {
            background: var(--text-light);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--text-dark);
            transform: translateY(-2px);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 1.2rem;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
            }
            
            .login-header h1 {
                font-size: 1.8rem;
            }
        }

        .loading {
            display: none;
        }

        .loading.active {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">💄</div>
            <h1>Ayla's Cosmetic</h1>
            <p>Faça login para acessar o sistema</p>
        </div>

        <div class="login-card">
            <!-- Mensagens de erro -->
            <?php if (isset($erro_login)): ?>
                <div class="alert alert-error">
                    ⚠️ <?php echo $erro_login; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="seu@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="password-container">
                        <input type="password" id="senha" name="senha" required 
                               placeholder="Sua senha" minlength="6">
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span class="loading" id="loading">⏳</span>
                    <span id="btnText"> Entrar</span>
                </button>
            </form>

            <div class="login-footer">
                <p>Não tem uma conta?</p>
                <a href="cadUsuarioCosmetico.php" class="btn btn-secondary"> Cadastrar-se</a>
            </div>
        </div>
    </div>

    <script>
        // Mostrar/ocultar senha
        function togglePassword() {
            const passwordInput = document.getElementById('senha');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🔒';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Loading no botão de login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');
            const btnText = document.getElementById('btnText');
            
            btn.disabled = true;
            loading.classList.add('active');
            btnText.textContent = ' Entrando...';
        });

        // Foco no campo de email
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Efeito de digitação no placeholder (opcional)
        const emailInput = document.getElementById('email');
        const placeholders = ['exemplo@email.com', 'seu.nome@provedor.com', 'usuario@dominio.com'];
        let currentPlaceholder = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;

        function typePlaceholder() {
            const currentText = placeholders[currentPlaceholder];
            
            if (isDeleting) {
                emailInput.placeholder = currentText.substring(0, charIndex - 1);
                charIndex--;
                typingSpeed = 50;
            } else {
                emailInput.placeholder = currentText.substring(0, charIndex + 1);
                charIndex++;
                typingSpeed = 100;
            }

            if (!isDeleting && charIndex === currentText.length) {
                isDeleting = true;
                typingSpeed = 1000; // Pausa no final
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
                typingSpeed = 500; // Pausa antes de começar novo
            }

            setTimeout(typePlaceholder, typingSpeed);
        }

        // Iniciar efeito de digitação (opcional - remova se não quiser)
        // typePlaceholder();
    </script>
</body>
</html>