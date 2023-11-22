<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transporte Rodoviário</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="title-container">
        <div class="title">
            <h1>TRANSPORTE RODOVIÁRIO</h1>
        </div>
        <div class="search-container">
            <form id="formBusca" method="post" action="">
                <input type="text" id="origem" name="origem" placeholder="Origem">
                <input type="text" id="destino" name="destino" placeholder="Destino">
                <input type="date" id="ida" name="data_inicio" placeholder="Data de Ida">
                <input type="date" id="volta" name="data_fim" placeholder="Data de Volta">
                <input type="submit" value="Buscar">
            </form>
        </div>
    </div>

    <img src="viagem.webp" alt="viagem">

    <script>
        function redirecionarCompra() {
            // Redireciona para a página de compra (substitua "compra.php" pelo caminho correto)
            window.location.href = "viagem.php";
        }
    </script>
</body>
</html>
