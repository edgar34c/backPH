<?php
include_once './model/servico.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
class ControleServico{
    

    
     public function Cadastro(){
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
        
     }





     public function Login(){
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
     }

        public function Home(){
        $fun = new servico;
        $fun->inicio();
        $produtos = $_SESSION['pega_produt'];

        // Converte o array de produtos em JSON e envia como resposta para o cliente
        echo json_encode($produtos);
 

     }

     




}