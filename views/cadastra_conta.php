<?php
  if (isset($_POST['contas'])){
    $conta = $_POST['contas'];    
    $tipo = $_POST['tipo'];
    $vcto = $_POST['vcto'];
    insere_conta($conexao, $conta, $tipo, $vcto);
  }
?>
<div class="card card-header">
    <h1>Contas</h1>    
</div>
<div class="container">   
  <form action="" method="POST">   
    <div id="formulario">    
      <div class="row">       
        <label for="exampleFormControlInput1" class="form-label">Contas</label>       
        <input type="text" class="form-control mb-2" id="contas" name="contas" placeholder="Descrição"/>
        <label for="exampleFormControlInput1" class="form-label">Tipo</label>       
        <select class="form-control mb-2" id="tipo" name="tipo">
          <option value="" disabled selected>Selecione um tipo de conta</option>
          <option value="V">Conta Variável</option>
          <option value="F">Conta Fixa</option>
        </select>
        <label for="exampleFormControlInput1" class="form-label ">Vencimento</label>       
        <select class="form-control col-2" name="vcto" id="vcto">
          <option value='0' disabled selected>Selecione um Vencimento</option>
          <option value='5'>Dia 5</option>
          <option value='20'>Dia 20</option>
        </select>        
      </div>  
    </div>        
    <button type="submit" class="btn btn-primary glyphicon glyphicon-floppy-saved " id="save-btn" ></button>  
    <hr>
    <?php
      contas($conexao,2);
    ?>    
  </form>
</div>   
