<?php 
$usuario = $_SESSION['usuario_id'];
$compet ='';
$conta = 0;
if (isset($_POST['filter'])){
  if (isset($_POST['compet'])){
  $compet = $_POST['compet'];
  $conta = isset($_POST['conta']); 
  }else{$compet='';}
}

?>
<div class="card card-header">
    <h1>Gastos do mês</h1>    
</div>
<div class="container">     
 <form action="" method="POST">   
 <div class="form-check">
   <input class="form-check-input" type="checkbox" checked id="conta" name="conta">
   <label class="form-check-label col-2" for="flexCheckDefault">Conta</label>
  </div>  
  <div class="row col-3 md-2">
    <label for="exampleFormControlInput1 " class="form-label">Data</label>
    <select class="form-control" id="compet" name="compet">
      <option value ="0" disabled selected>Selecione um mês</option>
    <?php
       $i = 0;
       $data =  date("d-m-Y",strtotime('+1 months'));
       
       while ($i <= 11){
        $mes = date("m/Y", strtotime($data));
        echo '<option value="'.$mes.'">'.$mes.'</option>';
        $data =  date("d-m-Y", strtotime('-'.$i.' months')); 
        $i = $i + 1;        
       }
    ?>
    </select>
    <button  name="filter" id="filter" class="btn btn-primary col-1 glyphicon glyphicon-search mt-2"></button>  
   
  </div>  
    <div id="formulario">    
      <div class="row"> 
        <?php 
          if ($conta == 0){
            gastos($conexao, $usuario, $compet);             
          }else{
            gastos_agrupa($conexao, $usuario, $compet); 
          }
        ?>        
      </div>                                      
    </div>      
  </form>
</div>  