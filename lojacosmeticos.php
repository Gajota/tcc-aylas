<?php
session_start();


// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_loja";

// ID do usuário logado (simulado)
// Em uma aplicação real, este valor viria da sessão do usuário
$id_usuario_logado = 1;

// Inicializar variáveis
$produtos = [];
$erro = '';

try {
    // Cria a conexão PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Define o modo de erro do PDO para lançar exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca todos os produtos para exibir na loja
    $sql_select_produtos = "SELECT * FROM tb_produto";
    $stmt_produtos = $conn->prepare($sql_select_produtos);
    $stmt_produtos->execute();
    $produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Ayla's Cosméstic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos CSS aprimorados */
        :root {
            --primary-color: #e91e63;
            --primary-dark: #c2185b;
            --primary-light: #f8bbd0;
            --secondary-color: #9c27b0;
            --background-light: #f8f8f8;
            --text-dark: #333;
            --text-light: #666;
            --border-color: #ddd;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Menu lateral */
        .side-menu {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            background: linear-gradient(to bottom, var(--primary-color), var(--primary-dark));
            overflow-x: hidden;
            transition: var(--transition);
            padding-top: 60px;
            box-shadow: var(--shadow);
        }

        .side-menu a {
            padding: 12px 16px 12px 32px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: var(--transition);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .side-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 40px;
        }

        .side-menu .closebtn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 36px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        /* Barra superior */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background-color: white;
            box-shadow: var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 900;
        }

        .menu-btn {
            cursor: pointer;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            transition: var(--transition);
        }

        .menu-btn:hover {
            background-color: var(--primary-dark);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-left: 20px;
        }

        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 20px;
        }

        .search-container input {
            padding: 12px 20px;
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            font-size: 16px;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(233, 30, 99, 0.2);
        }

        .user-actions {
            display: flex;
            gap: 15px;
        }

        .icon-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-dark);
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        .icon-btn:hover {
            color: var(--primary-color);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Conteúdo principal */
        .main-content {
            flex: 1;
            padding: 100px 20px 20px;
            transition: margin-left .5s;
        }

        .container-produtos {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-size: 2.5rem;
            position: relative;
        }

        .page-title:after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            background-color: var(--primary-color);
            margin: 10px auto;
        }

        /* Filtros */
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .sort-select {
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            background-color: white;
        }

        /* Grid de produtos */
        .produto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .produto-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .produto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .produto-card img {
            max-width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: var(--transition);
        }

        .produto-card:hover img {
            transform: scale(1.05);
        }

        .produto-card h3 {
            margin: 15px 0 10px;
            font-size: 1.25rem;
            color: var(--text-dark);
        }

        .produto-card .descricao {
            font-size: 0.95em;
            color: var(--text-light);
            margin-bottom: 15px;
            min-height: 60px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .produto-card .price {
            font-size: 1.5em;
            color: var(--primary-color);
            font-weight: bold;
            margin: 10px 0;
        }

        .btn-adicionar-carrinho {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1em;
            transition: var(--transition);
            width: 100%;
            font-weight: 600;
        }

        .btn-adicionar-carrinho:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .adicionar-carrinho {
            margin-top: 15px;
        }

        /* Mensagem de erro */
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }

        /* Mensagem de nenhum produto */
        .no-products {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 1.2rem;
        }

        /* Carrinho flutuante */
        .floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            cursor: pointer;
            z-index: 800;
            transition: var(--transition);
        }

        .floating-cart:hover {
            transform: scale(1.1);
            background-color: var(--primary-dark);
        }

        .floating-cart i {
            font-size: 24px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }

            .search-container {
                margin: 0;
                order: 3;
                width: 100%;
            }

            .filters {
                flex-direction: column;
            }

            .produto-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .main-content {
                padding: 150px 15px 15px;
            }
        }

        @media (max-width: 480px) {
            .produto-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div id="sideMenu" class="side-menu">
        <button class="closebtn" onclick="closeNav()">&times;</button>
        <a href="lojacosmeticos.php"><i class="fas fa-store"></i> Loja</a>
        <a href="cadUsuarioCosmetico.php"><i class="fas fa-user-plus"></i> Adicionar Usuário</a>
        <a href="listaUsuariosCosmetico.php"><i class="fas fa-users"></i> Listar Usuários</a>
        <a href="cadProdutoCosmetico.php"><i class="fas fa-plus-circle"></i> Adicionar Produto</a>
        <a href="listaProdutosCosmetico.php"><i class="fas fa-list"></i> Listar Produtos</a>
        <a href="logout.php" style="color: #ff6b6b; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;"> 
        🚪 Sair do Sistema
    </a>
    </div>

    <div class="top-bar">
        <button class="menu-btn" onclick="openNav()"><i class="fas fa-bars"></i> Menu</button>
        <div class="logo">Ayla's Cosméstic</div>
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Pesquisar produtos...">
        </div>

        <div class="user-actions">
            <button class="icon-btn" title="Perfil">
                <i class="fas fa-user"></i>
            </button>
            <button class="icon-btn" title="Carrinho">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
            </button>
        </div>
    </div>

    <div class="main-content">
        <div class="container-produtos">
            <h1 class="page-title">Nossos Produtos</h1>
            
            <!-- Filtros e ordenação -->
            <div class="filters">
                <div class="filter-group">
                    <button class="filter-btn active" data-filter="all">Todos</button>
                    <button class="filter-btn" data-filter="skincare">Skincare</button>
                    <button class="filter-btn" data-filter="maquiagem">Maquiagem</button>
                    <button class="filter-btn" data-filter="cabelos">Cabelos</button>
                    <button class="filter-btn" data-filter="perfumaria">Perfumaria</button>
                    <button class="filter-btn" data-filter="corpo">Corpo & Banho</button>
                </div>
                <select class="sort-select" id="sortSelect" onchange="sortProducts()">
                    <option value="nome">Ordenar por: Nome</option>
                    <option value="preco-asc">Preço: Menor para Maior</option>
                    <option value="preco-desc">Preço: Maior para Menor</option>
                </select>
            </div>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>

            <!-- Grid de produtos -->
            <div class="produto-grid" id="produtos-grid">
                <?php if (!empty($produtos)): ?>
                <?php
                $categorias_loja = [
                1 => 'maquiagem',
                2 => 'perfumaria',
                3 => 'skincare',
                4 => 'cabelos',
                5 => 'corpo',
                6 => 'acessorios'
                                    ];
                                    ?>
                    <?php foreach ($produtos as $produto): ?>
                        
                        <div class="produto-card" data-category="<?php echo $categorias_loja[$produto['categoria_produto']] ?? 'outros'; ?>">
                        <?php if (!empty($produto['imagem'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagem']); ?>" 
                            alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" 
                            alt="Sem imagem">
                    <?php endif; ?>

                            <h3><?php echo htmlspecialchars($produto['nome_produto']); ?></h3>
                            <p class="descricao"><?php echo htmlspecialchars($produto['descricao_produto']); ?></p>
                            <p class="price">R$ <?php echo number_format($produto['valor_produto'], 2, ',', '.'); ?></p>
                            <form action="carrinho.php" method="post" class="adicionar-carrinho">
                                <input type="hidden" name="id_produto" value="<?php echo htmlspecialchars($produto['id_produto']); ?>">
                                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario_logado); ?>">
                                <button type="submit" class="btn-adicionar-carrinho">
                                    <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <p>Nenhum produto cadastrado no momento.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Carrinho flutuante -->
    <div class="floating-cart" onclick="window.location.href='carrinho.php'">
        <i class="fas fa-shopping-cart"></i>
    </div>

    <script>
        function openNav() {
            document.getElementById("sideMenu").style.width = "280px";
        }

        function closeNav() {
            document.getElementById("sideMenu").style.width = "0";
        }

        // Fechar menu ao clicar fora dele
        document.addEventListener('click', function(event) {
            const sideMenu = document.getElementById('sideMenu');
            const menuBtn = document.querySelector('.menu-btn');
            
            if (!sideMenu.contains(event.target) && !menuBtn.contains(event.target)) {
                closeNav();
            }
        });

        function filterProducts() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const grid = document.getElementById("produtos-grid");
            const cards = grid.getElementsByClassName("produto-card");

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const title = card.getElementsByTagName("h3")[0].textContent.toLowerCase();
                const description = card.getElementsByClassName("descricao")[0].textContent.toLowerCase();

                if (title.includes(filter) || description.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }

        // Filtros por categoria
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class de todos os botões
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Adiciona active class ao botão clicado
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                filterByCategory(filter);
            });
        });

        function filterByCategory(category) {
            const cards = document.querySelectorAll('.produto-card');
            
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Ordenação de produtos
        function sortProducts() {
            const sortSelect = document.getElementById('sortSelect');
            const sortValue = sortSelect.value;
            const grid = document.getElementById('produtos-grid');
            const cards = Array.from(grid.getElementsByClassName('produto-card'));
            
            cards.sort((a, b) => {
                if (sortValue === 'nome') {
                    const nameA = a.getElementsByTagName('h3')[0].textContent.toLowerCase();
                    const nameB = b.getElementsByTagName('h3')[0].textContent.toLowerCase();
                    return nameA.localeCompare(nameB);
                } else if (sortValue === 'preco-asc') {
                    const priceA = parseFloat(a.getElementsByClassName('price')[0].textContent.replace('R$ ', '').replace(',', '.'));
                    const priceB = parseFloat(b.getElementsByClassName('price')[0].textContent.replace('R$ ', '').replace(',', '.'));
                    return priceA - priceB;
                } else if (sortValue === 'preco-desc') {
                    const priceA = parseFloat(a.getElementsByClassName('price')[0].textContent.replace('R$ ', '').replace(',', '.'));
                    const priceB = parseFloat(b.getElementsByClassName('price')[0].textContent.replace('R$ ', '').replace(',', '.'));
                    return priceB - priceA;
                }
                return 0;
            });
            
            // Limpa o grid
            grid.innerHTML = '';
            
            // Adiciona os cards ordenados
            cards.forEach(card => {
                grid.appendChild(card);
            });
        }

        // Adicionar efeito de loading ao adicionar ao carrinho
        document.querySelectorAll('.btn-adicionar-carrinho').forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adicionando...';
                this.disabled = true;
                
                // Simula o processamento
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    
                    // Atualiza contador do carrinho
                    const cartCount = document.querySelector('.cart-count');
                    let count = parseInt(cartCount.textContent);
                    cartCount.textContent = count + 1;
                    
                    // Feedback visual
                    this.style.backgroundColor = '#4caf50';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 1000);
                }, 1000);
            });
        });
    </script>
</body>
</html>