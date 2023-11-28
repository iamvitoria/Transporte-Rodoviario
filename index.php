<?php
/* Responsável por exibir um formulário de busca de viagens com campos como origem, destino, data de ida, data de volta, etc.
Exibe os resultados da busca, mostrando informações sobre as viagens disponíveis.
Possui links ou botões para redirecionar para a página de viagem.php com os parâmetros da viagem selecionada. */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transporte Rodoviário</title>
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
            <form id="formBusca" method="get" action="viagem.php">
                <select id="origem" name="origem">
                    <?php echo obterOpcoesCidades('origem'); ?>
                </select>

                <select id="destino" name="destino">
                    <?php echo obterOpcoesCidades('destino'); ?>
                </select>

                <input type="date" id="ida" name="data_inicio" placeholder="Data de Ida">
                <input type="date" id="volta" name="data_fim" placeholder="Data de Volta">
                <input type="submit" value="Buscar">
            </form>
        </div>
    </div>

    <img src="viagem.webp" alt="viagem">

    <?php
    // Função para obter opções de cidades do banco de dados
    function obterOpcoesCidades($tipo) {
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
