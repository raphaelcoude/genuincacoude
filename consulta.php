<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Cavalos - Genuína Marcha</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Pesquisar Cavalos Cadastrados</h2>

        <!-- Pesquisa por Nome do Cavalo -->
        <form method="GET">
            <div class="form-group">
                <input type="text" name="nome" placeholder="Digite o nome do cavalo para pesquisar...">
            </div>
            <button type="submit" class="search-btn">Pesquisar por Nome</button>
        </form>

        <?php
        if (isset($_GET['nome']) && !empty($_GET['nome'])) {
            $nome = $_GET['nome'];

            // Conexão com o banco de dados
            $conn = new mysqli('localhost', 'root', '', 'genuina');

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM cavalos WHERE nome LIKE ?";
            $stmt = $conn->prepare($sql);
            $nomeBusca = "%$nome%";
            $stmt->bind_param('s', $nomeBusca);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Nome</th><th>Pai</th><th>Mãe</th><th>Raça</th><th>Pelagem</th><th>Idade</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['pai'] . "</td>";
                    echo "<td>" . $row['mae'] . "</td>";
                    echo "<td>" . $row['raca'] . "</td>";
                    echo "<td>" . $row['pelagem'] . "</td>";
                    echo "<td>" . $row['idade'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Nenhum cavalo encontrado com esse nome.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- Pesquisa por Pai do Cavalo -->
        <form method="GET">
            <div class="form-group">
                <input type="text" name="pai" placeholder="Digite o nome do pai para pesquisar...">
            </div>
            <button type="submit" class="search-btn">Pesquisar por Pai</button>
        </form>

        <?php
        if (isset($_GET['pai']) && !empty($_GET['pai'])) {
            $pai = $_GET['pai'];

            // Conexão com o banco de dados
            $conn = new mysqli('localhost', 'root', '', 'genuina');

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM cavalos WHERE pai LIKE ?";
            $stmt = $conn->prepare($sql);
            $paiBusca = "%$pai%";
            $stmt->bind_param('s', $paiBusca);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Nome</th><th>Pai</th><th>Mãe</th><th>Raça</th><th>Pelagem</th><th>Idade</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['pai'] . "</td>";
                    echo "<td>" . $row['mae'] . "</td>";
                    echo "<td>" . $row['raca'] . "</td>";
                    echo "<td>" . $row['pelagem'] . "</td>";
                    echo "<td>" . $row['idade'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Nenhum cavalo encontrado com esse pai.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>