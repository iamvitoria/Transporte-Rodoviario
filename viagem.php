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
            <form id="formBusca" method="post" action="">
                <select id="origem" name="origem">
                    <?php echo obterOpcoesCidades(); ?>
                </select>

                <select id="destino" name="destino">
                    <?php echo obterOpcoesCidades(); ?>
                </select>
                <input type="date" id="ida" name="data_inicio" placeholder="Data de Ida">
                <input type="date" id="volta" name="data_fim" placeholder="Data de Volta">
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
        $result = $mysqli->query("SELECT viagem.*, origem.Nome AS cidade_origem, destino.Nome AS cidade_destino FROM viagem 
                                  JOIN cidade AS origem ON viagem.cidade_origem_ID = origem.ID
                                  JOIN cidade AS destino ON viagem.cidade_destino_ID = destino.ID");

        // Verifique se a consulta foi bem-sucedida
        if ($result->num_rows > 0) {
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo '<div class="corpo">';
                echo '<p>Origem: ' . $row['cidade_origem'] . '</p>';
                echo '<p>Destino: ' . $row['cidade_destino'] . '</p>';
                echo '<p>Saída: ' . $row['Saida'] . '</p>';
                echo '<p>Chegada: ' . $row['Chegada'] . '</p>';
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
            window.location.href = "compra.php";
        }
    </script>

    <?php
    // Função para obter opções de cidades do banco de dados
    function obterOpcoesCidades() {
        // Conectar ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'onibus');

        // Verificar a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        } 

        // Consultar cidades disponíveis
        $result = $mysqli->query("SELECT * FROM cidade");

        // Verificar se a consulta foi bem-sucedida
        if ($result->num_rows > 0) {
            // Construir as opções
            $options = "";
            while($row = $result->fetch_assoc()) {
                $options .= '<option value="' . $row['ID'] . '">' . $row['Nome'] . '</option>';
            }

            return $options;
        } else {
            return '<option value="" disabled>Nenhuma cidade disponível</option>';
        }

        $mysqli->close();
    }
    ?>
</body>
</html>
