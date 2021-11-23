<?php
// Abaixo vamos comecar chamando a class pessoa 
require_once 'classepessoa.php';
$p = new Pessoa("crudpdo", "localhost","root", "");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pessoas</title>
    <link rel="stylesheet" href="estilo.css">

</head>
<body>
    <?php
    // abaixo verifica atraves de um campo qualquer se 
    // digitou e enviou os dados do form

    if(isset($_POST['nome']))
    {
       //aqui é se clicou no botao cadastrar ou Editar
        //Sempre usar a instrução addslashes para pegar os campos 
        // pois é uma proteção contra informações maliciosas.
       // echo "passei aqui" ;
        //-------------clicou no botao Editar -------------------- 
        if(isset($_GET['id_up']) && !empty($_GET['id_up'])) {
            $id_upd     = addslashes($_GET['id_up']);
            $nome       = addslashes($_POST['nome']);
            
            $telefone   = addslashes($_POST['telefone']);
            $email      = addslashes($_POST['email']);
            if (!empty($nome) && !empty($telefone) && !empty($email)){
               //Vamos Cadastrar as Pessoas chamando a funcao para cadastrar pessoa
               // se o retorno for false ja existe este email, Já foi cadastrado.
               // se o retorno for true entao pode cadastrar.
               // echo "vou atualizar";
               $p->atualizarDados($id_upd, $nome, $telefone, $email);
               header("location:index.php");
                        
            }else{
                ?>
                <div class="aviso">
                     <img src="aviso.jpg">
                     <h4>"Preencha todos os campos"</h4>
                </div>               
                <?php   

            } 

       }
       //---------------clicou no botao cadastrar---------------
       else{
            $nome       = addslashes($_POST['nome']);
            $telefone   = addslashes($_POST['telefone']);
            $email      = addslashes($_POST['email']);
            if (!empty ($nome) && !empty($telefone) && !empty($email)){
            //Vamos Cadastrar as Pessoas chamando a funcao para cadastrar pessoa
            // se o retorno for false ja existe este email, Já foi cadastrado.
            // se o retorno for true entao pode cadastrar.
            if(!$p->cadastrarPessoa($nome, $telefone, $email))
            {
                ?>
                <div class="aviso">
                     <img src="aviso.jpg">
                     <h4>"Email já esta cadastrado!"</h4>
                </div>   
              
              <?php
               
            }


            }else{
                ?>
                <div class="aviso">
                     <img src="aviso.jpg">
                     <h4>"Preencha todos os campos"</h4>
                </div>   
              
              <?php
               

            }

       }

        
    }
    ?>
    
    <?php
      //Abaixo será executado quando for clicado no botao editar.
      if(isset($_GET['id_up'])) {
       $id_update = addslashes($_GET['id_up']);
       $res = $p->buscardadospessoa($id_update);

      }
    ?>

    <section id="esquerda">
       <form method="POST">
        <h2>Cadastrar Pessoas</h2>
        <label for="nome" >Nome</label>
        <input type="text" name="nome" id="nome" value ="<?php if(isset($res)){echo $res['nome'];}?>">
        <label for="telefone">Telefone</label>
        <input type="text" name="telefone" id="telefone" value ="<?php if(isset($res)){echo $res['telefone'];}?>">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];}?>">
        <input type="submit" value="<?php if(isset($res)){echo "Atualizar";}else{echo "Cadastrar";}?>" >
       </form>  
    </section>
    <section id="direita">
     <table>
            <tr id="titulo">
                <td>NOME</td>
                <td>TELEFONE</td>
                <td colspan="2">EMAIL</td>
             </tr>
        <?php
         $dados = $p->buscarDados();
         //echo "<pre>";
         //var_dump($dados);
         //echo "<pre>";
         //Abaixo se count($dados) > 0 tem Pessoas cadastrada na tabela
        if(count($dados) > 0) {
           for ($i=0; $i < count($dados); $i++){
                 echo "<tr>";
                 foreach($dados[$i] as $k => $v) {
                     if ($k != "id") {
                         echo "<td>".$v."</td>";


                     }
                 }
                 ?>
                    <td><a href="index.php?id_up=<?php echo $dados[$i]['id']; ?>">Editar</a>
                        <a href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a></td> 
                 <?php   
                 echo "</tr>";
            }
          
        }else{ //Nao há pessoas cadastradas na tabela pessoa o anco de dados esta vazio
         ?>
     </table>
         <!-- ?> -->
          <div class="aviso">
              <img src="aviso.jpg" whidht = 50 height = 50>
                <h4>"Ainda não há Pessoas cadastradas!"</h4>
          </div>   
        
        <?php
      }
      ?> 
   </section>
</body>
</html>

<?php
  // abaixo é executado quando a função exluir pessoa é acionada no botao excluir
  if(isset($_GET['id'])){
      $id_pessoa = addslashes($_GET['id']);
      $p->excluirPessoa($id_pessoa);
      //atualizar a pagina
      header("location:index.php");    
  }
?>