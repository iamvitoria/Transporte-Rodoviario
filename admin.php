<?php
/* Permite a administração das informações do sistema, como cadastro de novas viagens, atualização de horários, configuração de descontos, etc. */
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

    <div class="adicionar-viagem">
        <h2>Adicionar Nova Viagem</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="origem_nova">Origem:</label>
                <select id="origem_nova" name="origem_nova">
                    <?php echo obterOpcoesCidades(); ?>
                </select>
            </div>

            <div class="form-group">
                <label for="destino_nova">Destino:</label>
                <select id="destino_nova" name="destino_nova">
                    <?php echo obterOpcoesCidades(); ?>
                </select>
            </div>

            <div class="form-group">
                <label for="saida">Saída:</label>
                <input type="datetime-local" id="saida" name="saida" required>
            </div>

            <div class="form-group">
                <label for="chegada">Chegada:</label>
                <input type="datetime-local" id="chegada" name="chegada" required>
            </div>

            <div class="form-group">
                <label for="horario">Horário:</label>
                <input type="time" id="horario" name="horario" required>
            </div>

            <div class="form-group">
                <label for="parada">Parada:</label>
                <select id="parada" name="parada">
                    <?php echo obterOpcoesCidades(); ?>
                </select>
            </div>

            <div class="form-group">
                <label for="onibus">Ônibus:</label>
                <select id="onibus" name="onibus" required>
                    <?php echo obterOpcoesOnibus(); ?>
                </select>
            </div>

            <input type="submit" name="adicionar_viagem" value="Adicionar Viagem">
        </form>
    </div>

    <script>
        function redirecionarCompra() {
            window.location.href = "compra.php";
        }
    </script>

    <?php
    // Processar o formulário de adição de viagem
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_viagem'])) {
        // Obter os dados do formulário
        $origem_nova = $_POST['origem_nova'];
        $destino_nova = $_POST['destino_nova'];
        $saida = $_POST['saida'];
        $chegada = $_POST['chegada'];
        $horario = $_POST['horario'];
        $parada = $_POST['parada'];
        $onibus = $_POST['onibus'];

        // Conectar ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'mydb');

        // Verificar a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        }

        // Inserir a nova viagem no banco de dados
        $inserir_viagem_query = "INSERT INTO viagem (cidade_origem, cidade_destino, saida, chegada, horario, parada_id, Onibus_ID) VALUES ('$origem_nova', '$destino_nova', '$saida', '$chegada', '$horario', '$parada', '$onibus')";

        if ($mysqli->query($inserir_viagem_query)) {
            echo "Viagem adicionada com sucesso.";
        } else {
            echo "Erro ao adicionar a viagem: " . $mysqli->error;
        }

        $mysqli->close();
    }

    // Função para obter opções de cidades do banco de dados
    function obterOpcoesCidades() {
        // Conectar ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'mydb');

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
    function obterOpcoesOnibus() {
        // Conectar ao banco de dados
        $mysqli = new mysqli('localhost', 'root', '', 'mydb');

        // Verificar a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        } 

        // Consultar ônibus disponíveis
        $result = $mysqli->query("SELECT * FROM onibus");

        // Verificar se a consulta foi bem-sucedida
        if ($result->num_rows > 0) {
            // Construir as opções
            $options = "";
            while($row = $result->fetch_assoc()) {
                $options .= '<option value="' . $row['ID'] . '">' . $row['Modelo'] . ' (Poltrona ' . $row['Poltrona'] . ')</option>';
            }

            return $options;
        } else {
            return '<option value="" disabled>Nenhum ônibus disponível</option>';
        }

        $mysqli->close();
    }
    ?>
</body>
</html>
