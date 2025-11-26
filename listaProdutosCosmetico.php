<?php
session_start();

// 1. INCLUIR CONEXÃO CENTRALIZADA: Substitui as 10 linhas de definição da conexão
require_once 'conexao.php'; 

try {
    // 2. BUSCA TODOS OS PRODUTOS
    $sql_select = "SELECT * FROM tb_produto ORDER BY id_produto DESC";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute();
    $result = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro de conexão ou consulta: " . $e->getMessage());
    // Garante que o usuário veja uma mensagem de erro em caso de falha grave
    $_SESSION['erro'] = "Erro ao carregar lista: " . $e->getMessage();
    $result = []; // Garante que $result seja um array vazio para não quebrar o foreach
}

// Mapeamento de categorias (mantido)
$categorias = [
    1 => 'Maquiagem',
    2 => 'Perfumaria', 
    3 => 'Skincare',
    4 => 'Cabelo',
    5 => 'Corpo e Banho',
    6 => 'Acessórios'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos - Ayla's Cosmetic</title>
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

        /* Imagens dos produtos */
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        .no-image {
            width: 60px;
            height: 60px;
            background: var(--background-light);
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 0.8rem;
            text-align: center;
        }

        /* Informações do produto */
        .product-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .product-description {
            color: var(--text-light);
            font-size: 0.85rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .product-category {
            background: var(--secondary-light);
            color: var(--primary-dark);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
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
            <h1 class="page-title">Produtos</h1>
        </div>

        <div class="container">
            <!-- Mensagens de Feedback -->
            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success">
                    ✅ <?= htmlspecialchars($_SESSION['sucesso']) ?>
                </div>
                <?php unset($_SESSION['sucesso']); // Limpa a mensagem após exibir ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-error">
                  ❌ <?= htmlspecialchars($_SESSION['erro']) ?>
               </div>
                <?php unset($_SESSION['erro']); // Limpa a mensagem após exibir ?>
            <?php endif; ?>

            <div class="list-header">
                <h2 class="list-title"> Lista de Produtos</h2>
                <div class="list-actions">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Pesquisar produtos..." onkeyup="filterProducts()">
                        <span class="search-icon"></span>
                    </div>
                    <a href="cadProdutoCosmetico.php" class="btn btn-primary"> Novo Produto</a>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th width="80">Imagem</th>
                            <th>Produto</th>
                            <th width="120">Valor</th>
                            <th width="120">Categoria</th>
                            <th width="150" style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="produtosTableBody">
                        <?php if ($result && count($result) > 0): ?>
                            <?php foreach ($result as $row): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($row['id_produto']); ?></strong></td>
                                    <td>
                                        <?php if (!empty($row['imagem'])): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagem']); ?>" 
                                                 alt="<?php echo htmlspecialchars($row['nome_produto']); ?>" 
                                                 class="product-image">
                                        <?php else: ?>
                                            <div class="no-image">Sem imagem</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="product-name"><?php echo htmlspecialchars($row['nome_produto']); ?></div>
                                        <div class="product-description" title="<?php echo htmlspecialchars($row['descricao_produto']); ?>">
                                            <?php echo htmlspecialchars($row['descricao_produto']); ?>
                                        </div>
                                    </td>
                                    <td class="product-price">R$ <?php echo number_format($row['valor_produto'], 2, ',', '.'); ?></td>
                                    <td>
                                        <span class="product-category">
                                            <?php echo isset($categorias[$row['categoria_produto']]) ? $categorias[$row['categoria_produto']] : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="cadProdutoCosmetico.php?id=<?php echo htmlspecialchars($row['id_produto']); ?>" 
                                               class="btn-action btn-edit" title="Editar produto"> Editar</a>
                                               <a href="processaProdutoCosmetico.php?acao=excluir&id=<?php echo htmlspecialchars($row['id_produto']); ?>" 
                                               class="btn-action btn-delete" 
                                               title="Excluir produto"
                                               onclick="return confirm('Tem certeza que deseja excluir o produto \'<?php echo addslashes($row['nome_produto']); ?>\'?')"> Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div></div>
                                        <h3>Nenhum produto cadastrado</h3>
                                        <p>Comece adicionando seu primeiro produto ao sistema.</p>
                                        <a href="cadProdutoCosmetico.php" class="btn btn-primary" style="margin-top: 15px;">
                                             Adicionar Primeiro Produto
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="results-count" id="resultsCount">
                <?php echo count($result); ?> produto(s) encontrado(s)
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

        // Filtro de produtos
        function filterProducts() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const tableBody = document.getElementById("produtosTableBody");
            const rows = tableBody.getElementsByTagName("tr");
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const productName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
                const productDescription = row.querySelector('.product-description')?.textContent.toLowerCase() || '';
                const productCategory = row.querySelector('.product-category')?.textContent.toLowerCase() || '';
                
                if (productName.includes(filter) || productDescription.includes(filter) || productCategory.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            }

            // Atualiza contador
            document.getElementById('resultsCount').textContent = visibleCount + ' produto(s) encontrado(s)';
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