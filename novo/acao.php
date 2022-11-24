
<?php
// verificar dados enviados
// echo 'Dados enviados:<br>';
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
define ('DB_HOST','localhost');         // endereço do servidor de banco de dados
define ('DB_USER','root');            // root
define ('DB_PASSWORD','');           // ""
define ('DB_DB','agenda');              // nome banco
define ('DB_PORT','3306');              // porta que o banco de dados recebe requisições
define ('MYSQL_DSN',"mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DB.";charset=UTF8");
//var_dump(JSON); -MITO
$id = isset($_POST['id'])?$_POST['id']:0;  // teste ISSET é para verificar se os dados foram enviad
$nome = isset($_POST['nome'])?$_POST['nome']:'';
$sobrenome = isset($_POST['sobrenome'])?$_POST['sobrenome']:'';
$dtnasc = isset($_POST['dtnasc'])?$_POST['dtnasc']:'';
$email = isset($_POST['email'])?$_POST['email']:'';
$telefone = isset($_POST['telefone'])?$_POST['telefone']:'';
$sexo = isset($_POST['sexo'])?$_POST['sexo']:'';
$parente = isset($_POST['parente'])?$_POST['parente']:'';
$origem = isset($_POST['origem'])?$_POST['origem']:'';
$rede = isset($_POST['rede'])?$_POST['rede']:'';
$estado = isset($_POST['estado'])?$_POST['estado']:'';
$cidade  =isset($_POST['cidade'])?$_POST['cidade']:'';
$descricao = isset($_POST['descricao'])?$_POST['descricao']:'';
$foto=isset($_POST['foto'])?$_POST['foto']:'';

// se a ação for excluir virá via GET
$acao =  isset($_GET['acao'])?$_GET['acao']:"";

if ($acao == 'excluir'){ // exclui um registro do banco de dados
    try{
        $id =  isset($_GET['id'])?$_GET['id']:0;  // se for exclusão o ID vem via GET
        
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
        $query = 'DELETE FROM contato WHERE id = :id';
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':id',$id);
        // executar a consulta
        if ($stmt->execute())
            header('location: ../index.php');
        else
            echo 'Erro ao excluir dados';
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
    }
}else{ // então é para inserir ou atualizar
    if ($nome != "" && $email != ""){
        // salvar no banco de dados    
        try{
            // cria a conexão com o banco de dados 
            $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
            // montar consulta
            if ($id > 0) // se o ID está informado é atualização
                $query = 'UPDATE contato 
                             SET nome = :nome, sobrenome = :sobrenome, dtnasc = :dtnasc, email = :email,
                              telefone = :telefone, sexo = :sexo, parente = :parente, origem = :origem, rede = :rede,
                               estado = :estado, cidade = :cidade, descricao = :descricao, foto = :foto
                           WHERE id = :id';
            else // senão será inserido um novo registro
                $query = 'INSERT INTO contato (nome, sobrenome, dtnasc, email, telefone, sexo, parente, origem, rede, estado, cidade, descricao, foto) 
                               VALUES (:nome, :sobrenome, :dtnasc, :email, :telefone, :sexo, :parente, :origem, :rede, :estado, :cidade, :descricao, :foto)';
            // preparar consulta
            $stmt = $conexao->prepare($query);
            // vincular variaveis com a consulta
            $stmt->bindValue(':nome',$nome);   
            $stmt->bindValue(':sobrenome',$sobrenome);
            $stmt->bindValue(':dtnasc',$dtnasc);       
            $stmt->bindValue(':email',$email);
            $stmt->bindValue(':telefone',$telefone);         
            $stmt->bindValue(':sexo',$sexo);
            $stmt->bindValue(':parente',$parente);
            $stmt->bindValue(':origem',$origem); 
            $stmt->bindValue(':rede',$rede); 
            $stmt->bindValue(':estado',$estado); 
            $stmt->bindValue(':cidade',$cidade);  
            $stmt->bindValue(':descricao',$descricao); 
            $stmt->bindValue(':foto',$foto); 
            if ($id > 0) // atualização
                $stmt->bindValue(':id',$id);

            // executar a consulta
            if ($stmt->execute())
                header('location: index.php');
            else
                echo 'Erro ao inserir/editar dados';
        }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
            die();
        }catch(Exception $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro genérico...<br>".$e->getMessage());
            die();
        }
    }
}

?> 
