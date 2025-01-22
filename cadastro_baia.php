<?php
// Configuração da conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "sistema_baias");

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para cadastrar nova ocupação
function cadastrarOcupacao($conn, $dados) {
    $sql = "INSERT INTO ocupacao_baia (
                numero_baia, 
                nome_cavalo, 
                proprietario, 
                cuidador, 
                quantidade_feno, 
                quantidade_racao, 
                valor_mensal, 
                data_vencimento, 
                data_cadastro
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssddds",
        $dados['baia'],
        $dados['cavalo'],
        $dados['proprietario'],
        $dados['cuidador'],
        $dados['feno'],
        $dados['racao'],
        $dados['valor_mensal'],
        $dados['vencimento']
    );

    return $stmt->execute();
}

// Função para registrar consumo diário
function registrarConsumo($conn, $dados) {
    // Primeiro, verifica se há estoque suficiente
    $sql_check = "SELECT quantidade_feno, quantidade_racao FROM ocupacao_baia 
                  WHERE numero_baia = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("i", $dados['baia']);
    $stmt->execute();
    $result = $stmt->get_result();
    $estoque = $result->fetch_assoc();

    if ($estoque['quantidade_feno'] < $dados['feno_usado'] || 
        $estoque['quantidade_racao'] < $dados['racao_usada']) {
        return false;
    }

    // Registra o consumo
    $sql_consumo = "INSERT INTO consumo_diario (
                        numero_baia, 
                        feno_consumido, 
                        racao_consumida, 
                        data_consumo
                    ) VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql_consumo);
    $stmt->bind_param(
        "idd",
        $dados['baia'],
        $dados['feno_usado'],
        $dados['racao_usada']
    );
    
    if ($stmt->execute()) {
        // Atualiza o estoque
        $sql_update = "UPDATE ocupacao_baia SET 
                      quantidade_feno = quantidade_feno - ?,
                      quantidade_racao = quantidade_racao - ?
                      WHERE numero_baia = ?";
        
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param(
            "ddi",
            $dados['feno_usado'],
            $dados['racao_usada'],
            $dados['baia']
        );
        return $stmt->execute();
    }
    return false;
}

// Processa as requisições POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cadastrar'])) {
        $dados = [
            'baia' => $_POST['baia'],
            'cavalo' => $_POST['cavalo'],
            'proprietario' => $_POST['proprietario'],
            'cuidador' => $_POST['cuidador'],
            'feno' => $_POST['feno'],
            'racao' => $_POST['racao'],
            'valor_mensal' => $_POST['valor_mensal'],
            'vencimento' => $_POST['vencimento']
        ];

        if (cadastrarOcupacao($conn, $dados)) {
            header("Location: index.html?msg=success&action=cadastro");
        } else {
            header("Location: index.html?msg=error&action=cadastro");
        }
    }

    if (isset($_POST['registrar_consumo'])) {
        $dados = [
            'baia' => $_POST['baia_consumo'],
            'feno_usado' => $_POST['feno_usado'],
            'racao_usada' => $_POST['racao_usada']
        ];

        if (registrarConsumo($conn, $dados)) {
            header("Location: index.html?msg=success&action=consumo");
        } else {
            header("Location: index.html?msg=error&action=consumo");
        }
    }
}

$conn->close();
?>