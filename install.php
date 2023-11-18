<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pib";

// Criar conexão
$conn = new mysqli($servername, $username, $password);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Criar banco de dados se não existir
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados '$dbname' criado com sucesso ou já existente.<br>";
} else {
    echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
    $conn->close();
    exit();
}

// Selecionar o banco de dados
$conn->select_db($dbname);

// Verificar se a tabela 'historico' já existe
$checkTable = $conn->query("SHOW TABLES LIKE 'historico'");
if ($checkTable && $checkTable->num_rows > 0) {
    echo "A instalação já foi realizada anteriormente.<br>";
    echo "Você será redirecionado para a página inicial em 5 segundos.";
    header("Refresh:5; url=index.html");
    $conn->close();
    exit();
}

// SQL para criar a tabela 'historico'
$sql = "CREATE TABLE IF NOT EXISTS historico (
    ID_Consulta INT AUTO_INCREMENT PRIMARY KEY,
    PAIS_BUSCA TEXT NOT NULL,
    DataHora_Consulta DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela 'historico' criada com sucesso ou já existente.<br>";
} else {
    echo "Erro ao criar tabela: " . $conn->error . "<br>";
}

echo "Você será redirecionado para a página inicial em 5 segundos.";
header("Refresh:5; url=index.html");
$conn->close();
?>
