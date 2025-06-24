<?php
// Inclui o arquivo de autenticação para verificar se o usuário está logado.
// Certifique-se de que autenticacao.php está no mesmo diretório.
include("autenticacao.php");

// Opcional: Para depuração, você pode ativar a exibição de erros.
// Em produção, deve ser desabilitado ou direcionado para logs.
ini_set('display_errors', '0'); // Desabilite em produção
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        /* Estilos globais e cores predominantes */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a; /* Fundo principal preto */
            color: #f5f5f5; /* Cor padrão do texto: branco */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Cabeçalho */
        header {
            background-color: #333333; /* Fundo cinza escuro para o cabeçalho */
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1em;
            color: #e60000; /* Texto do cabeçalho em vermelho */
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4); /* Sombra para profundidade */
        }

        header span {
            color: #f5f5f5; /* "Olá, [Nome]" em branco */
        }

        header a {
            color: #e60000; /* Link "Sair" em vermelho */
            text-decoration: none;
            font-weight: 600;
            padding: 8px 15px;
            border: 1px solid #e60000; /* Borda vermelha */
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        header a:hover {
            color: #ffffff; /* Texto branco no hover */
            background-color: #cc0000; /* Fundo vermelho mais escuro no hover */
            border-color: #ffffff; /* Borda branca no hover */
        }

        /* Contêiner principal (menu lateral + conteúdo) */
        .container {
            flex: 1; /* Ocupa o espaço restante */
            display: flex;
            max-width: 1200px; /* Largura máxima para centralizar */
            margin: 40px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6); /* Sombra mais intensa */
            background-color: #222222; /* Fundo do contêiner cinza escuro */
        }

        /* Menu de navegação lateral */
        nav {
            width: 250px; /* Largura fixa do menu */
            background-color: #111111; /* Fundo do menu mais escuro (preto) */
            padding: 30px 20px;
            border-right: 1px solid #444444; /* Separador cinza escuro */
            color: #f5f5f5; /* Texto do menu branco */
            box-sizing: border-box; /* Inclui padding na largura total */
        }

        nav h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 1.8em;
            color: #e60000; /* Título do menu em vermelho */
            text-align: center;
        }

        nav a {
            display: block;
            padding: 12px 15px;
            color: #f5f5f5; /* Links do menu em branco */
            text-decoration: none;
            font-size: 1.1em;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            background-color: #e60000; /* Fundo vermelho no hover */
            color: #ffffff; /* Texto branco no hover */
            transform: translateX(5px); /* Pequeno movimento no hover */
            transition: all 0.2s ease-out;
        }

        /* Conteúdo principal */
        main {
            flex: 1; /* Ocupa o espaço restante */
            padding: 40px 50px;
            color: #f5f5f5; /* Texto do conteúdo em branco */
        }

        main h2 {
            margin-top: 0;
            font-weight: 700;
            font-size: 2.5em;
            color: #e60000; /* Título principal em vermelho */
            margin-bottom: 25px;
        }

        main p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #cccccc; /* Texto secundário em cinza claro */
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                margin: 20px;
                width: auto; /* Permite que o contêiner ocupe a largura total disponível */
            }
            nav {
                width: 100%; /* Menu ocupa toda a largura em telas menores */
                border-right: none;
                border-bottom: 1px solid #444444; /* Separador na parte inferior */
                padding: 20px;
            }
            nav a {
                text-align: center; /* Centraliza links no menu responsivo */
                padding: 10px 0;
                margin-bottom: 5px;
            }
            main {
                padding: 30px 20px;
            }
            main h2 {
                font-size: 2em; /* Ajuste do tamanho do título */
            }
        }
    </style>
</head>
<body>

<header>
    <span>Olá, <?=htmlspecialchars($_SESSION['nome'] ?? 'Usuário')?></span>
    <a href="sair.php">Sair</a>
</header>

<div class="container">
    <nav>
        <h2>Menu</h2>
        <a href="cadastroUsuarios.php">Gerenciar Usuários</a>
        <a href="cadastroFilmes.php">Cadastrar Filmes</a>
        </nav>

    <main>
        <h2>Bem-vindo à Central de Filmes!</h2>
        <p>Esta é a sua página principal. Aqui você pode navegar para diferentes seções para gerenciar o sistema.</p>
        <p>Utilize o menu lateral para cadastrar novos usuários, adicionar filmes à coleção ou realizar outras tarefas de administração.</p>
        <p>Aproveite a experiência!</p>
    </main>
</div>

</body>
</html>