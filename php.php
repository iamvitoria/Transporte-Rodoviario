<?php
// Conectar ao banco de dados (substitua os valores pelos seus)
$servername = "seu_servidor";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Suponha que você tenha duas datas selecionadas pelo usuário
$dataInicio = $_POST['data_inicio'];  // Substitua por seu método de obter as datas
$dataFim = $_POST['data_fim'];

// Consulta parametrizada para buscar viagens entre as datas
$sql = "SELECT * FROM viagem WHERE DataSaida BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $dataInicio, $dataFim);

// Executar a consulta
$stmt->execute();
$result = $stmt->get_result();

// Exibir os resultados
while ($row = $result->fetch_assoc()) {
    // Faça o que for necessário com os resultados
    echo "ID: " . $row['ID'] . " Saida: " . $row['DataSaida'] . "<br>";
}

// Fechar a declaração e a conexão
$stmt->close();
$conn->close();
?>