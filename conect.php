<?php 
// Configurações de conexão
$host = 'localhost';
$user = 'root';
$senha = "";
$database = "genuina";

// Tenta estabelecer a conexão
$conn = mysqli_connect($host, $user, $senha, $database);

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    // Mensagem de erro detalhada
    die("Erro na conexão: " . mysqli_connect_error());
} else {
    echo "Conectado com sucesso ao banco de dados!";
}

?>