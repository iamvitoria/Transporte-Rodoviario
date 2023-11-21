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


    <div class="compra">
        <div class="search">
            <img src="C:\xampp\htdocs\Transporte-Rodoviario\onibus.png" alt="Onibus"> 
                <form id="formCompra">
                    <input type="text" id="nome" name="nome" placeholder="Nome">
                    <input type="text" id="RG" name="RG" placeholder="RG">
                    <input type="text" id="poltrona" name="poltrona" placeholder="Poltrona">
                    <input type="text" id="pagamento" name="pagamento" placeholder="Forma de Pagamento">
                    <input type="submit" value="Comprar">
                </form>
        </div>
    </div>
</body>
</html>