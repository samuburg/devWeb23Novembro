<?php

// parâmetros para a conexão
define ('DB_HOST','localhost');         // endereço do servidor de banco de dados
define ('DB_USER','root');            // root
define ('DB_PASSWORD','');           // ""
define ('DB_DB','agenda');              // nome banco
define ('DB_PORT','3306');              // porta que o banco de dados recebe requisições
define ('MYSQL_DSN',"mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DB.";charset=UTF8");


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
    <script src='script .js'></script>
    <title>Cadastro de Novo Contato</title>

</head>
<body class='container'>
    <div class='row'>
        <div class='col'>
            <section id="formulario-cadastro">
                <form action="acao.php" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Dados do Contato</legend>
                        <div>
                            <label class="col-sm-2 col-form-label" for="nome">Id:</label>
                            <input readonly class="form-control-plaintext" type="text" id="id" name="id"  value=<?php if(isset($usuario)) echo ($usuario['id']); else echo 0;?> >
                        </div>
                        <div>
                            <label class="form-label" for="nome">Nome:</label>
                            <input class="form-control" type="text" id="nome" name="nome" placeholder="Digite aqui seu nome..."  value= <?php if(isset($usuario)) echo $usuario['nome'] ?> >
                        </div>
                        <div>
                            <label class="form-label" for="sobrenome">Sobrenome:</label>
                            <input type="text"  class="form-control" id="sobrenome" name="sobrenome" placeholder="Digite aqui seu sobrenome..."  value=<?php if(isset($usuario)) echo $usuario['sobrenome'] ?>>
                        </div>
                        <div>
                            <label class="form-label" for="dtnasc">Data de Nascimento:</label>
                            <input type="date"  class="form-control" id="dtnasc" name="dtnasc" value=<?php if(isset($usuario)) echo $usuario['dtnasc'] ?>>
                        </div>
                        <div>
                            <label class="form-label" for="email">E-mail:</label>
                            <input type="email"  class="form-control"  id="email" name="email" value=<?php if(isset($usuario)) echo $usuario['email'] ?>>
                        </div>
                        <div>
                            <label class="form-label" for="telefone">Telefone:</label>
                            <input type="tel"  class="form-control"  id="telefone" name="telefone" value=<?php if(isset($usuario)) echo $usuario['telefone'] ?>>
                        </div>
                        <div>
                            <input type="radio"  class="form-check-input"   id="sexofeminino" name="sexo" value="1" <?php if((isset($usuario)) and $usuario['sexo']=='1') echo 'checked'; ?> >
                            <label class="form-check-label" for="sexofeminino">Feminino:</label>
                            <input type="radio" class="form-check-input"   id="sexomasculino" name="sexo" value="2" <?php if(isset($usuario) and $usuario['sexo']=='2') echo 'checked'; ?> >
                            <label class="form-check-label" for="sexomasculino">Masculino:</label>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input"  id="parente" name="parente" <?php if(isset($usuario) and $usuario['parente']) echo 'checked'?> > 
                            <label class="form-check-label"  for="parente">Parente ?</label>
                        </div>
                        <div>
                            <label class="form-label" for="foto">Foto:</label>
                            <input type="file"  class="form-control"  id="foto" name="foto" value=<?php if(isset($usuario)) echo'' ?>>
                        </div>
                        <div>
                            <label for="origem">Origem:</label>
                            <select  class="form-select"  name="origem" id="origem">
                                <option value="0">Selecione</option>
                                <option value="1"  <?php if(isset($usuario) and $usuario['origem'] == 1) echo 'selected'; ?>>Trabalho</option>
                                <option value="2"  <?php if(isset($usuario) and $usuario['origem'] == 2) echo 'selected'; ?>>Escola</option>
                                <option value="3"  <?php if(isset($usuario) and $usuario['origem'] == 3) echo 'selected'; ?>>Internet</option>
                                <option value="4"  <?php if(isset($usuario) and $usuario['origem'] == 4) echo 'selected'; ?>>Night</option>
                            </select>
                        </div>
                        <div>
                            <label  class="form-label"for="rede">Rede Social:</label>
                            <input  class="form-control"  type="text" id="rede" name="rede" placeholder="@..." value=<?=isset($usuario)?$usuario['rede']:''?>>
                        </div>
                        <div>
                            <label for="estado">Estado:</label>
                            <select  class="form-select"  name="estado" id="estado">
                                <option value="0">Selecione</option>
                                <option value="1" <?php if(isset($usuario) and $usuario['estado'] == 1) echo 'selected'; ?>>Acre</option>
                                <option value="2" <?php if(isset($usuario) and $usuario['estado'] == 2) echo 'selected'; ?>>Paraná</option>
                                <option value="3" <?php if(isset($usuario) and $usuario['estado'] == 3) echo 'selected'; ?>>Rio Grande do Sul</option>
                                <option value="4" <?php if(isset($usuario) and $usuario['estado'] == 4) echo 'selected'; ?>>Santa Catarina</option>
                                <option value="5" <?php if(isset($usuario) and $usuario['estado'] == 5) echo 'selected'; ?>>São Paulo</option>
                            </select>
                        </div>
                        <div>
                            <label for="cidade">Cidade:</label>
                            <select  class="form-select" name="cidade" id="cidade" value=<?=isset($usuario)?$usuario['cidade']:''?>>
                                <option value="0">Selecione</option>
                                <option value="1" <?php if(isset($usuario) and $usuario['cidade'] == 1) echo 'selected'; ?>>Joinville</option>
                                <option value="2" <?php if(isset($usuario) and $usuario['cidade'] == 2) echo 'selected'; ?>>Florianópolis</option>
                                <option value="3" <?php if(isset($usuario) and $usuario['cidade'] == 3) echo 'selected'; ?>>Itajaí</option>
                                <option value="4" <?php if(isset($usuario) and $usuario['cidade'] == 4) echo 'selected'; ?>>Blumenau</option>
                                <option value="5" <?php if(isset($usuario) and $usuario['cidade'] == 5) echo 'selected'; ?>>Rio do Sul</option>
                            </select>
                        </div>
                        <div>
                            <textarea  class="form-control"  name="descricao" id="descricao" cols="30" rows="10" placeholder="Descreva seu contato, adicione demais informações"><?=isset($usuario)?$usuario['descricao']:''?></textarea>
                        </div>
                        <div>
                            <button  class="btn btn-primary"  type="submit" name="acao" value="salvar">Salvar</button>
                            <input  class="btn btn-cancel"  type="reset" name="cancelar" value="Cancelar" onclick='window.location.href="index.php"'>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
    </div>
</body>
</html>