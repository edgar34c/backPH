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

    if($_SERVER['REQUEST_METHOD']=="OPTIONS"){
        resposta(200, true, '');
    }

    if($_SERVER['REQUEST_METHOD'] !="POST"){
        resposta(400, false, 'Metodo Inválido');
    }

    $json = file_get_contents('php://input');

    if(!$json){
        resposta(400, false, "Corpo da requisição não encontrado");
    }
        
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $dados = json_decode($json);
    $log = new servico();
    $log->usuario = $dados->usuario;
    $log->senha = $dados->senha;
    $log->logar();

    if (isset($_SESSION['login'])) {
        if ($_SESSION['login'] == true) {

            resposta(200, true, "Login validado com sucesso");
        } else {
            resposta(400, false, "Usuário ou senha inválidos!");
        }
    }
    
    
?>