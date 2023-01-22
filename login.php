<?php
session_start();
include('conexao.php');
if(empty($_POST['usuario']) || empty($_POST['senha'])) {		
	$_SESSION['nao_autenticado'] = True;
	header('Location: ?pagina=home');
	exit();
}
$usuario = mysqli_real_escape_string($conexao, $_POST['usuario']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

$query = "select usuario_id, usuario_login, usuario_nome from cad_usuarios where usuario_login = '{$usuario}' and usuario_senha = md5('{$senha}')";
$result = mysqli_query($conexao, $query);
$row = mysqli_num_rows($result);

if($row == 1) {

	$usuario_bd = mysqli_fetch_assoc($result);
	$_SESSION['usuario_login'] = $usuario_bd['usuario_login'];
    $_SESSION['usuario_id'] = $usuario_bd['usuario_id'];
    $_SESSION['usuario_nome'] = $usuario_bd['usuario_nome'];	
	$_SESSION['nao_autenticado'] = false;	
	header('Location: painel.php');
} else {
	$_SESSION['nao_autenticado'] = true;	
	header('Location: ./?pagina=home');
	$_SESSION['mensagem'] = 'Não foi possível fazer o login! <br>Verifique seu usuário e senha.';
}

