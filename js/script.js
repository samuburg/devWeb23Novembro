window.onload = (function (){
    document.getElementById('busca').addEventListener('keyup',function(ev){ // pega o evento de submit do formulário
        // ev.preventDefault(); // não envia o formulário
        carregaDados(document.getElementById('busca').value);
    })

});

function carregaDados(busca){
    const xhttp = new XMLHttpRequest();  // cria o objeto que fará a conexão assíncrona
    xhttp.onload = function() {  // executa essa função quando receber resposta do servidor
        dados = JSON.parse(this.responseText); // os dados são convertidos para objeto javascript
        montaTabela(dados); // chama função que montará a tabela na interface
    }
    // configuração dos parâmetros da conexão assíncrona
    xhttp.open("POST", "pesquisa.php", true);  // arquivo que será acessado no servidor remoto  
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // cabeçalhos - necessário para requisição POST
    xhttp.send("busca=" + busca); // parâmetros para a requisição
}

function excluir(url,nome){ // função para confirmar a exclusão de um registro
    msg = 'Confirma a exclusão do contato ' + nome + '?';
    if (confirm(msg)){        
        window.location.href = url; // se o usuário confirmar redireciona para a URL
    }
}

function montaTabela(dados){
    el = document.getElementById("lista");
    el.remove(); // remove a tabela existente para recriá-la
    

    // aqui eu crio tudo como uma string, o ideal é criar cada elemento com a função Create e fazer o append desses ao documento
    let tabela = "<table class='table lista-contatos' id='lista'><thead><tr><th>Id</th><th>Nome</th><th>Sobrenome</th><th>Telefone</th><th>Alterar</th><th>Excluir</th></tr></thead>";
    for (let it in dados) {
        tabela += "<tr><td>" + dados[it].id + "</td>";
        tabela += "<td>" + dados[it].nome + "</td>";
        tabela += "<td>" + dados[it].sobrenome + "</td>";
        tabela += "<td>" + dados[it].telefone + "</td>";
        tabela += "<td><a href='novo/index.php?acao=editar&id="+dados[it].id+"'>Alt</a></td>";
        tabela += "<td><a href='#' onclick=excluir('index.php?acao=excluir&id="+dados[it].id+"','"+dados[it].nome+"')>Exc</a></td></tr>";
    }
    tabela += "</table>";
    document.getElementById('listagem').innerHTML = tabela;
}


