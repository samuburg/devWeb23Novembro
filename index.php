<?php
// parâmetros para a conexão
/*
define ('DB_HOST','localhost');         // endereço do servidor de banco de dados
define ('DB_USER','root');            // root
define ('DB_PASSWORD','');           // ""
define ('DB_DB','agenda');              // nome banco
define ('DB_PORT','3306');              // porta que o banco de dados recebe requisições
define ('MYSQL_DSN',"mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DB.";charset=UTF8");
*/
  include_once "novo/acao.php";

  // pega variáveis enviadas via GET - são enviadas para edição de um registro
$acao = isset($_GET['acao'])?$_GET['acao']:"";
$id = isset($_GET['id'])?$_GET['id']:0;
// verifica se está editando um registro
if ($acao == 'editar'){
    // buscar dados do usuário que estamos editando
    try{
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
        // montar consulta
        $query = 'SELECT * FROM contato WHERE id = :id' ;
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id',$id); 
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $usuario = $stmt->fetch(); 
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
    }  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="estilo.css">
    <title>Agenda de Contatos</title>
    <script>

        // floreio -- para o usuário confirmar a exclusão
        function excluir(url){
            if (confirm("Confirma a exclusão?"))
                window.location.href = url; //redireciona para o arquivo que irá efetuar a exclusão
        }
    </script>
    <script src="js/script.js"></script>
</head>
<body class='container'>
    <h1>Meus Contatos</h1>
    <nav> <!-- menu -->
        <ul class="menu">
            <li id="cadastrar" class="itemenu"><a href="novo/">Cadastrar Contato</a></li>
            <li id="cadUsuario" class="itemenu"><a href="usuario/cadUsuario.php">Cadastrar Usuário</a></li>
            <li class="itemenu"><a href="extras/sobre.html">Sobre</a></li>
            <li>Favoritos</li>
        </ul>
    </nav>
    <section> <!-- pesquisa -->
        <div class='row'>
            <div class='col'>
                <form action="" id='pesquisa'>
                    <div class='row'>
                        <div class='col-6'></div>
                        <div class='col-4'>
                            <input class='form-control' type="search" name='busca' id='busca'>
                        </div>
                        <div class='col-2'>
                            <button class='btn btn-primary' type="submit">Filtrar</button>
                        </div>
                    </div>
                </form>            
            </div>
        </div>
    </section>
    <br>
    <section> <!-- tabela de contatos-->
        <div class='row'>
            <div class='col' id='listagem'>
                <table class="table lista-contatos" id='lista'>
                <thead>
                    <tr>
                        <th>Id</th><th>Nome</th><th>Sobrenome</th><th>Telefone</th><th>Alterar</th><th>Excluir</th>
                    </tr>
                </thead>
                <?php             
                        try{
                            // cria  a conexão com o banco de dados 
                            $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
                            // pega o valor informado pelo usuário no formulário de pesquisa
                            $busca = isset($_GET['busca'])?$_GET['busca']:"";
                            // monta consulta
                            $query = 'SELECT * FROM contato';
                            if ($busca != ""){ // se o usuário informou uma pesquisa
                                $busca = '%'.$busca.'%'; // concatena o curiga * na pesquisa
                                $query .= ' WHERE nome like :busca' ; // acrescenta a clausula where
                            }
                            // prepara consulta
                            $stmt = $conexao->prepare($query);
                            // vincular variaveis com a consulta
                            if ($busca != "") // somente se o usuário informou uma busca
                                $stmt->bindValue(':busca',$busca);
                            // executa a consuta 
                            $stmt->execute();
                            // pega todos os registros retornados pelo banco
                            $usuarios = $stmt->fetchAll();
                            foreach($usuarios as $usuario){ // percorre o array com todos os usuários imprimindo as linhas da tabela
                                $editar = '<a href=index.php?acao=editar&id='.$usuario['id'].'>Alt</a>';
                                $excluir = "<a href='#' onclick=excluir('novo/acao.php?acao=excluir&id={$usuario['id']}')>Excluir</a>";
                                echo '<tr><td>'.$usuario['id'].'</td><td>'.$usuario['nome'].'</td><td>'.$usuario['sobrenome'].'</td><td>'.$usuario['telefone'].'</td><td>'.$editar.'</td><td>'.$excluir.'</td></tr>';
                            }
                        }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
                            print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
                            die();
                        }           
                    ?>  
                </table>
            </div>
        </div>
       
    </section>
</body>
</html>

