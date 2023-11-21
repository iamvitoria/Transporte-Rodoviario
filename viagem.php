<!DOCTYPE html>
<html>
<head>
    <title>Transporte Rodoviário</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="title-container">
        <div class="title">
            <h1>TRANSPORTE RODOVIÁRIO</h1>
        </div>
        <div class="search-container">
            <form id="formBusca">
                <input type="text" id="origem" name="origem" placeholder="Origem">
                <input type="text" id="destino" name="destino" placeholder="Destino">
                <input type="date" id="ida" name="ida" placeholder="Data de Ida">
                <input type="date" id="volta" name="volta" placeholder="Data de Volta">
                <input type="submit" value="Buscar">
            </form>
        </div>
    </div>

    <div class="viagem">
        <?php
        // Conecte-se ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'mydb');

        // Verifique a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        } 

        // Consulte o banco de dados para obter as viagens e os nomes das cidades
        $result = $mysqli->query("SELECT viagem.*, cidade.Nome AS cidade_nome FROM viagem JOIN cidade ON viagem.cidade_ID = cidade.ID");

        // Verifique se a consulta foi bem-sucedida
        if ($result->num_rows > 0) {
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo '<div class="corpo">';
                echo '<p>Destino: ' . $row['cidade_nome'] . '</p>'; // Agora exibe o nome da cidade
                echo '<p>Saída: ' . $row['Saida'] . '</p>';
                echo '<p>Chegada: ' . $row['Chegada'] . '</p>';
                // Você precisará juntar a tabela 'passagem' para obter o preço da passagem
                echo '<input type="submit" value="Selecionar" onclick="redirecionarCompra()">';
                echo '</div>';
            }
        } else {
            echo "Nenhuma viagem disponível";
        }

        $mysqli->close();
        ?>
    </div>

    <script>
        function redirecionarCompra() {
            // Redireciona para a página de compra (substitua "compra.html" pelo caminho correto)
            window.location.href = "compra.php";
        }
    </script>
</body>
</html>
