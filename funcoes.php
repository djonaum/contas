<?php

function contas($conexao, $tipo){
  $sql = "select id_conta, dsc_conta 
            from conta";
  if ($tipo == 'V' ){
    $sql = $sql.' Where fixo_variavel = "V"';
    $conta = mysqli_query($conexao,$sql);    
    return $conta;
  }else if ($tipo == 'F'){
    $sql = $sql.' Where fixo_variavel = "F"';
    $conta = mysqli_query($conexao,$sql);    
    return $conta;    
  }else{
  $conta = mysqli_query($conexao,$sql);    
  echo '<table  class="table table-striped">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <td>';
  echo '        <b>Contas</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Deletar</b>';
  echo '      </td>';  
  echo '    </tr>';
  echo '  </thead>';  
  echo '  <tbody>';  
  foreach($conta as $contas){    
    echo '    <tr>';
    echo '      <td>';
    echo          $contas['dsc_conta'];
    echo '      </td>';
    echo '      <td>';
    echo '        <a href="?pagina=delete_conta&id='.$contas['id_conta'].'" class="btn btn-danger glyphicon glyphicon-trash"></a>';
    echo '      </td>';    
    echo '    </tr>';       
  }
  echo '  </tbody>';
  echo '</table>';
}
}

function contas_fixa($conexao, $usuario){
  $conta= mysqli_query($conexao,"select id_fixo, dsc_conta, vlr_fixo, dia_vencto, fixo_ativo from fixo, conta 
                                  where fixo.id_conta = conta.id_conta
                                  and conta_geral = 0
                                  union
                                  select id_fixo, dsc_conta, vlr_fixo, dia_vencto, fixo_ativo from fixo, conta 
                                  where fixo.id_conta = conta.id_conta
                                  and conta_geral = 1
                                  and usuario_id = $usuario");
  echo '<table  class="table table-striped">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <td>';
  echo '        <b>Conta</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Valor</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Vencimento</b>';
  echo '      </td>';    
  echo '      <td>';
  echo '        <b>Ativo</b>';
  echo '      </td>';  
  echo '    </tr>';
  echo '  </thead>';  
  echo '  <tbody>';  
  foreach($conta as $fixo){    
    echo '    <tr>';
    echo '      <td>';
    echo          $fixo['dsc_conta'];
    echo '      </td>';
    echo '      <td>';
    echo          number_format($fixo['vlr_fixo'], 2, ',', '');
    echo '      </td>';       
    echo '      <td>';
    echo          $fixo['dia_vencto'];
    echo '      </td>';           
    echo '      <td>';
    if ($fixo['fixo_ativo'] == 1){
      echo'        <input class="form-check-input" type="checkbox" Checked disabled>';
    }else{
      echo'        <input class="form-check-input" type="checkbox" disabled>';
    }
    echo '      </td>';                  
    echo '      <td>';
    echo '        <a href="?pagina=delete_conta_fixa&id='.$fixo['id_fixo'].'" class="btn btn-danger glyphicon glyphicon-trash"></a>';
    echo '      </td>';
    echo '    </tr>';       
  }
  echo '  </tbody>';
  echo '</table>';
}


function contas_variaveis($conexao, $usuario){
  $conta= mysqli_query($conexao,"select id_compra, id_conta, dsc_compra, vlr_compra, data_compra,	parcela
                                   from compra_variavel
                                    where ((conta_geral = 1) or (conta_geral=0 and usuario_id = $usuario))
                                      and concat(month(date(data_compra)),'/',year(date(data_compra))) = concat(month(date(now())),'/',year(date(now()))) ");
  
  echo '<table  class="table table-striped">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <td>';
  echo '        <b>Conta</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Valor</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Compra</b>';
  echo '      </td>';    
  echo '      <td>';
  echo '        <b>Parcela</b>';
  echo '      </td>';  
  echo '    </tr>';
  echo '  </thead>';  
  echo '  <tbody>';  
  foreach($conta as $variavel){    
    echo '    <tr>';
    echo '      <td>';
    echo          $variavel['dsc_compra'];
    echo '      </td>';
    echo '      <td>';
    echo         number_format($variavel['vlr_compra'], 2, ',', '');
    echo '      </td>';       
    echo '      <td>';
    echo           date('d/m/Y', strtotime($variavel['data_compra']));
    echo '      </td>';    
    echo '      <td>';
    echo          $variavel['parcela'];
    echo '      </td>';                   
    echo '      <td>';
    echo '        <a href="?pagina=delete_conta_variavel&id='.$variavel['id_compra'].'" class="btn btn-danger glyphicon glyphicon-trash"></a>';
    echo '      </td>';
    echo '    </tr>';       
  }
  echo '  </tbody>';
  echo '</table>';
}

function gastos_agrupa($conexao, $usuario, $compet){
  if ($compet == ''){exit();}else{
    $data = date("d/m/Y", strtotime('01/'.$compet));
  }
  $mes =  ltrim(date("m",strtotime($data)),0);
  $ano =  date("Y",strtotime($data));
  $comp = $mes.'/'.$ano;

  mysqli_query($conexao,"CREATE TEMPORARY TABLE Contas
                         Select id_conta, vlr_fixo as vlr, parc.baixa 
                           from fixo, parcelas parc
                          where fixo.id_fixo  = parc.id_fixo
                            and data_vcto = '".$comp."';");
  mysqli_query($conexao,"insert into Contas
                         Select cv.id_conta, cv.vlr_compra as vlr, parc.baixa 
                           from parcelas parc, compra_variavel cv
                          Where parc.id_compra = cv.id_compra 
                            and data_vcto = '".$comp."';");
  $contas = mysqli_query($conexao,"Select con.id_conta, ct.dsc_conta,sum(vlr) as vlr,
                                          case when (select count(baixa) 
                                                       from Contas cont 
                                                      where cont.baixa = 0
                                                        and cont.id_conta = con.id_conta) >= 1 then 0
                                          else 1 end baixa
                                     From Contas con, conta ct
                                    Where con.id_conta = ct.id_conta
                                    Group by con.id_conta,ct.dsc_conta
                                    Order by ct.dsc_conta;");
                              
  mysqli_query($conexao,"CREATE  TEMPORARY TABLE Total_Pago(vlr_conta_total DECIMAL(8,2));");
  mysqli_query($conexao,"insert into Total_Pago
                         select (case when sum(case when vlr_fixo is null then 0 
                                else vlr_fixo end) > 0 then sum(case when vlr_fixo is null then 0 
                                                                else vlr_fixo end) 
                               else 0 end) 
                          from fixo fx, parcelas parc
                         where fx.id_fixo = parc.id_fixo
                           and parc.baixa = 1
                           and conta_geral = 1 
                          and parc.data_vcto = '".$comp."';");
                                    
  mysqli_query($conexao,"insert into Total_Pago
                         select (case when sum(case when vlr_fixo is null then 0 
                                               else vlr_fixo end) > 0 then sum(case when vlr_fixo is null then 0
                                                                               else vlr_fixo end) 
                                 else 0 end)
                           from fixo fx, parcelas parc
                          where fx.id_fixo = parc.id_fixo
                            and parc.baixa = 1
                            and conta_geral = 0
                            and usuario_id = $usuario 
                            and parc.data_vcto = '".$comp."';");
                                    
  mysqli_query($conexao,"insert into Total_Pago
                         select (case when sum(case when vlr_compra is null then 0 
                                               else vlr_compra end) > 0 then sum(case when vlr_compra is null then 0 
                                                                                 else vlr_compra end) 
                                else 0 end)
                           from compra_variavel cv, parcelas parc 
                          where cv.id_compra = parc.id_compra
                            and parc.baixa = 1
                            and conta_geral = 1
                            and parc.data_vcto = '".$comp."';");
                                    
  mysqli_query($conexao,"insert into Total_Pago
                         select (case when sum(case when vlr_compra is null then 0 
                                               else vlr_compra end) > 0 then sum(case when vlr_compra is null then 0 
                                                                                else vlr_compra end) 
                                else 0 end)
                           from compra_variavel cv, parcelas parc 
                          where cv.id_compra = parc.id_compra
                            and parc.baixa = 1
                            and conta_geral = 0
                            and usuario_id = $usuario 
                            and parc.data_vcto = '".$comp."';");
                                    
  $pagto = mysqli_query($conexao,"select sum(vlr_conta_total) tot_pago from Total_Pago;");     

  foreach($pagto as $pago){
    $total_pago = $pago['tot_pago'];
  }                                   
  
    $total = 0;
    echo '<table  class="table table-striped">';
    echo '  <thead>';
    echo '    <tr>';
    echo '      <td>';
    echo '        <b>Conta</b>';
    echo '      </td>';      
    echo '      <td>';
    echo '        <b>Valor</b>';
    echo '      </td>';
    echo '      <td>';
    echo '        <b>Baixa</b>';
    echo '      </td>';    
    echo '    </tr>';
    echo '  </thead>';  
    echo '  <tbody>';  
    foreach($contas as $gastos){    
      echo '    <tr>';
      echo '      <td>';
      echo          $gastos['dsc_conta'];
      echo '      </td>';    
      echo '      <td>';
      echo         'R$ '.number_format($gastos['vlr'], 2, ',', '');
      echo '      </td>';    
      echo '      <td>';
      if ($gastos['baixa']){      
        echo '<input type="text"value="Pago" />';
      }else{
        echo '<input type="text" value="Pendente"/>';
      }
      echo '      </td>';        
      echo '      <td>';     
      if ($gastos['baixa']){
        echo '        <a href="?pagina=pagamento&id='.$gastos['id_conta'].'&baixa=0&tipo=1&comp='.$comp.'" class="btn btn-danger glyphicon glyphicon-floppy-saved "></a>';    
      }else{
        echo '        <a href="?pagina=pagamento&id='.$gastos['id_conta'].'&baixa=1&tipo=1&comp='.$comp.'" class="btn btn-primary glyphicon glyphicon-floppy-saved "></a>';    
      }
      echo '      </td>';             
      echo '    </tr>';    
      $total = $total + $gastos['vlr'];  
    }
    echo '    <tr>';    
    echo '      <td></td>';
    echo '      <td></td>';
    echo '    </tr>';  
    echo '    <tr>';  
    echo '      <td><h3>Total</h3></td>';
    echo '      <td>';
    echo '      </td>';        
    echo '    </tr>';  
    echo '    <tr style="border-top:1px solid;">';
    echo '      <td style="background-color: red;">';
    echo '        <h4>Total a Pagar</h4>';
    echo '      </td>';    
    echo '      <td style="background-color: red;">';
    echo '         <h4>R$ '.number_format($total - $total_pago , 2, ',', '').'</h4>';  
    echo '      </td>';    
    echo '      <td style="background-color: red; color: White;">';  
    echo '      </td>';    
    echo '      <td style="background-color: Blue; color: White;">';
    echo '        <h4>Total Pago</h4>';  
    echo '      </td>';        
    echo '      <td style="background-color: Blue; color: White;">';
    echo '         <h4>R$ '.number_format($total_pago, 2, ',', '').'</h4>';    
    echo '      </td>';
    echo '      <td style="background-color: Blue; color: White;">';  
    echo '      </td>';  
    echo '    </tr>';
    echo '  </tbody>';
    echo '</table>';                                 
}


function gastos($conexao, $usuario, $compet){
  if ($compet == ''){exit();}else{
    $data = date("d/m/Y", strtotime('01/'.$compet));
  }
  $mes =  ltrim(date("m",strtotime($data)),0);
  $ano =  date("Y",strtotime($data));
  
  mysqli_query($conexao,"CREATE  TEMPORARY TABLE Gastos
                                   select cv.dsc_compra, cv.vlr_compra, parc.ordem,
                                          parc.baixa, parc.id_parcela , ct.dsc_conta as dsc_conta_padrao
                                     from compra_variavel cv, parcelas parc, conta ct
                                    where cv.id_compra = parc.id_compra
                                      and cv.id_conta = ct.id_conta
                                      and conta_geral = 1
                                      and parc.data_vcto = concat($mes,'/',$ano)
                                    union all
                                   select cv.dsc_compra, cv.vlr_compra, parc.ordem, 
                                          parc.baixa , parc.id_parcela, ct.dsc_conta as dsc_conta_padrao
                                     from compra_variavel cv, parcelas parc, conta ct
                                    where cv.id_compra = parc.id_compra
                                      and cv.id_conta = ct.id_conta
                                      and conta_geral = 0
                                      and usuario_id = $usuario
                                      and parc.data_vcto = concat($mes,'/',$ano)
                                      union all
                                      select ct.dsc_conta as dsc_compra, vlr_fixo, parc.ordem,
                                             parc.baixa, parc.id_parcela, ct.dsc_conta as dsc_conta_padrao
                                        from fixo fx, parcelas parc, conta ct
                                       where fx.id_fixo = parc.id_fixo
                                         and fx.id_conta = ct.id_conta
                                         and fixo_ativo = 1
                                         and conta_geral = 1                                         
                                         and parc.data_vcto = concat($mes,'/',$ano)
                                       union all
                                       select ct.dsc_conta as dsc_compra, vlr_fixo, parc.ordem,
                                              parc.baixa, parc.id_parcela, ct.dsc_conta as dsc_conta_padrao
                                         from fixo fx, parcelas parc, conta ct
                                        where fx.id_fixo = parc.id_fixo
                                          and fx.id_conta = ct.id_conta
                                          and fixo_ativo = 1
                                          and conta_geral = 0
                                          and usuario_id = $usuario
                                          and parc.data_vcto = concat($mes,'/',$ano);");
$contas = mysqli_query($conexao,"Select * from Gastos order by dsc_conta_padrao, dsc_compra;");

mysqli_query($conexao,"CREATE  TEMPORARY TABLE Total_Pago(vlr_conta_total DECIMAL(8,2));");
mysqli_query($conexao,"insert into Total_Pago
                       select (case when sum(case when vlr_fixo is null then 0 
                                             else vlr_fixo end) > 0 then sum(case when vlr_fixo is null then 0 
                                                                             else vlr_fixo end) 
                               else 0 end) 
                         from fixo fx, parcelas parc
                        where fx.id_fixo = parc.id_fixo
                          and parc.baixa = 1
                          and conta_geral = 1 
                          and parc.data_vcto = concat($mes,'/',$ano);");

mysqli_query($conexao,"insert into Total_Pago
                       select (case when sum(case when vlr_fixo is null then 0 
                                             else vlr_fixo end) > 0 then sum(case when vlr_fixo is null then 0 
                                                                             else vlr_fixo end) 
                               else 0 end)
                         from fixo fx, parcelas parc
                        where fx.id_fixo = parc.id_fixo
                          and parc.baixa = 1
                          and conta_geral = 0
                          and usuario_id = $usuario 
                          and parc.data_vcto = concat($mes,'/',$ano);");

mysqli_query($conexao,"insert into Total_Pago
                       select (case when sum(case when vlr_compra is null then 0 
                                             else vlr_compra end) > 0 then sum(case when vlr_compra is null then 0 
                                                                               else vlr_compra end) 
                                             else 0 end)
                         from compra_variavel cv, parcelas parc 
                        where cv.id_compra = parc.id_compra
                          and parc.baixa = 1
                          and conta_geral = 1
                          and parc.data_vcto = concat($mes,'/',$ano);");

mysqli_query($conexao,"insert into Total_Pago
                       select (case when sum(case when vlr_compra is null then 0 
                                             else vlr_compra end) > 0 then sum(case when vlr_compra is null then 0 
                                                                               else vlr_compra end) 
                                             else 0 end)
                        from compra_variavel cv, parcelas parc 
                       where cv.id_compra = parc.id_compra
                         and parc.baixa = 1
                         and conta_geral = 0
                         and usuario_id = $usuario 
                         and parc.data_vcto = concat($mes,'/',$ano);");

$pagto = mysqli_query($conexao,"select sum(vlr_conta_total) tot_pago from Total_Pago;");

foreach($pagto as $pago){
  $total_pago = $pago['tot_pago'];
}                                   
  $total = 0;
  echo '<table  class="table table-striped">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <td>';
  echo '        <b>Conta</b>';
  echo '      </td>';  
  echo '      <td>';
  echo '        <b>Compra</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Valor</b>';
  echo '      </td>';
  echo '      <td>';
  echo '        <b>Parcela</b>';
  echo '      </td>';    
  echo '      <td>';
  echo '        <b>Status</b>';
  echo '      </td>';  
  echo '      <td>';
  echo '        <b>Ação</b>';
  echo '      </td>';    
  echo '    </tr>';
  echo '  </thead>';  
  echo '  <tbody>';  
  foreach($contas as $gastos){    
    echo '    <tr>';
    echo '      <td>';
    echo          $gastos['dsc_conta_padrao'];
    echo '      </td>';    
    echo '      <td>';
    echo          $gastos['dsc_compra'];
    echo '      </td>';
    echo '      <td>';
    echo         'R$ '.number_format($gastos['vlr_compra'], 2, ',', '');
    echo '      </td>';       
    echo '      <td>';
    echo          $gastos['ordem'];
    echo '      </td>';    
    echo '      <td>';
    if ($gastos['baixa']){      
      echo '<input type="text"value="Pago" />';
    }else{
      echo '<input type="text" value="Pendente"/>';
    }
    echo '      </td>';        
    echo '      <td>';     
    if ($gastos['baixa']){
      echo '        <a href="?pagina=pagamento&id='.$gastos['id_parcela'].'&baixa=0&tipo=0" class="btn btn-danger glyphicon glyphicon-floppy-saved "></a>';    
    }else{
      echo '        <a href="?pagina=pagamento&id='.$gastos['id_parcela'].'&baixa=1&tipo=0" class="btn btn-primary glyphicon glyphicon-floppy-saved "></a>';    
    }
    echo '      </td>';    
    
    echo '    </tr>';    
    $total = $total + $gastos['vlr_compra'];  
  }
  echo '    <tr>';    
  echo '      <td></td>';
  echo '      <td></td>';
  echo '      <td>';
  echo '      </td>';        
  echo '      <td>';
  echo '      </td>';        
  echo '      <td>';
  echo '      </td>';   
  echo '      <td>';
  echo '      </td>';                  
  echo '    </tr>';  
  echo '    <tr>';  
  echo '      <td><h3>Total</h3></td>';
  echo '      <td>';
  echo '      </td>';        
  echo '      <td>';
  echo '      </td>';        
  echo '      <td>';
  echo '      </td>';   
  echo '      <td>';
  echo '      </td>';                
  echo '      <td>';
  echo '      </td>';                  
  echo '    </tr>';  
  echo '    <tr style="border-top:1px solid;">';
  echo '      <td style="background-color: red;">';
  echo '        <h4>Total a Pagar</h4>';
  echo '      </td>';    
  echo '      <td style="background-color: red;">';
  echo '         <h4>R$ '.number_format($total - $total_pago , 2, ',', '').'</h4>';  
  echo '      </td>';    
  echo '      <td style="background-color: red; color: White;">';  
  echo '      </td>';    
  echo '      <td style="background-color: Blue; color: White;">';
  echo '        <h4>Total Pago</h4>';  
  echo '      </td>';        
  echo '      <td style="background-color: Blue; color: White;">';
  echo '         <h4>R$ '.number_format($total_pago, 2, ',', '').'</h4>';    
  echo '      </td>';
  echo '      <td style="background-color: Blue; color: White;">';  
  echo '      </td>';  
  echo '    </tr>';
  echo '  </tbody>';
  echo '</table>';
}

function insere_conta($conexao, $dsc_conta, $tipo, $vcto){
    mysqli_query($conexao,"insert into conta (dsc_conta, fixo_variavel) values('".$dsc_conta."', '".$tipo."')");
    $sql = mysqli_query($conexao,"select id_conta from conta order by id_conta desc limit 1");
    foreach($sql as $conta){
      $id_conta = $conta['id_conta'];
    }
    mysqli_query($conexao,"insert into vcto_cartoes (id_conta, dia_vcto)
                                      values(".$id_conta.",".$vcto.")");

}

function deleta_conta($conexao, $id_compra){
    mysqli_query($conexao,"delete from conta where id_conta = ".$id_compra);

}

function deleta_conta_variavel($conexao, $id_compra){
  $erro = "Não foi possível deletar a compra variável";
    mysqli_query($conexao,"delete from compra_variavel where id_compra = ".$id_compra);
    mysqli_query($conexao,"delete from parcelas where id_compra = ".$id_compra);

}

function deleta_conta_fixa($conexao, $id_fixo){
    mysqli_query($conexao,"delete from fixo where id_fixo = ".$id_fixo);
    mysqli_query($conexao,"delete from parcelas where id_fixo = ".$id_fixo);

}

function insere_conta_fixa($conexao, $id_conta, $vlr_fixo, $vencto_fixo, $ativo, $usuario, $geral){
  $erro = "Não foi possível inserir nova conta fixa";
  if ($ativo == ''){
    $ativo = 0;
  }
  if ($geral == ''){
    $geral = 0;
  }
    mysqli_query($conexao,"insert into fixo (id_conta, vlr_fixo, dia_vencto, fixo_ativo, conta_geral, usuario_id)
                                      values(".$id_conta.", ".$vlr_fixo.", '.$vencto_fixo.', ".$ativo.", ".$geral.",".$usuario.")");
}

function insere_conta_variavel($conexao, $id_conta, $dsc_compra, $vlr_compra, $dt_compra, $qtd_parcela, $geral, $usuario){
  if ($geral == ''){
    $geral = 0;
  }  
    mysqli_query($conexao,"insert into compra_variavel (id_conta, dsc_compra, vlr_compra, data_compra, parcela, conta_geral, usuario_id)
                                      values(".$id_conta.",'".$dsc_compra."', ".$vlr_compra.", '".$dt_compra."', ".$qtd_parcela.", ".$geral.", ".$usuario.")");    

    $id_compra = mysqli_query($conexao,"SELECT id_compra FROM compra_variavel order by id_compra desc limit 1");

    foreach($id_compra as $compra){
      $id = $compra['id_compra'];
    }

    mysqli_query($conexao,"CALL parcelamento($id)");

}

function pagamento($conexao, $id, $baixa, $tipo, $comp){
    if ($tipo == 0){    
      mysqli_query($conexao,"update parcelas
                                set baixa = ".$baixa."
                              where id_parcela = ".$id); 
    }else{      
      mysqli_query($conexao,"update parcelas
                              inner join compra_variavel cv on parcelas.id_compra = cv.id_compra
                                set baixa = ".$baixa."
                              where cv.id_conta = ".$id."
                                and parcelas.data_vcto = '".$comp."'");
      mysqli_query($conexao,"update parcelas
                              inner join fixo fix on parcelas.id_fixo = fix.id_fixo
                                set baixa = ".$baixa."
                              where fix.id_conta = ".$id."
                                and parcelas.data_vcto = '".$comp."'");                                
    }
  
}

function usuario($conexao, $usuario, $senha){

    mysqli_query($conexao,"update cad_usuarios 
                              set usuario_login = '{$usuario}',
                                  usuario_senha = md5('{$senha}')
                            where usuario_login = '{$usuario}'");

}
