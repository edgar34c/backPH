<?php
include_once './model/servico.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$fun = new servico;
$fun->inicio();
$produtos = $_SESSION['pega_produt'];

// Converte o array de produtos em JSON e envia como resposta para o cliente
echo json_encode($produtos);
?>
