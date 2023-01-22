<?php 
session_start();
if (!isset($_SESSION['usuario_id'])){
  $_SESSION['usuario_id'] = '';
}

  if (isset($_GET['pagina'])){
    $pagina = $_GET['pagina'];  
  }else{  
    $pagina = 'home';
  }

  if ($_SESSION['usuario_id'] == ''){
    $pagina = 'login';
  }

  include 'funcoes.php';
  include 'conexao.php';

  include 'header.php';  
  echo '<body>';
  switch($pagina){
    //Views
    case'conta': include'views/cadastra_conta.php';break;     
    case'conta_fixa': include'views/cadastra_contas_fixa.php';break;     
    case'conta_variavel': include'views/cadastra_contas_variaveis.php';break;     
    case'contas': include'views/contas.php';break;       
    case'login': include'views/login.php';break;       
    case'usuario': include'views/usuario.php';break;           

    //Constructor
    case'delete_conta': include'constructor/delete_conta.php';break;     
    case'delete_conta_fixa': include'constructor/delete_fixo.php';break;     
    case'delete_conta_variavel': include'constructor/delete_variaveis.php';break;     
    case'pagamento': include'constructor/pagamento.php';break;     
      
    default: include 'views/home.php'; break;
  }
  echo '</body>';
  include ('footer.php');
  include ('scripts.php');