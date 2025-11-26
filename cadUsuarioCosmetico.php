    <?php
    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_loja";

    try {
        // Cria a conexão PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Define o modo de erro do PDO para lançar exceções
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro de conexão: " . $e->getMessage());
        die("Erro ao conectar com o banco de dados. Tente novamente mais tarde.");
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastrar Usuário - Ayla's Cosmetic</title>
        <style>
            /* Variáveis CSS - Mesmo padrão do anterior */
            :root {
                --primary-color: #e91e63;
                --primary-dark: #d81b60;
                --secondary-color: #ffc0cb;
                --secondary-light: #ffeef2;
                --danger-color: #dc3545;
                --danger-dark: #c82333;
                --success-color: #28a745;
                --background-light: #fafafa;
                --text-dark: #333;
                --text-light: #666;
                --border-color: #e0e0e0;
                --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                --radius: 12px;
                --transition: all 0.3s ease;
            }

            /* Reset e estilos base */
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
            }

            /* Menu Lateral - Mesmo estilo */
            .side-menu {
                height: 100vh;
                width: 280px;
                background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                position: fixed;
                left: -280px;
                top: 0;
                z-index: 1000;
                transition: var(--transition);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .side-menu.active {
                left: 0;
            }

            .side-menu-header {
                padding: 30px 20px;
                text-align: center;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .side-menu-header h3 {
                color: white;
                font-size: 1.2rem;
                font-weight: 600;
            }

            .side-menu-nav {
                padding: 20px 0;
            }

            .side-menu-nav a {
                display: flex;
                align-items: center;
                padding: 15px 25px;
                color: white;
                text-decoration: none;
                transition: var(--transition);
                border-left: 3px solid transparent;
            }

            .side-menu-nav a:hover {
                background: rgba(255, 255, 255, 0.1);
                border-left-color: var(--secondary-color);
                padding-left: 30px;
            }

            .side-menu-nav a i {
                margin-right: 12px;
                width: 20px;
                text-align: center;
            }

            .closebtn {
                position: absolute;
                top: 15px;
                right: 20px;
                font-size: 28px;
                color: white;
                cursor: pointer;
                transition: var(--transition);
            }

            .closebtn:hover {
                color: var(--secondary-color);
            }

            /* Conteúdo Principal */
            .main-content {
                flex: 1;
                padding: 30px;
                transition: var(--transition);
                margin-left: 0;
            }

            .main-content.menu-open {
                margin-left: 280px;
            }

            .top-bar {
                display: flex;
                align-items: center;
                margin-bottom: 30px;
                padding: 20px 0;
            }

            .menu-btn {
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 12px 16px;
                border-radius: var(--radius);
                cursor: pointer;
                font-size: 18px;
                margin-right: 20px;
                transition: var(--transition);
                box-shadow: var(--shadow);
            }

            .menu-btn:hover {
                background: var(--primary-dark);
                transform: translateY(-2px);
            }

            .page-title {
                color: var(--text-dark);
                font-size: 1.8rem;
                font-weight: 600;
            }

            /* Container do Formulário */
            .form-container {
                max-width: 700px;
                margin: 0 auto;
                background: white;
                padding: 40px;
                border-radius: var(--radius);
                box-shadow: var(--shadow);
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

            .form-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .form-header h2 {
                color: var(--primary-color);
                font-size: 2rem;
                margin-bottom: 10px;
            }

            .form-header p {
                color: var(--text-light);
                font-size: 1rem;
            }

            /* Layout de colunas para formulário */
            .form-columns {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            @media (max-width: 768px) {
                .form-columns {
                    grid-template-columns: 1fr;
                }
            }

            /* Grupos do Formulário */
            .form-group {
                margin-bottom: 25px;
            }

            .form-group.full-width {
                grid-column: 1 / -1;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: var(--text-dark);
                font-size: 0.95rem;
            }

            .form-group input, .form-group select {
        width: 100%;
        padding: 14px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 1rem;
        transition: var(--transition);
        background: var(--background-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
    }

            .form-group input:invalid:not(:focus):not(:placeholder-shown) {
                border-color: var(--danger-color);
            }
            

            /* Força de senha */
            .password-strength {
                margin-top: 8px;
                height: 4px;
                border-radius: 2px;
                background: var(--border-color);
                overflow: hidden;
            }

            .password-strength-bar {
                height: 100%;
                width: 0%;
                transition: var(--transition);
            }

            .strength-weak { background: var(--danger-color); width: 25%; }
            .strength-medium { background: #ff9800; width: 50%; }
            .strength-strong { background: var(--success-color); width: 75%; }
            .strength-very-strong { background: #4caf50; width: 100%; }

            .password-strength-text {
                font-size: 0.8rem;
                margin-top: 4px;
                color: var(--text-light);
            }

            /* Botões */
            .form-buttons {
                display: flex;
                gap: 15px;
                margin-top: 30px;
            }

            .btn {
                flex: 1;
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

            .btn-secondary {
                background: var(--text-light);
                color: white;
            }

            .btn-secondary:hover {
                background: var(--text-dark);
                transform: translateY(-2px);
            }

            /* Estados de loading e feedback */
            .loading {
                opacity: 0.7;
                pointer-events: none;
            }

            .form-message {
                padding: 12px;
                border-radius: var(--radius);
                margin-bottom: 20px;
                text-align: center;
                font-weight: 500;
            }

            .form-message.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .form-message.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            /* Responsividade */
            @media (max-width: 768px) {
                .form-container {
                    padding: 25px;
                    margin: 20px;
                }

                .form-buttons {
                    flex-direction: column;
                }

                .side-menu {
                    width: 100%;
                    left: -100%;
                }

                .main-content.menu-open {
                    margin-left: 0;
                }

                .top-bar {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 15px;
                }

                .menu-btn {
                    margin-right: 0;
                }
            }

            /* Informações de ajuda */
            .form-help {
                font-size: 0.8rem;
                color: var(--text-light);
                margin-top: 5px;
            }

            .required-field::after {
                content: " *";
                color: var(--danger-color);
            }
        </style>
    </head>
    <body>

        <!-- Menu Lateral -->
        <div id="sideMenu" class="side-menu">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="side-menu-header">
        <h3>Ayla's Cosmetic</h3>
    </div>
    <nav class="side-menu-nav">
        <a href="login.php"> 🔐 Fazer Login</a>
        <a href="cadUsuarioCosmetico.php"> 👤 Cadastrar-se</a>
        <a href="lojacosmeticos.php" onclick="alert('Faça login primeiro para acessar a loja'); return false;"> 🏪 Loja</a>
    </nav>
</div>
            </nav>
        </div>

        <!-- Conteúdo Principal -->
        <div class="main-content" id="mainContent">
            <div class="top-bar">
                <button class="menu-btn" onclick="openNav()">☰ Menu</button>
                <h1 class="page-title">Cadastrar Usuário</h1>
            </div>

            <div class="form-container">
                <div class="form-header">
                    <h2>Novo Usuário</h2>
                    <p>Preencha os dados para cadastrar um novo usuário no sistema</p>
                </div>

                <form action="processaUsuario.php" method="POST" id="usuarioForm">
                <input type="hidden" name="acao" value="salvar">
                    <div class="form-columns">
                        <!-- Dados Pessoais -->
                        <div class="form-group full-width">
                            <label for="nome" class="required-field">Nome Completo</label>
                            <input type="text" id="nome" name="nome" required 
                                placeholder="Ex: Maria Silva Santos" maxlength="100"
                                pattern="[A-Za-zÀ-ÿ\s]{2,}" title="Digite um nome válido (mínimo 2 caracteres)">
                        </div>

                        <div class="form-group">
                            <label for="email" class="required-field">E-mail</label>
                            <input type="email" id="email" name="email" required 
                                placeholder="exemplo@email.com" maxlength="100">
                        </div>

                        <div class="form-group">
                            <label for="fone" class="required-field">Telefone</label>
                            <input type="tel" id="fone" name="fone" required 
                                placeholder="(11) 99999-9999" maxlength="15"
                                oninput="formatarTelefone(this)">
                        </div>

                        <div class="form-group">
                            <label for="senha" class="required-field">Senha</label>
                            <input type="password" id="senha" name="senha" required 
                                placeholder="Mínimo 6 caracteres" minlength="6"
                                oninput="verificarForcaSenha(this.value)">
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="password-strength-text" id="passwordStrengthText"></div>
                        </div>

                        <!-- Endereço -->
                        <div class="form-group">
                            <label for="cep" class="required-field">CEP</label>
                            <input type="text" id="cep" name="cep" required 
                                placeholder="00000-000" maxlength="9"
                                oninput="formatarCEP(this)"
                                onblur="buscarEndereco(this.value)">
                        </div>

                        <div class="form-group">
                            <label for="numero" class="required-field">Número</label>
                            <input type="text" id="numero" name="numero" required 
                                placeholder="123" maxlength="10">
                        </div>

                        <div class="form-group full-width">
                            <label for="rua" class="required-field">Rua</label>
                            <input type="text" id="rua" name="rua" required 
                                placeholder="Av. Principal" maxlength="100">
                        </div>

                        <div class="form-group full-width">
                            <label for="bairro" class="required-field">Bairro</label>
                            <input type="text" id="bairro" name="bairro" required 
                                placeholder="Centro" maxlength="50">
                        </div>

                        <!-- Já existe no seu código, apenas verifique se está correto -->
                        <div class="form-group full-width">
                            <label for="complemento">Complemento (Opcional)</label>
                            <input type="text" id="complemento" name="complemento" 
                                placeholder="Apartamento 101, Bloco B..." maxlength="100">
                            <div class="form-help">Casa, apartamento, sala, etc.</div>
                        </div>
                    <!-- ADICIONE ESTES CAMPOS -->

                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" 
                            placeholder="000.000.000-00" maxlength="14"
                            oninput="formatarCPF(this)">
                    </div>

                    <div class="form-group">
                        <label for="datanasc">Data de Nascimento</label>
                        <input type="date" id="datanasc" name="datanasc">
                    </div>

                    <div class="form-group">
                        <label for="tipo_usuario" class="required-field">Tipo de Usuário</label>
                        <select id="tipo_usuario" name="tipo_usuario" required>
                            <option value="cliente">Cliente</option>
                            <option value="funcionario">Funcionário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

    <!-- FIM DOS CAMPOS ADICIONADOS -->

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary"> Cadastrar Usuário</button>
                        <a href="login.php" class="btn btn-secondary"> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Funções do menu
            function openNav() {
                document.getElementById("sideMenu").classList.add("active");
                document.getElementById("mainContent").classList.add("menu-open");
            }

            function closeNav() {
                document.getElementById("sideMenu").classList.remove("active");
                document.getElementById("mainContent").classList.remove("menu-open");
            }

            // Formatação de telefone
            function formatarTelefone(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    if (value.length <= 2) {
                        value = value.replace(/^(\d{0,2})/, '($1');
                    } else if (value.length <= 6) {
                        value = value.replace(/^(\d{2})(\d{0,4})/, '($1) $2');
                    } else if (value.length <= 10) {
                        value = value.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                    } else {
                        value = value.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                    }
                    input.value = value;
                }
            }

            // Formatação de CEP
            function formatarCEP(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length <= 8) {
                    if (value.length > 5) {
                        value = value.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
                    }
                    input.value = value;
                }
            }

            // Buscar endereço via CEP
            function buscarEndereco(cep) {
                cep = cep.replace(/\D/g, '');
                
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('rua').value = data.logradouro || '';
                                document.getElementById('bairro').value = data.bairro || '';
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar CEP:', error);
                        });
                }
            }

            // Verificar força da senha
            function verificarForcaSenha(senha) {
                const bar = document.getElementById('passwordStrengthBar');
                const text = document.getElementById('passwordStrengthText');
                
                let forca = 0;
                let texto = '';
                
                if (senha.length >= 6) forca += 25;
                if (senha.match(/[a-z]/) && senha.match(/[A-Z]/)) forca += 25;
                if (senha.match(/\d/)) forca += 25;
                if (senha.match(/[^a-zA-Z\d]/)) forca += 25;
                
                // Atualizar barra e texto
                bar.className = 'password-strength-bar';
                if (forca <= 25) {
                    bar.classList.add('strength-weak');
                    texto = 'Senha fraca';
                } else if (forca <= 50) {
                    bar.classList.add('strength-medium');
                    texto = 'Senha média';
                } else if (forca <= 75) {
                    bar.classList.add('strength-strong');
                    texto = 'Senha forte';
                } else {
                    bar.classList.add('strength-very-strong');
                    texto = 'Senha muito forte';
                }
                
                text.textContent = texto;
            }

            // Validação do formulário
            document.getElementById('usuarioForm').addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const senha = document.getElementById('senha').value;
                const cep = document.getElementById('cep').value;
                
                // Validação de email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Por favor, insira um e-mail válido.');
                    return;
                }
                
                // Validação de senha
                if (senha.length < 6) {
                    e.preventDefault();
                    alert('A senha deve ter pelo menos 6 caracteres.');
                    return;
                }
                
                // Validação de CEP
                const cepRegex = /^\d{5}-\d{3}$/;
                if (!cepRegex.test(cep)) {
                    e.preventDefault();
                    alert('Por favor, insira um CEP válido (00000-000).');
                    return;
                }

                // Formatação de CPF
                function formatarCPF(input) {
                    let value = input.value.replace(/\D/g, '');
                    if (value.length <= 11) {
                        if (value.length <= 3) {
                            value = value.replace(/^(\d{0,3})/, '$1');
                        } else if (value.length <= 6) {
                            value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
                        } else if (value.length <= 9) {
                            value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                        } else {
                            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                        }
                        input.value = value;
                    }
                }
                
                // Adiciona estado de loading
                this.classList.add('loading');
            });

            // Fecha menu ao clicar fora
            document.addEventListener('click', function(e) {
                const sideMenu = document.getElementById('sideMenu');
                const menuBtn = document.querySelector('.menu-btn');
                
                if (!sideMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                    closeNav();
                }
            });

            // Fecha menu com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeNav();
                }
            });

            // Foco no primeiro campo
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('nome').focus();
            });
            
        </script>
    </body>
    </html>