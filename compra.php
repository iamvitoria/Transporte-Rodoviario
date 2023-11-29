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

    <div class="compra">
        <div class="search">
            <img src="onibus.png" alt="Onibus">
            <form id="formCompra" method="post" action="">
                <input type="text" id="nome" name="nome" placeholder="Nome" required>
                <input type="text" id="CPF" name="CPF" placeholder="CPF" required>
                <input type="text" id="celular" name="celular" placeholder="Telefone" required>
                <select id="poltrona" name="poltrona" required>
                    <option value="" disabled selected>Poltrona</option>
                    <!-- Adicione as opções de poltrona aqui -->
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <!-- etc. -->
                </select>
                <select id="pagamento" name="pagamento" required>
                    <option value="" disabled selected>Forma de Pagamento</option>
                    <!-- Adicione as opções de pagamento aqui -->
                    <option value="credito">Cartão de Crédito</option>
                    <option value="debito">Cartão de Débito</option>
                    <option value="dinheiro">Dinheiro</option>
                    <!-- etc. -->
                </select>
                <input type="text" id="desconto" name="desconto" placeholder="Desconto">
                <input type="submit" value="Comprar">
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se os campos obrigatórios foram preenchidos
        if (!empty($_POST["nome"]) && !empty($_POST["CPF"]) && !empty($_POST["poltrona"]) && !empty($_POST["pagamento"])) {
            // Conectar ao banco de dados
            $mysqli = new mysqli('localhost', 'root', '', 'mydb');

            // Verificar a conexão
            if ($mysqli->connect_error) {
                die("Falha na conexão: " . $mysqli->connect_error);
            } 

            // Iniciar uma transação para garantir consistência entre as inserções
            $mysqli->begin_transaction();

            try {
                // Inserir dados na tabela `passageiro`
                $stmt_passageiro = $mysqli->prepare("INSERT INTO passageiro (Nome, CPF, Telefone) VALUES (?, ?, ?)");
                $stmt_passageiro->bind_param("sss", $_POST["nome"], $_POST["CPF"], $_POST["celular"]);

                if ($stmt_passageiro->execute()) {
                    // Obter o ID gerado na inserção anterior
                    $passageiro_ID = $mysqli->insert_id;

                    // Inserir dados na tabela `compra`
                    $stmt_compra = $mysqli->prepare("INSERT INTO compra (passageiro_ID, desconto_ID) VALUES (?, ?)");
                    $stmt_compra->bind_param("ss", $passageiro_ID, $_POST["desconto"]);

                    if ($stmt_compra->execute()) {
                        // Commit na primeira transação
                        $mysqli->commit();

                        // Iniciar outra transação para operações em passagem
                        $mysqli->begin_transaction();

                        try {
                            // Obter o ID gerado na inserção anterior
                            $compra_ID = $mysqli->insert_id;

                            // Inserir dados na tabela `passagem`
                            $stmt_passagem = $mysqli->prepare("INSERT INTO passagem (Assento, Preco, compra_ID, viagem_ID, viagem_cidade_origem, viagem_cidade_destino) VALUES (?, ?, ?, ?, ?, ?)");

                            // Definir os parâmetros da terceira inserção
                            $assento = $_POST["poltrona"];
                            $preco = 0; // Defina o preço conforme necessário
                            $viagem_ID = 0; // Defina o ID da viagem conforme necessário
                            $viagem_cidade_origem = 0; // Defina o ID da cidade de origem conforme necessário
                            $viagem_cidade_destino = 0; // Defina o ID da cidade de destino conforme necessário

                            $stmt_passagem->bind_param("ssiiii", $assento, $preco, $compra_ID, $viagem_ID, $viagem_cidade_origem, $viagem_cidade_destino);

                            if ($stmt_passagem->execute()) {
                                // Commit na segunda transação
                                $mysqli->commit();
                                echo '<p>Compra realizada com sucesso!</p>';
                            } else {
                                throw new Exception("Erro ao inserir dados na tabela 'passagem': " . $stmt_passagem->error);
                            }
                        } catch (Exception $e) {
                            // Em caso de erro, rollback na segunda transação
                            $mysqli->rollback();
                            echo '<p>Erro ao realizar a compra: ' . $e->getMessage() . '</p>';
                        }
                    } else {
                        throw new Exception("Erro ao inserir dados na tabela 'compra': " . $stmt_compra->error);
                    }
                } else {
                    throw new Exception("Erro ao inserir dados na tabela 'passageiro': " . $stmt_passageiro->error);
                }
            } catch (Exception $e) {
                // Em caso de erro, rollback na primeira transação
                $mysqli->rollback();
            }

            // Fechar as declarações preparadas
            if (isset($stmt_passageiro)) {
                $stmt_passageiro->close();
            }

            if (isset($stmt_compra)) {
                $stmt_compra->close();
            }

            if (isset($stmt_passagem)) {
                $stmt_passagem->close();
            }

            // Fechar a conexão
            $mysqli->close();
        } else {
            echo '<p>Todos os campos do formulário são obrigatórios.</p>';
        }
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
    ?>

    <script>
        window.onload = function() {
            var selects = document.querySelectorAll('.compra select');
            selects.forEach(function(select) {
                var firstOption = select.querySelector('option');
                firstOption.disabled = true;
            });
        };
    </script>

</body>

</html>