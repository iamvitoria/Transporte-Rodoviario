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

            // Obter os dados do formulário de compra
            $nome = $_POST["nome"];
            $cpf = $_POST["CPF"];
            $poltrona = $_POST["poltrona"];
            $pagamento = $_POST["pagamento"];

            // Inserir dados no banco de dados
            $sql = "INSERT INTO compra (Nome, CPF, Poltrona, Pagamento) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssss", $nome, $cpf, $poltrona, $pagamento);

            if ($stmt->execute()) {
                echo '<p>Compra realizada com sucesso!</p>';
            } else {
                echo '<p>Erro ao realizar a compra: ' . $stmt->error . '</p>';
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo '<p>Todos os campos do formulário são obrigatórios.</p>';
        }
    }

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
