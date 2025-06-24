<?php
// Para depuração: exibir todos os erros. Em produção, desabilite.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Inclua sua conexão com o banco de dados
include("conexao.php"); // Certifique-se de que 'conexao.php' está no mesmo diretório

// --- DEBUG: Check database connection status ---
if ($conn->connect_error) {
    die("Erro Crítico: Falha na conexão com o banco de dados em cadastroFilmes.php: " . $conn->connect_error);
}
// --- END DEBUG ---

// Initialize message for user feedback (optional, but good practice)
$message = '';
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'success') {
        $message = "<p class='message success'>Filme cadastrado com sucesso!</p>";
    } elseif ($_GET['message'] == 'empty_fields') {
        $message = "<p class='message error'>Por favor, preencha todos os campos.</p>";
    } elseif ($_GET['message'] == 'insert_error_prepare' || $_GET['message'] == 'insert_error_bind' || $_GET['message'] == 'insert_failed') {
        $message = "<p class='message error'>Erro ao cadastrar filme. Verifique os logs do servidor.</p>";
    } elseif ($_GET['message'] == 'deleted') {
        $message = "<p class='message success'>Filme apagado com sucesso!</p>";
    } elseif ($_GET['message'] == 'update_success') {
        $message = "<p class='message success'>Filme alterado com sucesso!</p>";
    } elseif ($_GET['message'] == 'update_error') {
        $message = "<p class='message error'>Erro ao alterar filme. Tente novamente.</p>";
    } elseif ($_GET['message'] == 'delete_error') { // Added for delete error
        $message = "<p class='message error'>Erro ao apagar filme. Tente novamente.</p>";
    }
}

// --- Logic for Alteration Mode ---
$is_editing = false;
$edit_filme_id = ''; // Changed from $edit_id to $edit_filme_id
$edit_nome = '';
$edit_ano = '';
$edit_genero_id = '';

if (isset($_POST['action']) && $_POST['action'] === 'edit_movie') {
    $is_editing = true;
    $edit_filme_id = htmlspecialchars($_POST['filme_id']); // Use 'filme_id' from the form post
    $edit_nome = htmlspecialchars($_POST['nome']);
    $edit_ano = htmlspecialchars($_POST['ano']);
    $edit_genero_id = htmlspecialchars($_POST['genero']);
}


// Fetch genres from the database for the dropdown
$generos = [];
$sql_generos = "SELECT genero AS id, descricao FROM generos ORDER BY descricao ASC";
$resultado_generos = $conn->query($sql_generos);

if (!$resultado_generos) {
    error_log("Erro ao buscar gêneros: " . $conn->error);
    $message .= "<p class='message error'>Erro ao carregar gêneros.</p>";
} else {
    while ($row = $resultado_generos->fetch_assoc()) {
        $generos[] = $row;
    }
}


// Fetch movies from the database for the list
$filmes_list = []; // Renamed to avoid conflict with table alias
// CORRECTED: Using 'f.filme' instead of 'f.id'
$sql_filmes = "SELECT f.filme, f.nome, f.ano, g.descricao AS genero_descricao, f.genero AS genero_id
               FROM filmes f
               JOIN generos g ON f.genero = g.genero
               ORDER BY f.nome ASC";
$resultado_filmes = $conn->query($sql_filmes);

