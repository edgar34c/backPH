<?php
include_once './model/servico.php';

function resposta($codigo, $ok, $msg){
    // Retorna a resposta como JSON
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
    header('Content-Type: application/json');
    http_response_code($codigo);
    $jsonRes = json_encode(['ok' => $ok, 'msg' => $msg]);
    echo($jsonRes);
    die;
}

if($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
    resposta(200, true, '');
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
    resposta(400, false, 'Método Inválido');
}

$json = file_get_contents('php://input');

if(!$json){
    resposta(400, false, "Corpo da requisição não encontrado");
}

$json = file_get_contents('php://input');
$data = json_decode($json);

$cad = new servico();
if (isset($data->nomeProd)) {
    $cad->nome = $data->nomeProd;
    $cad->preco = $data->preco;
    $cad->descricao = $data->descricao;
} elseif(isset($_FILES['file'])) {
    $arquivos = $_FILES['files'];
    $targetDir = "img/"; // Pasta de destino para os arquivos enviados
    $numFiles = count($arquivo); // Número de arquivos enviados
    if (isset($arquivo[0]->tmp_name) && isset($arquivo[0]->tmp_name)) {
        $fileName = basename($arquivo[0]->name);
        $targetFilePath = $targetDir . $fileName;
        // Tenta mover o arquivo enviado para a pasta de destino
        if (move_uploaded_file($arquivo[0]->tmp_name, $targetFilePath)) {
            $cad->imagem = $fileName;
            $cad->cadastrar();
            resposta(200, true, "Login validado com sucesso");
        } else {
            resposta(400, false, "Erro ao enviar os arquivos!");
        }
        // Loop através de todos os arquivos enviados
        for ($i = 0; $i < $numFiles; $i++) {
            $fileName = ($arquivo[$i]); // Nome do arquivo
            $targetFilePath = $targetDir . $fileName; // Caminho completo do arquivo de destino
            // Tenta mover o arquivo enviado para a pasta de destino
            if (move_uploaded_file($arquivo[$i]->tmp_name, $targetFilePath)) {
                $cad->imagem = $fileName;
                $cad->cadastrar2();
            } else {
                resposta(400, false, "Erro ao enviar os arquivos!");
            }
        }
    } else {
        resposta(400, false, "Erro ao enviar os arquivos!");
    }
    
    resposta(200, true, "Cadastro realizado com sucesso");

}


/*
$cad = new servico();

$cad->nome = $dados->nomeProd;
$cad->preco = $dados->preco;
$cad->descricao = $dados->descricao;
$arquivo = $dados->arquivos;
$arquivos = json_encode($arquivo);
var_dump($arquivos);

$targetDir = "img/"; // Pasta de destino para os arquivos enviados
$numFiles = count($arquivo); // Número de arquivos enviados

    $targetDir = "img/"; // Pasta de destino para os arquivos enviados
    $numFiles = count($arquivo); // Número de arquivos enviados
    if (isset($arquivo[0]->tmp_name) && isset($arquivo[0]->tmp_name)) {
        $fileName = basename($arquivo[0]->name);
        $targetFilePath = $targetDir . $fileName;
        // Tenta mover o arquivo enviado para a pasta de destino
        if (move_uploaded_file($arquivo[0]->tmp_name, $targetFilePath)) {
            $cad->imagem = $fileName;
            $cad->cadastrar();
            resposta(200, true, "Login validado com sucesso");
        } else {
            resposta(400, false, "Erro ao enviar os arquivos!");
        }
        // Loop através de todos os arquivos enviados
        for ($i = 0; $i < $numFiles; $i++) {
            $fileName = ($arquivo[$i]); // Nome do arquivo
            $targetFilePath = $targetDir . $fileName; // Caminho completo do arquivo de destino
            // Tenta mover o arquivo enviado para a pasta de destino
            if (move_uploaded_file($arquivo[$i]->tmp_name, $targetFilePath)) {
                $cad->imagem = $fileName;
                $cad->cadastrar2();
            } else {
                resposta(400, false, "Erro ao enviar os arquivos!");
            }
        }
    } else {
        resposta(400, false, "Erro ao enviar os arquivos!");
    }
    
    resposta(200, true, "Cadastro realizado com sucesso");
    */
?>