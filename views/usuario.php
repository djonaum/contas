<?php
  $verifica = 0;
  if (isset($_POST['usuario'])){
    $verifica = 1;
  }
  if (isset($_POST['senha'])){
    $verifica = 1;
  }  
if ($verifica == 1){
  
  $usuario = mysqli_real_escape_string($conexao, $_POST['usuario']);
  $senha = mysqli_real_escape_string($conexao, $_POST['senha']); 
  usuario($conexao, $usuario, $senha);
  
}
?>
<div class="card card-header">
    <h1>Usuário</h1>    
</div>
<div class="container">   
  <form action="" method="POST">   
    <div id="formulario">    
      <div class="row">       
        <label for="exampleFormControlInput1" class="form-label">Usuário</label>       
        <input type="text" class="form-control mb-2" id="usuario" name="usuario" placeholder="Usuário"/>
        <label for="exampleFormControlInput1" class="form-label">Senha</label>       
        <input type="password" class="form-control mb-2" id="senha" name="senha" placeholder="Senha"/>             
      </div>  
    </div>        
    <button type="submit" class="btn btn-primary glyphicon glyphicon-floppy-saved " id="save-btn" ></button>  
    <hr>  
  </form>
</div>   
