<?php
  if (isset($_POST['dsc_var'])){
    $id_conta = $_POST['id_conta'];
    $dsc_var = $_POST['dsc_var'];    
    $vlr_var = $_POST['vlr_var'];    
    $dt_compra = $_POST['dt_compra'];    
    $qtd_parcelas = $_POST['qtd_parcela'];      
    $geral = isset($_POST['geral']);  
    $usuario = $_SESSION['usuario_id'];
    
    insere_conta_variavel($conexao, $id_conta, $dsc_var, $vlr_var, $dt_compra, $qtd_parcelas, $geral, $usuario);
  }
?>
<div class="card card-header">
    <h1>Contas Variáveis</h1>    
</div>
<div class="container">    
  <form action="" method="POST">  
    <div id="formulario">          
      
        <label for="exampleFormControlInput1" class="form-label">Conta</label>       
        <?php 
          echo '<select class="form-control"  name="id_conta" id="id_conta">';
          echo '<option Value="0" selected disabled>Selecione uma conta</option>';
          foreach(contas($conexao,'V') as $conta){
            echo '<option value="'.$conta['id_conta'].'">'.$conta['dsc_conta'].'</option>';            
          };
          echo '</select>';            
        ?>           
        
        <label for="exampleFormControlInput1" class="form-label">Descrição da Conta Variável</label>       
        <input type="text" class="form-control" id="dsc_var" name="dsc_var" placeholder="Descrição"/>     
      
        <label for="exampleFormControlInput1" class="form-label">Valor</label>       
        <input type="number" class="form-control" id="vlr_var" name="vlr_var" placeholder="Valor" step="0.010"/>

        <label for="exampleFormControlInput1" class="form-label ">Data Compra</label>       
        <input type="date" class="form-control " id="dt_compra"  value="<?php echo date('Y-m-d');?>" name="dt_compra" placeholder="Data da Compra"></input>'

        <label for="exampleFormControlInput1" class="form-label ">Qtd. Parcelas</label>       
        <input type="number" class="form-control" id="qtd_parcela" name="qtd_parcela" placeholder="Quantidade de parcelas"/>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="geral" name="geral">
          <label class="form-check-label" for="flexCheckDefault">Conta Geral</label>
        </div>      
        <button type="submit" class="btn btn-primary glyphicon glyphicon-floppy-saved " id="save-btn" ></button>  
      </div>      
      
        
    
    <hr>
    <?php contas_variaveis($conexao, $_SESSION['usuario_id']);?>    
  </form>
</div>   
