<?php
session_start();
require_once 'conexao.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

try {
    switch ($acao) {
        case 'salvar':
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $datanasc = $_POST['datanasc'] ?? null;
            $cpf = $_POST['cpf'] ?? '';
            $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente';
            $complemento = $_POST['complemento'] ?? '';
            
            // Sanitização
            $fone = preg_replace('/\D/', '', $_POST['fone'] ?? '');
            $cep = str_replace('-', '', $_POST['cep'] ?? '');
            $cpf = str_replace(['.', '-'], '', $cpf);
            
            $bairro = $_POST['bairro'] ?? '';
            $rua = $_POST['rua'] ?? '';
            $numero = $_POST['numero'] ?? '';

            // Validações básicas
            if (empty($nome) || empty($email) || empty($senha)) {
                $_SESSION['erro'] = "Nome, e-mail e senha são obrigatórios!";
                header("Location: cadUsuarioCosmetico.php");
                exit;
            }

            // Hash da senha
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO tb_usuario (nome_usuario, datanasc_usuario, email_usuario, fone_usuario, cep_usuario, bairro_usuario, rua_usuario, numero_usuario, cpf, tipo_usuario, senha_usuario) 
                    VALUES (:nome, :datanasc, :email, :fone, :cep, :bairro, :rua, :numero, :cpf, :tipo_usuario, :senha)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':datanasc', $datanasc);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':fone', $fone);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':bairro', $bairro);
            $stmt->bindParam(':rua', $rua);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
            $stmt->bindParam(':senha', $senha_hashed);
            
            $stmt->execute();

            // Login automático após cadastro
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $conn->lastInsertId();
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_tipo'] = $tipo_usuario;
            
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
            header("Location: lojacosmeticos.php");
            exit;

        case 'atualizar':
            // ... (código existente para atualizar)
            break;

        case 'excluir':
            // ... (código existente para excluir)
            break;

        default:
            header("Location: cadUsuarioCosmetico.php");
            exit;
    }
} catch(PDOException $e) {
    if ($e->getCode() == 23000) { // Erro de duplicidade
        $_SESSION['erro'] = "E-mail ou CPF já cadastrado!";
    } else {
        $_SESSION['erro'] = "Erro ao processar usuário: " . $e->getMessage();
    }
    
    header("Location: cadUsuarioCosmetico.php");
    exit;
}
?>