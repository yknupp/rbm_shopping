<?php
require_once "config.php";

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <?php

    // Listar todos os clientes
    $sql = "SELECT * FROM users ";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();

    echo "<h2>Lista de Clientes</h2>";
    echo "<table class='table'>";
    echo "<thead class='thead-dark'><tr><th>ID</th><th>Nome</th><th>CPF</th><th>Cupons</th><th>Números da Sorte</th></tr></thead>";
    while ($row = $stmt->fetch()) {
        // Listar todos os cupons de cada cliente
        $sql1 = "SELECT * FROM cupons  WHERE cpf=".$row['cpf'];
        $stmt1 = $pdo->prepare($sql1);
        $result1 = $stmt1->execute();
        $cupons = '';
        $numeroSorte = '';
        while ($row1 = $stmt1->fetch()) {
            $cupons .= $row1["codigo"] . "<br>";
            $numeroSorte .= $row1["numeroSorte"] . "<br>";
        }
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["cpf"] . "</td><td> ". $cupons . "</td><td> " . $numeroSorte . " </td></tr>";
    }
    echo "</table>";

    // Query de sortear numero da sorte aleatorio
    // SELECT `numeroSorte` FROM cupons ORDER BY RAND() LIMIT 1


    
    /* 
    // Ver os cupons de um cliente específico
    $client_id = $_GET["cpf"];
    $sql = "SELECT * FROM cupons WHERE cpf = '$username'";
    $result = mysqli_query($conn, $sql);
    echo "<h2>Cupons do Cliente</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Cupom</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row["id"]. "</td><td>" . $row["coupon"]. "</td></tr>";
    }
    echo "</table>";
    // Ver os números da sorte
    $sql = "SELECT * FROM lucky_numbers";
    $result = mysqli_query($conn, $sql);
    echo "<h2>Números da Sorte</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Número</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row["id"]. "</td><td>" . $row["number"]. "</td></tr>";
    }
    echo "</table>";
    // Realizar o sorteio com base nos números da sorte
    $sql = "SELECT * FROM lucky_numbers ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "<h2>Resultado do Sorteio</h2>";
    echo "<p>O número sorteado é: " . $row["number"]. "</p>";
    */
    ?>


</body>

</html>