<?php
/* Exibe uma lista de passagens emitidas, talvez filtráveis por data, origem, destino, etc.
Permite realizar operações de controle, como cancelamento de passagens. */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transporte Rodoviário</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
 <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .dropdown-content a {
            display: block;
            color: black;
            text-decoration: none;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropbtn {
            margin-left: 10px;
            margin-top: 10px;
            color: #5024D1;
            padding: 16px;
            border: none;
            cursor: pointer;
            margin-right: 100px;
            background: #F5F5F5; 
            border-radius: 5px; 
        }
    </style>
</head>
<body>
    <div class="dropdown">
    <button class="dropbtn">MENU</button>
      <div class="dropdown-content">
        <a href="index.php">Início</a>
        <a href="viagem.php">Viagem</a>
        <a href="controle.php">Controle</a>
        <a href="compra.php">Compra</a>
        <a href="admin.php">Admin</a>
      </div>
    </div>
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
        $mysqli = new mysqli('localhost', 'root', '', 'test');

        // Verifique a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        } 

        // Consulte o banco de dados para obter as passagens e os nomes das cidades
        $result = $mysqli->query("SELECT passagem.*, 
                          cidade_origem.Nome AS cidade_origem, 
                          cidade_destino.Nome AS cidade_destino 
                          FROM passagem 
                          LEFT JOIN viagem ON passagem.viagem_ID = viagem.ID
                          LEFT JOIN cidade AS cidade_origem ON viagem.cidade_origem = cidade_origem.ID
                          LEFT JOIN cidade AS cidade_destino ON viagem.cidade_destino = cidade_destino.ID");


        // Verifique se a consulta foi bem-sucedida
        if ($result && $result->num_rows > 0) {
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo '<div class="corpo">';
                echo '<p>Assento: ' . $row['Assento'] . '</p>';
                echo '<p>Preço: ' . $row['Preco'] . '</p>';
                echo '<p>Passageiro: ' . $row['compra_passageiro_ID'] . '</p>';
                echo '<p>Cidade de Origem: ' . $row['cidade_origem'] . '</p>';
                echo '<p>Cidade de Destino: ' . $row['cidade_destino'] . '</p>';
                echo '<input type="submit" value="Cancelar" onclick="cancelarPassagem(' . $row['ID'] . ')">';
                echo '</div>';
            }
        } else {
            echo "Nenhuma passagem disponível ou erro na consulta: " . $mysqli->error;
        }

        $mysqli->close();
        ?>
    </div>

    <script>
        function cancelarPassagem(passagemID) {
            // Adicione aqui a lógica para cancelar a passagem no lado do cliente ou chame uma função no backend
            alert("Passagem com ID " + passagemID + " cancelada com sucesso!");
        }
    </script>

    <?php
    // Função para obter opções de cidades do banco de dados
    function obterOpcoesCidades() {
        // Conectar ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'test');

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