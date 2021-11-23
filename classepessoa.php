<?php
Class Pessoa {
    // Abaixo precisamos criar uma váriavel 
    // e esta variavel precisa ser enxergada em toda classe
    // ela é private porque somente na classe ela sera enxergada.
    private $pdo ;
    // vamos ter aqui 6 funçoes
    // Quando esta classe é chamada
    // o primeiro a ser executado é o
    // construtor.

    //1-funcão é a 
    // conexao com o banco de dados
    public function __construct($dbname, $host,$user, $senha)
    {
     //Abaixo para acessar a variavel criada acima em private $pdo 
     //usamos a instrução $this    
     try {
        $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
     }
     catch(PDOException $e) {
        echo  "Erro com banco de dados : " .$e->getmessage();
        exit();
     }
     catch(Exception $e) {
        echo  "Erro  : " .$e->getmessage();
        exit();
     }               
    }

    // 2-funcao buscar dados e colocar no canto direito da tela.
    public function buscarDados() {
        //abaixo estamos definindo a váriavel $res como um array
        // porque caso a tabela retorne vazio , não dê erro porque 
        // vai retornar um array vazio.
        $res = array();
        $cmd = $this->pdo->query("Select * from pessoa order by nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;

    }
    
    //3-funcao cadastrar Pessoa
    public function cadastrarPessoa($nome, $telefone, $email) {
        // Antes de cadastrar verificar se já esta cadastrado
        // neste caso aqui verificando se já tem email cadastrado
        // $res = array();
        $cmd = $this->pdo->prepare("Select id from pessoa where email = :e ");
        $cmd->bindValue(":e",$email);
        $cmd->execute();
        //Abaixo se o rowcount > 0 então ja existe este email cadastrado , entao retorne False.
        if ($cmd->rowCount() > 0) {
           return false;
         //saindo pelo else quer dizer que não foi encontrado o email , entao pode cadastrar
         // retornando true  
        }else {
            $cmd = $this->pdo->prepare("insert into pessoa (nome,telefone,email) values(:n , :t , :e)");
            $cmd->bindValue(":n",$nome);
            $cmd->bindValue(":t",$telefone);
            $cmd->bindValue(":e",$email);
            $cmd->execute();
            return true ;

        }
        

    }
     //4-Funcao Excluir Pessoa
    public function excluirPessoa($id){
      $cmd = $this->pdo->prepare("delete from pessoa where id = :id ");
      $cmd->bindValue(":id",$id);
      $cmd->execute();
    } 
     //5-Buscar dados de uma pessoa especifica 
     //  ao clicar no botao editar
     public function buscardadosPessoa($id){
        $res = array(); 
        $cmd = $this->pdo->prepare("Select * from pessoa where id = :id ");
        $cmd->bindValue(":id",$id);
        $cmd->execute();    
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;  
    }

    //6-Funcão atualizar dados da Pessoa no banco de dados 
    //  atraves dp botao Atualizar
    public function atualizarDados($id,$nome,$telefone,$email){
        // antes de atualizar, verificar se email ja existe cadastrado
       //--- $cmd = $this->pdo->prepare("Select id from pessoa where email = :e ");
       //--- $cmd->bindValue(":e",$email);
       //--- $cmd->execute();
        //Abaixo se o rowcount > 0 então ja existe este email cadastrado , entao retorne False.
       //--- if ($cmd->rowCount() > 0) {
       //---    return false;
         //saindo pelo else quer dizer que não foi encontrado o email , entao pode cadastrar
         // retornando true  
       //-- }else {
        
            $cmd = $this->pdo->prepare("Update pessoa Set nome = :n , telefone = :t, email = :e where id = :id ");
            $cmd->bindValue(":n",  $nome);
            $cmd->bindValue(":t",  $telefone);
            $cmd->bindValue(":e",  $email);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
           //--- return true;

        //--}       
          


    }
    
}

?>