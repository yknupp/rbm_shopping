<?php
require_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>

<?php

// Listar todos os clientes
$sql = "SELECT * FROM clients";
$result = mysqli_query($conn, $sql);

echo "<h2>Lista de Clientes</h2>";
echo "<table>";
echo "<tr><th>ID</th><th>Nome</th><th>E-mail</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row["id"]. "</td><td>" . $row["nome"]. "</td><td>" . $row["email"]. "</td></tr>";
}
echo "</table>";

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

?>


</body>
</html>