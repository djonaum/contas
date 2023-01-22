<?php

if (isset($_GET['id'])){
    $id = $_GET['id'];
    $baixa = $_GET['baixa'];
    $tipo = $_GET['tipo'];

    if ($tipo == 0){        
      pagamento($conexao, $id, $baixa, $tipo, '');      
    }else{        
      $comp = $_GET['comp'];
      pagamento($conexao, $id, $baixa, $tipo, $comp);        
    }
    header('Location: painel.php?pagina=contas');                  
}