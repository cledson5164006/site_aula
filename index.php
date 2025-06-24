<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Estilos globais e paleta de cores */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fonte mais moderna */
            /* Fundo principal em preto sólido para o tema */
            background-color: #1a1a1a; 
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #f5f5f5; /* Cor padrão do texto: branco */
        }

        /* Contêiner do formulário de login */
        .login-container {
            background-color: rgba(51, 51, 51, 0.8); /* Fundo cinza escuro semi-transparente */
            padding: 40px;
            border-radius: 10px; /* Bordas mais arredondadas */
            width: 100%;
            max-width: 380px; /* Aumentado ligeiramente para melhor visual */
            box-sizing: border-box;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6); /* Sombra mais forte */
            text-align: center;
            animation: fadeIn 0.8s ease-out; /* Animação ao carregar */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #e60000; /* Título "Login" em vermelho vibrante */
            margin-bottom: 30px;
            font-size: 2.5em; /* Título maior */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Sombra no texto do título */
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 22px); /* Ajuste para padding */
            padding: 12px;
            margin: 15px 0; /* Espaçamento maior entre os campos */
            border: 1px solid #555555; /* Borda cinza escuro */
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1.1em;
            color: #f5f5f5; /* Texto dos inputs branco */
            background-color: #444444; /* Fundo dos inputs mais escuro */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #aaaaaa; /* Cor do placeholder mais suave */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #e60000; /* Borda vermelha no foco */
            box-shadow: 0 0 0 0.2rem rgba(230, 0, 0, 0.25);
            outline: none; /* Remove a outline padrão do navegador */
        }

        input[type="submit"] {
            width: 100%;
            background-color: #e60000; /* Botão de submissão vermelho vibrante */
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 1.3em; /* Fonte maior para o botão */
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px; /* Mais espaço acima do botão */
            transition: background-color 0.3s ease, transform 0.2s ease;
            letter-spacing: 1px; /* Leve espaçamento entre letras */
        }

        input[type="submit"]:hover {
            background-color: #cc0000; /* Vermelho mais escuro no hover */
            transform: translateY(-3px); /* Efeito de elevação */
        }

        .register-link {
            margin-top: 25px; /* Mais espaço acima do link */
            display: block;
            color: #ffffff; /* Link de cadastro em branco */
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            padding: 8px 15px; /* Adiciona padding para parecer um botão discreto */
            border: 1px solid #ffffff; /* Borda branca */
            border-radius: 6px;
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        }

        .register-link:hover {
            color: #e60000; /* Texto vermelho no hover */
            background-color: #ffffff; /* Fundo branco no hover */
            border-color: #e60000; /* Borda vermelha no hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="submit" value="Entrar">
        </form>
        <a href="cadastroUsuarios.php" class="register-link">Cadastrar Usuário</a>
    </div>
</body>
</html>