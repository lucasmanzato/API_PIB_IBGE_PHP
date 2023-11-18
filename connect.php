<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pib";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Não fechar a conexão, pois ela será usada no arquivo principal
?>
