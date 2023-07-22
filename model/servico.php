<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:3000");

class servico
{
    public $nome;
    public $preco;
    public $codproduto;
    public $descricao;
    public $imagem;
    public $usuario;
    public $senha;

    public function __construct()
    {
        include_once 'model/Conexao.php';
    }

    //Função para verificar login no banco de dados
    public function logar()
    {
        $con = Conexao::conectar();
        $query = "SELECT * FROM usuario WHERE nome = :usuario AND senha = :senha LIMIT 1";
        $cmd = $con->prepare($query);
        $cmd->bindParam(':usuario', $this->usuario); // Usando $this->usuario em vez de $usuario
        $cmd->bindParam(':senha', $this->senha); // Usando $this->senha em vez de $senha
        $cmd->execute();
        $rows = $cmd->rowCount();
        if ($rows > 0) {
            // A consulta retornou pelo menos uma linha
            $_SESSION['login'] = true;
        }else{
            $_SESSION['login'] = false;
        }
    }

    public function cadastrar()
    {
        $con = Conexao::conectar(); //conectar no BD
        //comando SQL para cadastrar (INSERT)
        $cmd = $con->prepare("INSERT INTO produtos (nome, preco, descricao, imagem,codproduto) 
            VALUES (:nome, :preco, :descricao,:imagem,:codproduto )");

        //enviando o valor dos parâmetros
        $cmd->bindParam(":nome",          $this->nome);
        $cmd->bindParam(":preco",            $this->preco);
        $cmd->bindParam(":descricao",    $this->descricao);
        $cmd->bindParam(":imagem",          $this->imagem);
        $cmd->bindParam(":codproduto", $this->codproduto);

        $cmd->execute(); //executar o comando
        //pega ultimo codproduto cadastrado no banco
        $this->codproduto = $con->lastInsertId();
    }



    //outra função para cadastrar as outras fotos no banco
    public function cadastrar2()
    {
        $con = Conexao::conectar(); //conectar no BD
        //comando SQL para cadastrar (INSERT)
        $cmd = $con->prepare("INSERT INTO imagens (imagens,codproduto) VALUE (:imagem, :codproduto) ");

        //enviando o valor dos parâmetros
        $cmd->bindParam(":imagem",          $this->imagem);
        $cmd->bindParam(":codproduto",          $this->codproduto);
        $cmd->execute(); //executar o comando
    }

    public function jp()
    {
        $con = Conexao::conectar();
        $cmd = $con->prepare("SELECT * FROM imagens JOIN produtos 
             ON produto.codproduto = imagens.codproduto 
             WHERE produto.codproduto = :codproduto");
        $cmd->bindParam(":codproduto", $this->codproduto);
        $cmd->execute();
        return $cmd->fetchAll(PDO::FETCH_OBJ);
    }



    //seleciona produtos que aparece na home
    public function inicio()
    {
        $con = Conexao::conectar();
        $query = 'SELECT * FROM produtos';
        $cmd = $con->query($query);
        $result = $cmd->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['pega_produt'] = $result;
    }

    //seleciona produto que aparece na descrição do produto
    public function info_prod($id)
    {
        $con = Conexao::conectar();
        $query = "SELECT * FROM produtos JOIN imagens
            ON produtos.codproduto = imagens.codproduto 
            WHERE produtos.codproduto = :id";
        $cmd = $con->prepare($query);
        $cmd->bindParam(":id", $id);
        $cmd->execute();
        $result = $cmd->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['pega_descri'] = $result;
    }

    public function consultar()
    {
        $con = Conexao::conectar(); //acessar o BD
        $cmd = $con->prepare("SELECT * FROM produtos "); //comando SQL
        $cmd->execute(); //executar o comando SQL
        $results =  $cmd->fetchAll(PDO::FETCH_OBJ);
        $_SESSION['pegas'] = $results;
    }

     //CRUD PH

     public function atualizado(){
            
        $con = Conexao::conectar();
        

        $cmd = $con->prepare("UPDATE produtos SET 
         nome = :nome,
         preco = :preco,
         descricao = :descricao
         WHERE codproduto = :codproduto");
         //enviando o valor dos parametros
         $cmd->bindParam(":codproduto", $this->codproduto);
         $cmd->bindParam(":nome", $this->nome);
         $cmd->bindParam(":preco", $this->preco);
         $cmd->bindParam(":descricao", $this->descricao);
         $cmd->execute();
          var_dump($cmd);
     }


     public function excluir(){
        $con = Conexao::conectar();

        $cmd = $con->prepare("DELETE FROM produtos 
        WHERE codproduto = :codproduto");
        //enviar valores
        $cmd->bindParam(":codproduto", $this->codproduto);
        $cmd->execute();
        $result = $cmd->fetchAll(PDO::FETCH_ASSOC);
         
        
     }

     public function retornar()
     {
         $con = Conexao::conectar();//acessar o BD
         $cmd = $con->prepare("SELECT * FROM produtos
         WHERE codproduto = :codproduto"); //comando SQL
         $cmd->bindParam(":codproduto", $this->codproduto);
         $cmd->execute();//executar o comando SQL
        
         
     }
}