if (!$resultado_filmes) {
    error_log("Erro ao carregar filmes: " . $conn->error);
    $message .= "<p class='message error'>Erro ao carregar lista de filmes.</p>";
} else {
    while ($row = $resultado_filmes->fetch_assoc()) {
        $filmes_list[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Filmes</title>
    <style>
        /* Your existing CSS styles (from cadastroUsuarios.php or your general CSS file) */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            color: #f5f5f5;
        }

        .main-container {
            width: 1024px;
            margin: 0 auto;
            background-color: #222;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            overflow: hidden;
        }

        .header-bar {
            min-height: 100px;
            width: 100%;
            background-color: #ACAF5C;
            overflow: hidden;
            padding: 15px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            color: #333;
            font-family: 'Open Sans', sans-serif;
            font-size: 24px;
            padding-left: 15px;
        }

        .logout-link {
            background-color: #e60000;
            padding: 8px 15px;
            border-radius: 5px;
            margin-right: 50px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-link:hover {
            background-color: #cc0000;
        }

        .menu-bar {
            width: 200px;
            background-color: #F4F4F4;
            min-height: 400px;
            float: left;
            padding: 20px;
            box-sizing: border-box;
            color: #333;
        }

        .menu-bar h2 {
            color: #e60000;
            margin-bottom: 20px;
            border-bottom: 2px solid #e60000;
            padding-bottom: 10px;
        }

        .menu-bar p {
            margin-bottom: 10px;
        }

        .menu-bar a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .menu-bar a:hover {
            color: #e60000;
            text-decoration: underline;
        }

        .content-area {
            background-color: #333333;
            min-height: 400px;
            width: calc(100% - 200px);
            float: left;
            padding: 20px;
            box-sizing: border-box;
            color: #f5f5f5;
        }

        .content-area h1 {
            color: #e60000;
            margin-bottom: 25px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .content-area p {
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .content-area form input[type="text"],
        .content-area form input[type="number"],
        .content-area form select {
            width: 250px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #444;
            color: #f5f5f5;
        }
        .content-area form select option {
            background-color: #444;
            color: #f5f5f5;
        }


        .content-area form input[type="submit"] {
            background-color: #e60000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .content-area form input[type="submit"]:hover {
            background-color: #cc0000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #444444;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #555555;
            color: #f5f5f5;
        }

        table th {
            background-color: #e60000;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        table tr:nth-child(even) {
            background-color: #3a3a3a;
        }

        table tr:hover {
            background-color: #555555;
        }

        .no-records-message {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #cccccc;
        }

        /* Message styles */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            animation: slideIn 0.5s forwards;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .action-link {
            display: inline-block;
            margin-top: 20px;
            margin-right: 10px;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border: 1px solid #ffffff;
            border-radius: 6px;
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        }

        .action-link:hover {
            color: #e60000;
            background-color: #ffffff;
            border-color: #e60000;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header-bar">
            <div class="header-title">
                <span>Projeto [Administrador]</span>
            </div>
            <div>
                <a href="sair.php" class="logout-link">SAIR</a>
            </div>
        </div>

        <div class="menu-bar">
            <h2>Menu</h2>
            <p><a href="cadastroUsuarios.php">Cadastrar Usuários</a></p>
            <p><a href="cadastroFilmes.php">Cadastrar Filmes</a></p>
            <p>Item 3</p>
        </div>

        <div class="content-area">
            <h1>Gerenciamento de Filmes</h1>

            <?php echo $message; // Display messages here ?>

            <p><strong><?php echo $is_editing ? '(1) Alterar filme existente:' : '(1) Cadastrar novo filme:'; ?></strong></p>
            <form method="post" action="<?php echo $is_editing ? 'alterarFilme.php' : 'inserirFilme.php'; ?>">
                <label for="nome">Nome do Filme:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $edit_nome; ?>" required><br>

                <label for="ano">Ano de Lançamento:</label>
                <input type="number" name="ano" id="ano" value="<?php echo $edit_ano; ?>" required min="1888" max="<?php echo date('Y') + 5; ?>"><br>

                <label for="genero">Gênero:</label>
                <select name="genero" id="genero" required>
                    <option value="">Selecione um Gênero</option>
                    <?php
                    foreach ($generos as $genero) {
                        $selected = ($genero['id'] == $edit_genero_id) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($genero['id']) . "' " . $selected . ">" . htmlspecialchars($genero['descricao']) . "</option>";
                    }
                    ?>
                </select><br>

                <?php if ($is_editing): ?>
                    <input type="hidden" name="filme_id" value="<?php echo $edit_filme_id; ?>">
                <?php endif; ?>

                <input type="submit" value="<?php echo $is_editing ? 'Atualizar Filme' : 'Inserir Filme'; ?>">
            </form>

            <br><br>

            <h2>Lista de Filmes Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID do Filme</th> <th>Nome</th>
                        <th>Ano</th>
                        <th>Gênero</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($filmes_list)) { // Use the renamed variable
                        foreach ($filmes_list as $filme) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($filme['filme']) ?></td> <td><?= htmlspecialchars($filme['nome']) ?></td>
                                <td><?= htmlspecialchars($filme['ano']) ?></td>
                                <td><?= htmlspecialchars($filme['genero_descricao']) ?></td>
                                <td>
                                    <form method="post" action="cadastroFilmes.php" style="display: inline-block;">
                                        <input type="hidden" name="action" value="edit_movie">
                                        <input type="hidden" name="filme_id" value="<?= htmlspecialchars($filme['filme']) ?>"> <input type="hidden" name="nome" value="<?= htmlspecialchars($filme['nome']) ?>">
                                        <input type="hidden" name="ano" value="<?= htmlspecialchars($filme['ano']) ?>">
                                        <input type="hidden" name="genero" value="<?= htmlspecialchars($filme['genero_id']) ?>">
                                        <input type="submit" value="Alterar">
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="apagarFilme.php" style="display: inline-block;">
                                        <input type="hidden" name="filme_id" value="<?= htmlspecialchars($filme['filme']) ?>"> <input type="submit" value="Apagar" onclick="return confirm('Tem certeza que deseja apagar este filme?');">
                                    </form>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='no-records-message'>Nenhum filme encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="principal.php" class="action-link">Voltar para a Página Principal</a>
        </div>
        <div style="clear: both;"></div>
    </div>
    <?php
    // Close the database connection at the end of the script
    if (isset($conn) && $conn) {
        $conn->close();
    }
    ?>
</body>
</html>