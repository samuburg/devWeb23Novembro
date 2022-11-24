<?php

// parâmetros para a conexão
define ('DB_HOST','localhost');         // endereço do servidor de banco de dados
define ('DB_USER','desenv');            // root
define ('DB_PASSWORD','123');           // ""
define ('DB_DB','agenda');              // nome banco
define ('DB_PORT','3306');              // porta que o banco de dados recebe requisições
define ('MYSQL_DSN',"mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DB.";charset=UTF8");

try{
    // cria a conexão com o banco de dados 
    $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
    // montar consulta 
    $consulta = 'SELECT * FROM contato';
    // prepara a consulta para executar
    $comando = $conexao->prepare($consulta);
    // executa a consulta
    $comando->execute();
    //pega retorno da consulta
    $listacontatos = $comando->fetchAll();
    // echo '<pre>';
    // var_dump($listacontatos);
    // echo '</pre>';
    echo "<table>";
    foreach($listacontatos as $contato){
        echo "<tr>";
        echo "<td>".$contato['id']."</td><td>".$contato['nome'].
                                 "</td><td>".$contato['sobrenome']."</td>";
        echo "</tr>";
    }
    echo "</table>";




}catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
    print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
    die();
}


?>