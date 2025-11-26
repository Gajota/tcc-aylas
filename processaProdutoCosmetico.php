<?php
session_start();
require_once 'conexao.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

try {

    switch ($acao) {

        // ===========================
        // SALVAR PRODUTO
        // ===========================
        case 'salvar':

            $nome       = $_POST['nome_produto'];
            $valor      = $_POST['valor_produto'];
            $categoria  = $_POST['categoria_produto'];
            $descricao  = $_POST['descricao_produto'];

            // Corrige vírgula no valor
            $valor_formatado = str_replace(',', '.', $valor);

            // === IMAGEM ===
            $imagem = null;
            if (!empty($_FILES['imagem']['tmp_name'])) {
                $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
            }

            $sql = "INSERT INTO tb_produto 
                    (nome_produto, valor_produto, categoria_produto, descricao_produto, imagem)
                    VALUES 
                    (:nome, :valor, :categoria, :descricao, :imagem)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':valor', $valor_formatado);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB);
            $stmt->execute();

            $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
            header("Location: listaProdutosCosmetico.php");
            exit;



        // ===========================
        // ATUALIZAR PRODUTO
        // ===========================
        case 'atualizar':

            $id         = $_POST['id'];
            $nome       = $_POST['nome_produto'];
            $valor      = $_POST['valor_produto'];
            $categoria  = $_POST['categoria_produto'];
            $descricao  = $_POST['descricao_produto'];

            $valor_formatado = str_replace(',', '.', $valor);

            // === IMAGEM ===
            if (!empty($_FILES['imagem']['tmp_name'])) {
                // Nova imagem enviada
                $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
            } else {
                // Mantém a imagem antiga
                if (!empty($_POST['imagem_atual'])) {
                    $imagem = base64_decode($_POST['imagem_atual']);
                } else {
                    $imagem = null;
                }
            }

            $sql = "UPDATE tb_produto SET
                        nome_produto      = :nome,
                        valor_produto     = :valor,
                        categoria_produto = :categoria,
                        descricao_produto = :descricao,
                        imagem            = :imagem
                    WHERE id_produto = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':valor', $valor_formatado);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
            header("Location: listaProdutosCosmetico.php");
            exit;



        // ===========================
        // EXCLUIR PRODUTO
        // ===========================
        case 'excluir':

            $id = $_GET['id'] ?? null;

            if ($id) {
                $sql = "DELETE FROM tb_produto WHERE id_produto = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['sucesso'] = "Produto excluído com sucesso!";
            }

            header("Location: listaProdutosCosmetico.php");
            exit;


        // ===========================
        // AÇÃO INVÁLIDA
        // ===========================
        default:
            header("Location: listaProdutosCosmetico.php");
            exit;
    }


} catch(PDOException $e) {

    $_SESSION['erro'] = "Erro ao processar produto: " . $e->getMessage();

    if ($acao === 'atualizar') {
        header("Location: editarProdutoCosmetico.php?id=" . ($_POST['id'] ?? ''));
    } else {
        header("Location: cadProdutoCosmetico.php");
    }
    exit;
}

$conn = null;
?>
