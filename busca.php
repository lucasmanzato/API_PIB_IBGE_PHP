<?php
include 'connect.php'; // Incluir o arquivo de conexão

function buscar($pais)
{
    $url = "https://servicodados.ibge.gov.br/api/v1/paises/{$pais}/indicadores/77827";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_0);
    $response = curl_exec($ch);
    // Verifica se ocorreu algum erro na requisição cURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);

        // Retorna a mensagem de erro
        return 'Erro no cURL: ' . $error_msg;
    }
    curl_close($ch);
    if ($response) {
        $dados = json_decode($response, true);
        // Filtrar dados apenas do país desejado
        if (isset($dados[0]) && $dados[0]['series']) {
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