<?php
include_once './model/servico.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


$cad = new servico();
$cad->nome = $_POST['nomeProd'];
$cad->preco = $_POST['preco'];
$cad->descricao = $_POST['descricao'];

$targetDir = "img/"; // Pasta de destino para os arquivos enviados
$uploadedFiles = $_FILES['images']; // Arquivos enviados
$numFiles = count($uploadedFiles['name']); // Número de arquivos enviados
$fileName = basename($uploadedFiles['name'][0]);
$targetFilePath = $targetDir . $fileName;

    // Tenta mover o arquivo enviado para a pasta de destino
if (move_uploaded_file($uploadedFiles['tmp_name'][0], $targetFilePath)) {
    $cad->imagem = $fileName;
    $cad->cadastrar();
    $response = ['msg' => 'Cadastro concluído com sucesso'];
} else {
    $response = ['msg' => 'Erro ao enviar o cadastro!'];
}

        // Loop através de todos os arquivos enviados
        for ($i = 1; $i < $numFiles; $i++) {
            $fileName = basename($uploadedFiles['name'][$i]); // Nome do arquivo
            $targetFilePath = $targetDir . $fileName; // Caminho completo do arquivo de destino
            // Tenta mover o arquivo enviado para a pasta de destino
            if (move_uploaded_file($uploadedFiles['tmp_name'][$i], $targetFilePath)) {
                $cad->imagem = $fileName;
                //colocar na tabela nova
                $cad->cadastrar2();
                $response = ['msg' => 'Cadastro concluído com sucesso'];
            } else {
                $response = ['msg' => 'Erro ao enviar o cadastro!'];
            }
        }

echo json_encode($response);
?>
