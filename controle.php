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
        $mysqli = new mysqli('localhost', 'root', '', 'mydb');

        // Verifique a conexão
        if ($mysqli->connect_error) {
            die("Falha na conexão: " . $mysqli->connect_error);
        } 

        // Verifique se o formulário foi enviado e se uma passagem deve ser excluída
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir_passagem'])) {
            $passagem_id = $_POST['passagem_id'];

            // Execute a consulta para excluir a passagem
            $delete_query = "DELETE FROM passagem WHERE ID = $passagem_id";
            if ($mysqli->query($delete_query)) {
                echo "Passagem excluída com sucesso.";
            } else {
                echo "Erro ao excluir a passagem: " . $mysqli->error;
            }
        }

        // Construa a consulta SQL com base nos filtros do formulário
        $query = "SELECT passagem.*, 
                          cidade_origem.Nome AS cidade_origem, 
                          cidade_destino.Nome AS cidade_destino 
                          FROM passagem 
                          LEFT JOIN viagem ON passagem.viagem_ID = viagem.ID
                          LEFT JOIN cidade AS cidade_origem ON viagem.cidade_origem = cidade_origem.ID
                          LEFT JOIN cidade AS cidade_destino ON viagem.cidade_destino = cidade_destino.ID
                          WHERE 1"; // Condição sempre verdadeira para incluir todas as passagens

        // Adicione condições com base nos filtros do formulário
        if (!empty($_POST['origem'])) {
            $query .= " AND viagem.cidade_origem = " . intval($_POST['origem']);
        }

        if (!empty($_POST['destino'])) {
            $query .= " AND viagem.cidade_destino = " . intval($_POST['destino']);
        }

        if (!empty($_POST['data_inicio'])) {
            $query .= " AND passagem.data >= '" . $_POST['data_inicio'] . "'";
        }

        if (!empty($_POST['data_fim'])) {
            $query .= " AND passagem.data <= '" . $_POST['data_fim'] . "'";
        }

        // Execute a consulta
        $result = $mysqli->query($query);

        // Verifique se a consulta foi bem-sucedida
        if ($result && $result->num_rows > 0) {
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo '<div class="corpo">';
                echo '<p>Assento: ' . $row['Assento'] . '</p>';
                echo '<p>Preço: ' . $row['Preco'] . '</p>';
                echo '<p>Passageiro: ' . $row['compra_passageiro_ID'] . '</p>';
                echo '<p>Origem: ' . $row['viagem_cidade_origem'] . '</p>';
                echo '<p>Destino: ' . $row['viagem_cidade_destino'] . '</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="passagem_id" value="' . $row['ID'] . '">';
                echo '<input type="submit" name="excluir_passagem" value="Excluir">';
                echo '<input type="submit" name="alterar_passagem" value="Alterar">';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "Nenhuma passagem disponível ou erro na consulta: " . $mysqli->error;
        }

        $mysqli->close();
        ?>
    </div>

    <?php
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
    ?>
</body>
</html>
