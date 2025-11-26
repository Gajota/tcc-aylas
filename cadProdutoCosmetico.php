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

    // Inicializa a variável $row
    $row = null;

    // Verifica se um ID foi passado para edição
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if ($id !== false) {
            $sql = "SELECT * FROM tb_produto WHERE id_produto = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();

            if (!$row) {
                header('Location: listaProdutosCosmetico.php?error=produto_nao_encontrado');
                exit;
            }
        }
    }
} catch (PDOException $e) {
    error_log("Erro de conexão ou consulta: " . $e->getMessage());
    die("Erro ao conectar com o banco de dados. Tente novamente mais tarde.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($row) ? 'Editar Produto - Ayla\'s Cosmetic' : 'Cadastrar Produto - Ayla\'s Cosmetic'; ?></title>
    <style>
        /* Variáveis CSS atualizadas */
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

        /* Menu Lateral */
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

        /* Grupos do Formulário */
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

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--background-light);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Preview da Imagem */
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            padding: 5px;
            background: white;
        }

        .image-preview p {
            margin-top: 8px;
            color: var(--text-light);
            font-size: 0.9rem;
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

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--text-light);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--text-dark);
            transform: translateY(-2px);
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
            <h1 class="page-title"><?php echo isset($row) ? 'Editar Produto' : 'Cadastrar Produto'; ?></h1>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2><?php echo isset($row) ? 'Editar Produto' : 'Novo Produto'; ?></h2>
                <p><?php echo isset($row) ? 'Atualize as informações do produto' : 'Preencha os dados do novo produto'; ?></p>
            </div>

            <form action="processaProdutoCosmetico.php" method="POST" enctype="multipart/form-data" id="produtoForm">

    <!-- AÇÃO (salvar ou atualizar) -->
    <input type="hidden" name="acao" value="<?php echo isset($row) ? 'atualizar' : 'salvar'; ?>">

    <!-- ID DO PRODUTO (quando quiser editar) -->
    <input type="hidden" name="id" value="<?php echo isset($row) ? htmlspecialchars($row['id_produto']) : ''; ?>">
                
                <div class="form-group">
                    <label for="nome_produto">Nome do Produto *</label>
                    <input type="text" id="nome_produto" name="nome_produto" 
                           value="<?php echo isset($row) ? htmlspecialchars($row['nome_produto']) : ''; ?>" 
                           required maxlength="60" placeholder="Ex: Batom Líquido Matte">
                </div>

                <div class="form-group">
                    <label for="descricao_produto">Descrição *</label>
                    <textarea id="descricao_produto" name="descricao_produto" rows="4" 
                              required maxlength="255" placeholder="Descreva o produto..."><?php echo isset($row) ? htmlspecialchars($row['descricao_produto']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="valor_produto">Valor (R$) *</label>
                    <input type="number" step="0.01" min="0.01" id="valor_produto" name="valor_produto" 
                           value="<?php echo isset($row) ? htmlspecialchars($row['valor_produto']) : ''; ?>" 
                           required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="categoria_produto">Categoria *</label>
                    <select id="categoria_produto" name="categoria_produto" required>
                        <option value="">Selecione a categoria</option>
                        <option value="1" <?php echo (isset($row) && $row['categoria_produto'] == 1) ? 'selected' : ''; ?>>Maquiagem</option>
                        <option value="2" <?php echo (isset($row) && $row['categoria_produto'] == 2) ? 'selected' : ''; ?>>Perfumaria</option>
                        <option value="3" <?php echo (isset($row) && $row['categoria_produto'] == 3) ? 'selected' : ''; ?>>Skincare</option>
                        <option value="4" <?php echo (isset($row) && $row['categoria_produto'] == 4) ? 'selected' : ''; ?>>Cabelos</option>
                        <option value="5" <?php echo (isset($row) && $row['categoria_produto'] == 5) ? 'selected' : ''; ?>>Corpo e Banho</option>
                        <option value="6" <?php echo (isset($row) && $row['categoria_produto'] == 6) ? 'selected' : ''; ?>>Acessórios</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem do Produto</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" 
                           onchange="previewImage(this)">
                    
                    <?php if (isset($row) && !empty($row['imagem'])): ?>
                        <div class="image-preview">
                            <p>Imagem atual:</p>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['nome_produto']); ?>">
                            <input type="hidden" name="imagem_atual" value="<?php echo base64_encode($row['imagem']); ?>">
                        </div>
                    <?php else: ?>
                        <div class="image-preview" id="imagePreview" style="display: none;">
                            <p>Prévia da nova imagem:</p>
                            <img id="previewImage" src="#" alt="Prévia da imagem">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($row) ? ' Atualizar Produto' : ' Salvar Produto'; ?>
                    </button>
                    <a href="listaProdutosCosmetico.php" class="btn btn-secondary"> Cancelar</a>
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

        // Preview de imagem
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImage');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Validação do formulário
        document.getElementById('produtoForm').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome_produto').value.trim();
            const valor = document.getElementById('valor_produto').value;
            
            if (nome === '') {
                e.preventDefault();
                alert('Por favor, preencha o nome do produto.');
                return;
            }
            
            if (parseFloat(valor) <= 0) {
                e.preventDefault();
                alert('O valor do produto deve ser maior que zero.');
                return;
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
    </script>
</body>
</html>