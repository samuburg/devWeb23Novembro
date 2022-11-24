<?php
include_once "../config/conf.inc.php";   
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
        $query = 'SELECT * FROM usuario WHERE id = :id' ;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
 
    <title>Cadastro de Usuário</title>
    <script>

        // floreio -- para o usuário confirmar a exclusão
        function excluir(url){
            if (confirm("Confirma a exclusão?"))
                window.location.href = url; //redireciona para o arquivo que irá efetuar a exclusão
        }
    </script>
</head>
<body class='container'>
    <h1>Cadastrar novo Usuário</h1>
    <section id='cadusuario' class='row'>
        <!-- Formulário para cadastro e edição de dados do usuário, 
        caso seja aberto a página com a ação de editar o formulário trará os campos preenchidos com os dados do registro selecionado  -->
        <div class='col'>
            <form action="acao.php" method="post">  <!-- esse formulário envia os dados para o arquivo acao.php -->
                <div class='row'>
                    <div class='col-1'>
                        <label for="id">Id:</label>
                        <input type="text" class='form-control' style='width:50px' readonly name="id" id="id" value=<?php if(isset($usuario)) echo ($usuario['id']); else echo 0;?>>
                    </div>
                    <div class='col'>
                        <label for="name">Nome:</label>
                        <input type="text" class='form-control' name='nome' id='nome' placeholder="Informe seu nome completo..."  value=<?php if(isset($usuario)) echo $usuario['nome'] ?> >
                    </div>
                    <div class='col'>
                        <label for="email">E-mail:</label>
                        <input type="email" class='form-control' name='email' id='email' placeholder="usuario@mail.com..." value=<?php if(isset($usuario)) echo $usuario['email'] ?>>
                    </div>
                    <div class='col'>
                        <label for="name">Senha:</label>
                        <input type="password" class='form-control' name='senha' id='senha' placeholder="Informe uma senha ..." value=<?php if(isset($usuario)) echo $usuario['senha'] ?>>
                    </div>
                    <div class='col'>  
                        <br>                  
                        <button type='submit' name='acao' value='salvar' class='btn btn-primary'>Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <hr>
    <!-- Nesta seção serão listados os usuários já cadastrados no banco de dados -->
    <section class='row'>
        <!-- esse formulário é para permitir a pesquisa de um usuário cadastrado -->
        <div class='col'>
            <form action="" method="get" id='pesquisa'> <!-- esse formulário submte para essa mesma página para recarregar com o resultado da busca -->
                <div class='row'>
                    <div class='col-8'><h2> Lista de Usuários cadastrados</h2></div>
                    <div class='col'><input class='form-control' type="search" name='busca' id='busca'></div>
                    <div class='col'><button type="submit" class='btn btn-success' name='pesquisa'>Buscar</button></div>
                </div>
            </form>
            <div class='row'>
                <!-- aqui montamos a tabela com os dados vindo do banco -->
                <table class='table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Senha</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php             
                        try{
                            // cria  a conexão com o banco de dados 
                            $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
                            // pega o valor informado pelo usuário no formulário de pesquisa
                            $busca = isset($_GET['busca'])?$_GET['busca']:"";
                            // monta consulta
                            $query = 'SELECT * FROM usuario';
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
                                $editar = '<a href=cadUsuario.php?acao=editar&id='.$usuario['id'].'>Alt</a>';
                                $excluir = "<a href='#' onclick=excluir('acao.php?acao=excluir&id={$usuario['id']}')>Excluir</a>";
                                echo '<tr><td>'.$usuario['id'].'</td><td>'.$usuario['nome'].'</td><td>'.$usuario['email'].'</td><td>'.$usuario['senha'].'</td><td>'.$editar.'</td><td>'.$excluir.'</td></tr>';
                            }
                        }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
                            print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
                            die();
                        }           
                    ?>  
                    </tbody>      
                </table>
            </div>
        </div>
    </section>
</body>
</html>