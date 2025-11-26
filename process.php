<?php
// Define uma variável para a mensagem de status
$status_message = '';

// Verifica se o formulário foi enviado usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Coleta e sanitiza os dados do formulário
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validação básica para garantir que os campos não estão vazios
    if (!empty($name) && !empty($email) && !empty($message)) {
        
        // Aqui, você faria algo com os dados, como:
        // 1. Enviar um e-mail para o administrador do site
        // 2. Salvar os dados em um banco de dados
        
        // Exemplo simplificado: Apenas exibe uma mensagem de sucesso
        $status_message = "Obrigado, $name! Sua mensagem foi enviada com sucesso.";

    } else {
        // Se algum campo estiver vazio
        $status_message = "Por favor, preencha todos os campos do formulário.";
    }
} else {
    // Se a página for acessada diretamente sem um POST
    $status_message = "Acesso inválido. Por favor, use o formulário de contato na página principal.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Contato - Ayla's Cosméstic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
            text-align: center;
        }
        .message-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #e91e63;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Status do Contato</h1>
        <p><?php echo $status_message; ?></p>
        <a href="index.html">Voltar para a página inicial</a>
    </div>
</body>
</html>