<?php
  if (isset($_POST['vlr_fixo'])){
    $id_conta= $_POST['id_conta'];    
    $vlr_fixo= $_POST['vlr_fixo'];    
    $vencto_fixo= $_POST['vencto_fixo'];    
    $ativo= isset($_POST['ativo']);    
    $usuario = $_SESSION['usuario_id'];    
    $geral = isset($_POST['geral']);      
    
    insere_conta_fixa($conexao, $id_conta, $vlr_fixo, $vencto_fixo, $ativo, $usuario, $geral);
   
  }
?>
<div class="card card-header">
    <h1>Contas Fixa</h1>    
</div>
<div class="container">    
<form action="" method="POST">
  
    <div id="formulario">          
      <div class="row">
        <label for="exampleFormControlInput1" class="form-label">Descrição da Conta Fixa</label>       
        <?php 
          echo '<select class="form-control col-2"  name="id_conta" id="id_conta">';
          echo '<option Value="0" selected disabled>Selecione uma conta</option>';
          foreach(contas($conexao,'F') as $conta){
            echo '<option value="'.$conta['id_conta'].'">'.$conta['dsc_conta'].'</option>';            
          };
          echo '</select>';            
        ?>
      </div>
      <div class="row ">
        <label for="exampleFormControlInput1" class="form-label">Valor</label>       
        <input type="number" class="form-control col-2" id="vlr_fixo" name="vlr_fixo" placeholder="Valor" step="0.010"/>

        <label for="exampleFormControlInput1" class="form-label ">Vencimento</label>       
        <input type="number" class="form-control col-2" id="vencto_fixo" name="vencto_fixo" placeholder="Vencimento"/>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="ativo" name="ativo">
          <label class="form-check-label col-2" for="flexCheckDefault">Ativo</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="geral" name="geral">
          <label class="form-check-label col-2" for="flexCheckDefault">Conta Geral</label>
        </div>        
      </div>      
    </div>        
    <button type="submit" class="btn btn-primary glyphicon glyphicon-floppy-saved " id="save-btn" ></button>
  
  <hr>
  <?php
  contas_fixa($conexao, $_SESSION['usuario_id']);
  ?>
    
</form>
</div>   
