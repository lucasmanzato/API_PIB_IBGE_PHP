<?php
include 'connect.php'; // Incluir o arquivo de conexão

function buscar($pais) {
    $url = "https://servicodados.ibge.gov.br/api/v1/paises/{$pais}/indicadores/77827";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $dados = json_decode($response, true);
        // Filtrar para retornar dados apenas do país desejado
        if(isset($dados[0]) && $dados[0]['series']){
            $paises = $dados[0]['series'];
            foreach ($paises as $item) {
                if ($item['pais']['id'] === $pais) {
                    return $item['serie']; // Retorna apenas os dados do país específico
                }
            }
        }
    }

    return null; // Retorna nulo se não encontrar os dados
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pais'])) {
    $pais = $_POST['pais'];

    // Registrar a busca no banco de dados
    $stmt = $conn->prepare("INSERT INTO historico (PAIS_BUSCA) VALUES (?)");
    $stmt->bind_param("s", $pais);
    $stmt->execute();
    $stmt->close();

    // Obter os dados da API
    $resultado = buscar($pais);

    // Convertendo o resultado para JSON
    echo json_encode($resultado);
    exit;
}

$conn->close();
?>
