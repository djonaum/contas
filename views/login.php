<?php
?>
<div class="card card-header text-center">
    <h1 style="margin-bottom:23px;" >Login</h1>    
</div>
<div class="container text-center">   
  <form class="login100-form validate-form" action="login.php" method="POST">
    <span class="login100-form-title"></span>                  
    <div class="wrap-input100">
      <input class="input100" style="border:1px solid;"  type="text" name="usuario" placeholder="Usuário" autofocus/>
	  <span class="focus-input100"></span>
	  <span class="symbol-input100">
	    <i class="fa fa-envelope" aria-hidden="true"></i>
	  </span>
	</div>
    <div class="wrap-input100 ">
	  <input class="input100" style="border:1px solid;" type="password"  name="senha" placeholder="Senha"/>
	  <span class="focus-input100"></span>
	  <span class="symbol-input100">
	    <i class="fa fa-lock" aria-hidden="true"></i>
	  </span>
	</div>					
    <div class="container-login100-form-btn">
	  <button style="margin-top:13px;" class="btn btn btn-primary" >Entrar</button>
	</div>
	<div class="text-center p-t-136">
	</div>
  </form>                
  <div class="container" style="color:red; margin-top:13px;">
    <?php 
      if(isset($_SESSION["mensagem"])):  
        print $_SESSION["mensagem"];
        unset($_SESSION["mensagem"]);
      endif;
    ?>                    
	</div>  
</div>