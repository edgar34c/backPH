<?php
 
include_once 'model/Conexao.php';
 
include_once 'model/servico.php';

//minha url
define('URL','http://localhost/backPH/');

if(isset($_GET['url']))
{
    $url = explode('/', $_GET['url']);
    switch($url[0])
    {
        //rotas para categoria

        case 'home':
            $usu->ControleServico();
            $usu->home();
            break;
        case'cadastro':
            $usu->ControleServico();
            $usu->Cadastro(); 
            break;
        case'login':
            $usu->ControleServico();
            $usu->login();
            break;       
              


        
        default:
            echo "página não encontrada<br>
            Verificar se existe a rota criada<br>
            Tentando acessar a rota: $url[0]";
            //poderá depois abrir uma página de aviso
        break;
        

    }

}
else
{
    //abrir a página inicial
    
}


?>