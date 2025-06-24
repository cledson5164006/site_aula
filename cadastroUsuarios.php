<?php
include("conexao.php"); // Make sure 'conexao.php' is in the same directory

// For debugging: display all errors. In production, disable this.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Initialize message for user feedback
$message = '';
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'success') {
        $message = "<p class='message success'>Usuário cadastrado com sucesso!</p>";
    } elseif ($_GET['message'] == 'deleted') {
        $message = "<p class='message success'>Usuário apagado com sucesso!</p>";
    } elseif ($_GET['message'] == 'updated') {
        $message = "<p class='message success'>Usuário alterado com sucesso!</p>";
    } elseif ($_GET['message'] == 'empty_fields') {
        $message = "<p class='message error'>Por favor, preencha todos os campos.</p>";
    } elseif ($_GET['message'] == 'user_exists') {
        $message = "<p class='message error'>CPF já cadastrado. Por favor, use outro CPF.</p>";
    } elseif ($_GET['message'] == 'validation_error' || $_GET['message'] == 'insert_error' || $_GET['message'] == 'insert_failed' || $_GET['message'] == 'update_error') { // Added update_error
        $message = "<p class='message error'>Ocorreu um erro ao processar sua solicitação. Tente novamente.</p>";
    }
}

// --- Logic for Alteration Mode ---
$is_editing = false;
$edit_cpf = '';
$edit_nome = '';
$edit_senha = '';
$original_cpf_to_edit = ''; // To hold the original CPF for the WHERE clause in update

if (isset($_POST['action']) && $_POST['action'] === 'edit_user') {
    $is_editing = true;
    $edit_cpf = htmlspecialchars($_POST['cpf']); // The CPF of the user being edited
    $edit_nome = htmlspecialchars($_POST['nome']); // The Nome of the user being edited
    $edit_senha = htmlspecialchars($_POST['senha']); // The Senha of the user being edited
    $original_cpf_to_edit = htmlspecialchars($_POST['cpf']); // This is the CPF that identifies the record to be updated
}

// Fetch users from the database for the list
$sql = "SELECT CPF, Nome, Senha FROM usuarios";
$resultado = $conn->query($sql);

// Check for query errors
if (!$resultado) {
    error_log("Erro ao carregar usuários: " . $conn->error);
    $message .= "<p class='message error'>Erro ao carregar lista de usuários.</p>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários</title>
    <style>
        /* Your existing CSS styles from the previous file */
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
        .content-area form input[type="password"] {
            width: 250px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #555;
            border-radius: 4px;
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

        .no-users-message {
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
    <script>
        // Optional: Client-side validation for password in the main form
        function validarSenha() {
            // Add any client-side validation for password if needed
            return true; // For now, always return true to allow submission
        }
    </script>
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
            <h1>Gerenciamento de Usuários</h1>

            <?php echo $message; // Display messages here ?>

            <p><strong><?php echo $is_editing ? '(1) Alterar usuário existente:' : '(1) Criar novo usuário:'; ?></strong></p>
            <form method="post" action="<?php echo $is_editing ? 'alterarUsuario.php' : 'inserirUsuario.php'; ?>" onsubmit="return validarSenha()">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" value="<?php echo $edit_cpf; ?>" required <?php echo $is_editing ? '' : ''; ?>><br>
                <label for="nome">NOME:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $edit_nome; ?>" required><br>
                <label for="senha">SENHA:</label>
                <input type="password" name="senha" id="senha" value="<?php echo $edit_senha; ?>" required><br>

                <?php if ($is_editing): ?>
                    <input type="hidden" name="cpfAnterior" value="<?php echo $original_cpf_to_edit; ?>">
                <?php endif; ?>

                <input type="submit" value="<?php echo $is_editing ? 'Atualizar' : 'Inserir'; ?>">
            </form>

            <br><br>

            <h2>Lista de Usuários Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Senha</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado && $resultado->num_rows > 0) { // Check if query was successful and has rows
                        while ($row = $resultado->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Nome']) ?></td>
                                <td><?= htmlspecialchars($row['CPF']) ?></td>
                                <td><?= htmlspecialchars($row['Senha']) ?></td>
                                <td>
                                    <form method="post" action="cadastroUsuarios.php" style="display: inline-block;">
                                        <input type="hidden" name="action" value="edit_user">
                                        <input type="hidden" name="cpf" value="<?= htmlspecialchars($row['CPF']) ?>">
                                        <input type="hidden" name="nome" value="<?= htmlspecialchars($row['Nome']) ?>">
                                        <input type="hidden" name="senha" value="<?= htmlspecialchars($row['Senha']) ?>">
                                        <input type="submit" value="Alterar">
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="apagarUsuario.php" style="display: inline-block;">
                                        <input type="hidden" name="cpf" value="<?= htmlspecialchars($row['CPF']) ?>">
                                        <input type="submit" value="Apagar" onclick="return confirm('Tem certeza que deseja apagar este usuário?');">
                                    </form>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='no-users-message'>Nenhum usuário encontrado.</td></tr>";
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