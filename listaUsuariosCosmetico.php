<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários - Ayla's Cosmetic</title>
    <style>
        /* Variáveis CSS - Mesmo padrão */
        :root {
            --primary-color: #e91e63;
            --primary-dark: #d81b60;
            --secondary-color: #ffc0cb;
            --secondary-light: #ffeef2;
            --danger-color: #dc3545;
            --danger-dark: #c82333;
            --success-color: #28a745;
            --warning-color: #ffc107;
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

        /* Container Principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
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

        /* Cabeçalho da Lista */
        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .list-title {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
        }

        .list-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Barra de Pesquisa */
        .search-container {
            position: relative;
            min-width: 300px;
        }

        .search-container input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--background-light);
        }

        .search-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* Botões */
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: var(--radius);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-align: center;
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

        /* Tabela */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:first-child {
            border-top-left-radius: var(--radius);
        }

        th:last-child {
            border-top-right-radius: var(--radius);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:hover {
            background: var(--secondary-light);
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Informações do usuário */
        .user-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .user-email {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .user-phone {
            font-weight: 500;
            color: var(--text-dark);
        }

        .user-address {
            color: var(--text-light);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .user-type {
            background: var(--secondary-light);
            color: var(--primary-dark);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .user-type.admin {
            background: #e3f2fd;
            color: #1976d2;
        }

        .user-type.funcionario {
            background: #fff3e0;
            color: #f57c00;
        }

        /* Ações */
        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-edit {
            background: var(--warning-color);
            color: var(--text-dark);
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background: var(--danger-dark);
            transform: translateY(-1px);
        }

        /* Estados vazios */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        /* Mensagens de feedback */
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            .list-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container {
                min-width: 100%;
            }

            .list-actions {
                justify-content: center;
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

            th, td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }

            .actions {
                flex-direction: column;
                gap: 5px;
            }

            .btn-action {
                padding: 6px 10px;
                font-size: 0.75rem;
            }
        }

        /* Contador de resultados */
        .results-count {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 15px;
            text-align: right;
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
        <a href="lojacosmeticos.php">🏪 Loja</a>
        <a href="cadUsuarioCosmetico.php">👤 Adicionar Usuário</a>
        <a href="listaUsuariosCosmetico.php">📋 Listar Usuários</a>
        <a href="cadProdutoCosmetico.php">📦 Adicionar Produto</a>
        <a href="listaProdutosCosmetico.php">📄 Listar Produtos</a>
        <a href="logout.php" style="color: #ff6b6b; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
            🚪 Sair do Sistema
        </a>
    </nav>
</div>

    <!-- Conteúdo Principal -->
    <div class="main-content" id="mainContent">
        <div class="top-bar">
            <button class="menu-btn" onclick="openNav()">☰ Menu</button>
            <h1 class="page-title">Usuários</h1>
        </div>

        <div class="container">
            <!-- Mensagens de Feedback -->
            <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                <div class="alert alert-success">
                     Usuário excluído com sucesso!
                </div>
            <?php endif; ?>

            <div class="list-header">
                <h2 class="list-title"> Lista de Usuários</h2>
                <div class="list-actions">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Pesquisar usuários..." onkeyup="filterUsuarios()">
                        <span class="search-icon">🔍</span>
                    </div>
                    <a href="cadUsuarioCosmetico.php" class="btn btn-primary"> Novo Usuário</a>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Usuário</th>
                            <th width="150">Telefone</th>
                            <th width="150">Tipo</th>
                            <th width="120" style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTableBody">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "db_loja";

                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Processa a exclusão
                            if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
                                $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
                                if ($id !== false) {
                                    $sql_delete = "DELETE FROM tb_usuario WHERE id_usuario = :id";
                                    $stmt_delete = $conn->prepare($sql_delete);
                                    $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
                                    $stmt_delete->execute();
                                    header("Location: listaUsuariosCosmetico.php?success=deleted");
                                    exit();
                                }
                            }

                            // Busca todos os usuários
                            $sql_select = "SELECT * FROM tb_usuario ORDER BY id_usuario DESC";
                            $stmt_select = $conn->prepare($sql_select);
                            $stmt_select->execute();
                            $result = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                        } catch (PDOException $e) {
                            error_log("Erro de conexão ou consulta: " . $e->getMessage());
                            die("Erro ao conectar com o banco de dados. Tente novamente mais tarde.");
                        }
                        ?>

                        <?php if ($result && count($result) > 0): ?>
                            <?php foreach ($result as $row): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($row['id_usuario']); ?></strong></td>
                                    <td>
                                        <div class="user-name"><?php echo htmlspecialchars($row['nome_usuario']); ?></div>
                                        <div class="user-email"><?php echo htmlspecialchars($row['email_usuario']); ?></div>
                                        <div class="user-address" title="<?php echo htmlspecialchars($row['rua_usuario'] . ', ' . $row['numero_usuario'] . ', ' . $row['bairro_usuario'] . ' - CEP: ' . $row['cep_usuario']); ?>">
                                            <?php echo htmlspecialchars($row['rua_usuario'] . ', ' . $row['numero_usuario']); ?>
                                        </div>
                                    </td>
                                    <td class="user-phone"><?php echo htmlspecialchars($row['fone_usuario']); ?></td>
                                    <td>
                                        <?php 
                                        $tipo_class = '';
                                        $tipo_text = 'Cliente';
                                        if (isset($row['tipo_usuario'])) {
                                            if ($row['tipo_usuario'] === 'admin') {
                                                $tipo_class = 'admin';
                                                $tipo_text = 'Administrador';
                                            } elseif ($row['tipo_usuario'] === 'funcionario') {
                                                $tipo_class = 'funcionario';
                                                $tipo_text = 'Funcionário';
                                            }
                                        }
                                        ?>
                                        <span class="user-type <?php echo $tipo_class; ?>">
                                            <?php echo $tipo_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="editarUsuarioCosmetico.php?id=<?php echo htmlspecialchars($row['id_usuario']); ?>" 
                                               class="btn-action btn-edit" title="Editar usuário"> Editar</a>
                                            <a href="?delete=<?php echo htmlspecialchars($row['id_usuario']); ?>" 
                                               class="btn-action btn-delete" 
                                               title="Excluir usuário"
                                               onclick="return confirm('Tem certeza que deseja excluir o usuário \'<?php echo addslashes($row['nome_usuario']); ?>\'?')"> Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div></div>
                                        <h3>Nenhum usuário cadastrado</h3>
                                        <p>Comece adicionando seu primeiro usuário ao sistema.</p>
                                        <a href="cadUsuarioCosmetico.php" class="btn btn-primary" style="margin-top: 15px;">
                                             Adicionar Primeiro Usuário
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="results-count" id="resultsCount">
                <?php echo count($result); ?> usuário(s) encontrado(s)
            </div>
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

        // Filtro de usuários
        function filterUsuarios() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const tableBody = document.getElementById("usuariosTableBody");
            const rows = tableBody.getElementsByTagName("tr");
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const userName = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const userEmail = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                const userPhone = row.querySelector('.user-phone')?.textContent.toLowerCase() || '';
                const userType = row.querySelector('.user-type')?.textContent.toLowerCase() || '';
                
                if (userName.includes(filter) || userEmail.includes(filter) || userPhone.includes(filter) || userType.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            }

            // Atualiza contador
            document.getElementById('resultsCount').textContent = visibleCount + ' usuário(s) encontrado(s)';
        }

        // Fecha menu ao clicar fora
        document.addEventListener('click', function(e) {
            const sideMenu = document.getElementById("sideMenu");
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

        // Auto-foco na pesquisa
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').focus();
        });
    </script>
</body>
</html>