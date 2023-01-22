
//Esconder/ mostrar menu
var mostrando=0;
function most()
{
   document.getElementById("vv").className="mmenu1 on";
   mostrando=1
}
function sumi()
{
   document.getElementById("vv").className="mmenu1";
   mostrando=0
}
function verificar()
{
   if(mostrando==0){most()}else{sumi()}
}