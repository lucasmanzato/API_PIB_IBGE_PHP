<?php
include 'connect.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Prepara a consulta SQL
    $sql = "SELECT * FROM historico ORDER BY DataHora_Consulta DESC";
    $result = $conn->query($sql);

    $buscas = [];

    if ($result->num_rows > 0) {
        // Armazena os dados de cada linha em um array
        while($row = $result->fetch_assoc()) {
            $buscas[] = $row;
        }
    }

    // Fecha a conexão com o banco de dados
    $conn->close();

    // Retorna os dados como JSON
    header('Content-Type: application/json');
    echo json_encode($buscas);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "TRUNCATE TABLE historico";

    if ($conn->query($sql) === TRUE) {
        echo "Histórico de buscas apagado com sucesso.";
    } else {
        echo "Erro ao apagar o histórico: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>