<?php
 $NO_MOSTRAR_BARRA_DE_BOTONES_PARA_MANTENIMIENTO=1;
 include "../econx.php";
 
 $campos=array('empre_cod_empre','ofici_cod_ofici');
 borrar_campos_globales($campos);
 $s=MostrarBarra1();
 $s2=MostrarBarra2();
 $elementos=array('cboton1','cboton2');
 print "<div style=\"position: absolute; left: 0px; top: 0px\" id=\"cboton1\">$s</div>";
 print "<div style=\"position: absolute; left: 0px; top: 0px\" id=\"cboton2\">$s2</div>";
 BR(2);
 $o=oculta_elementos_de_pagina('cboton2');
 print "<script>
         $o
         </script>";
 ocultar_mostrar($elementos);
 MostrarTitulo(" ANALISIS DE INDICADORES Y 5C'S DE CREDITO.");
 ObjetoAjax();
 $img = '<p align="center"><img  border="0" src="../iconos/ajax-loader.gif"><font size=2><b>Cargando Información...!</b></font></p>';
 $usuario=tabla_usuario();
 print "<script>
	jQuery().ready(function ()
	{
		$('#txtideclien').keypress(function(e)
		{
             if(e.keyCode == 13)
             {
                 var ideclien=$(this).val();
                 if(ideclien.length>0)
                 {
                     fnclista_clien();
                 }
             }
		});                                      
	});
	window.moveTo(0,0);
	window.resizeTo(screen.availWidth, screen.availHeight);
	function cancelar()
	{
       ".original_np('','cancelar')."
	}
	function soloNumeros(e)
	{
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = '0123456789';
        especiales = [8];
        tecla_especial = false
        for(var i in especiales)
        {
            if(key == especiales[i]) 
            {
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla) == -1 && !tecla_especial)
            return false;
	} 
	function nuevo()
	{
		document.getElementById('modo').value=1;
		document.getElementById('txtideclien').disabled=false;
		document.getElementById('txtideclien').focus();
	}
   function fnclista_clien()
   {
        var opcion=1;
        var txtideclien=document.getElementById('txtideclien').value;        
        cadena='opcion='+opcion+'&txtideclien='+txtideclien;
        ajax=objetoAjax();
        ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
        ajax.onreadystatechange=function()
        {
            if (ajax.readyState==4)
            {
                var campos=ajax.responseText.split('|');
                if(campos[0] > 1)
                {
                    srtdat_clien();
                }
                else
                {
                    if(campos[0] == 1)
					          {
                        document.getElementById('txtcliempre').value=campos[1];
                        document.getElementById('txtcliofici').value=campos[2];
                        document.getElementById('txtnomclien').innerHTML=campos[4];
                        srtdat_solic();
                    }
                    else
                    {
                        cancelar();
                    }
                }
            }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(cadena);
	}
	function srtdat_clien()
	{
		document.getElementById('clientes').style.display='';
		document.getElementById('clientes').innerHTML='$img';
		var opcion=2;
		var txtideclien=verobjeto('txtideclien').value;
		cadena='opcion='+opcion+'&txtideclien='+txtideclien;
		ajax=objetoAjax();
		ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4)
			{
				document.getElementById('clientes').innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(cadena);
	}
	function cerrar(obj)
	{
		document.getElementById('clientes').style.display='none';
	}
	function mostrar_cli(txtcliempre,txtcliofici,txtideclien,txtnomclien)
	{
		document.getElementById('txtcliempre').value=txtcliempre;
		document.getElementById('txtcliofici').value=txtcliofici;
		document.getElementById('txtideclien').value=txtideclien;
		document.getElementById('txtnomclien').innerHTML=txtnomclien;
		cerrar();
		srtdat_solic();
	}
	function srtdat_solic()
	{
		var opcion=3;
		var gl0codempre=document.getElementById('txtcodempre').value;
		var gl0codofici=document.getElementById('txtcodofici').value;
		var txtcliempre=document.getElementById('txtcliempre').value;
		var txtcliofici=document.getElementById('txtcliofici').value;
		var txtideclien=document.getElementById('txtideclien').value;
		cadena='opcion='+opcion+'&gl0codempre='+gl0codempre+'&gl0codofici='+gl0codofici+'&txtcliempre='+txtcliempre+'&txtcliofici='+txtcliofici+'&txtideclien='+txtideclien;
		ajax=objetoAjax();
		ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4)
			{
                var campos=ajax.responseText.split('@');
                var contar=campos.length;
                if(contar > 1)
                {
                    document.getElementById('mfvsl_cod_solic').disabled=false;
                    document.getElementById('mfvsl_cod_solic').options.length=0;
                    var codigo = new Array();
                    var nombre = new Array();
                    for(x=0;x<contar - 1;x++)
                    {
                          campos_a=campos[x].split('|');
                          codigo[x]=html_js(campos_a[0]);
                          nombre[x]=html_js(campos_a[1]);
                          document.getElementById('mfvsl_cod_solic').options[x]=new Option(nombre[x],codigo[x]);
                    }
					mostrarTabla();
                }
                else
                {
                    alert('No existe una Solicitud de Verificacion Generada...!');
					cancelar();
                }
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(cadena);
	}
	function mostrarTabla()
	{
		var ctr_codsolic=verobjeto('mfvsl_cod_solic').value.length;
		if(ctr_codsolic > 0)
		{
      var opcion=5;
			var txtcodsolic=document.getElementById('mfvsl_cod_solic').value;
			var txtcliempre=document.getElementById('txtcliempre').value;
			var txtcliofici=document.getElementById('txtcliofici').value;
			var txtideclien=document.getElementById('txtideclien').value;
			var txtnomclien=document.getElementById('txtnomclien').innerHTML;
			cadena='opcion='+opcion+'&txtcodsolic='+txtcodsolic+'&txtideclien='+txtideclien+'&txtnomclien='+txtnomclien;
			cadena=cadena+'&txtcliempre='+txtcliempre+'&txtcliofici='+txtcliofici;
			ajax=objetoAjax();
			ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4)
				{
					document.getElementById('msfanexo').innerHTML=ajax.responseText;
					document.getElementById('modo').value=2;
				}
			}
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(cadena);
		}
	}
	
    function montoMaximoOtorgado()
	{
		var txtvalor_trans=verobjeto('txtvalor_trans').value.length;
		if(txtvalor_trans > 0)
		{
      var opcion=6;
			var txtcodsolic=document.getElementById('mfvsl_cod_solic').value;
			var txtvalor_trans=document.getElementById('txtvalor_trans').value;
        var patElement = document.getElementById('montoValidado');
			cadena='opcion='+opcion+'&txtvalor_trans='+txtvalor_trans+'&txtcodsolic='+txtcodsolic;
			ajax=objetoAjax();
			ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4)
				{
                    var campos=ajax.responseText;
            patElement.innerHTML =campos;
            document.getElementById('montoValidado2').value=campos;
            sumarTotal();

					//alert('holassss');
				}
			}
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(cadena);
		}
	}
    function seleccionCambio() {
        var seleccion = document.getElementById('ingreso').value;
        var patElement = document.getElementById('pat');
        if (seleccion === '1') {
            patElement.innerHTML = 10;
            document.getElementById('patt').value=10;
            sumarTotal();
        } else if (seleccion === '0') {
            patElement.innerHTML = 0;
            document.getElementById('patt').value=0;
            sumarTotal();
        }
    }
    function seleccionCambio1() {
        var seleccion = document.getElementById('ingreso1').value;
        var patElement = document.getElementById('pat1');
        
        if (seleccion === '1') {
            patElement.innerHTML = '10';
            document.getElementById('patt1').value=10;
            sumarTotal();
        } else if (seleccion === '0') {
            patElement.innerHTML = '0';
            document.getElementById('patt1').value=0;
            sumarTotal();
        }
    }
    function seleccionCambio2() {
        var seleccion = document.getElementById('ingreso2').value;
        var patElement = document.getElementById('pat2');
        
        if (seleccion === '1') {
            patElement.innerHTML = '10';
            document.getElementById('patt2').value=10;
            sumarTotal();
        } else if (seleccion === '0') {
            patElement.innerHTML = '0';
            document.getElementById('patt2').value=0;
            sumarTotal();
        }
    }
    function sumarTotal() {
        var pat = document.getElementById('patt').value;
        var pate = parseInt(pat);
        var pat1 = document.getElementById('patt1').value;
        var pate1 = parseInt(pat1);
        var pat2 = document.getElementById('patt2').value;
        var pate2 = parseInt(pat2);
        var montoValido = document.getElementById('montoValidado2').value;
        var montoValidoe2 = parseInt(montoValido);
        var aux = document.getElementById('aux').value;
        var auxe = parseInt(aux);
        
        var aux1 = document.getElementById('aux1').value;
        var auxe1 = parseInt(aux1);
        var aux2 = document.getElementById('aux2').value;
        var auxe2 = parseInt(aux2);
        var aux3 = document.getElementById('aux3').value;
        var auxe3 = parseInt(aux3);
        var aux4 = document.getElementById('aux4').value;
        var auxe4 = parseInt(aux4);
        
        var to_total = document.getElementById('to_total');
        var Sum=pate+pate1+pate2+montoValidoe2+auxe+auxe1+auxe2+auxe3+auxe4;
        //alert(Sum);
        if(Sum>80){
            to_total.innerHTML = 'CUMPLE';
            to_total.style.backgroundColor = 'green';}
            else{
                to_total.innerHTML= 'NO CUMPLE';
                to_total.style.backgroundColor = 'red';}
        
    }
    function C(e,t){
		var m=null;
		(e.keyCode) ? m=e.keyCode : m=e.which;
		if(m==13) (!t) ? D() : t.focus();
	}  
    function justNumberstrans(e){
		var keynum = window.event ? window.event.keyCode : e.which;
		if ((keynum == 8) || (keynum == 46))
			return true;
			return /\d/.test(String.fromCharCode(keynum));
	}

    function previaImprimir(){
        var txtcodsolic=document.getElementById('mfvsl_cod_solic').value;
			var txtcliempre=document.getElementById('txtcliempre').value;
			var txtcliofici=document.getElementById('txtcliofici').value;
			var txtideclien=document.getElementById('txtideclien').value;
			var txtvalor_trans=document.getElementById('txtvalor_trans').value;
			var desicionaprobado=document.getElementById('desicionaprobado');
			var desicionsuspenso=document.getElementById('desicionsuspenso');
			var desicionnegado=document.getElementById('desicionnegado');
            if(desicionaprobado.checked==true){
                desicion=1;
            }
            if(desicionsuspenso.checked==true){
                desicion=2;
            }
            if(desicionnegado.checked==true){
                desicion=0;
            }
			var tipo_persona_publico = document.getElementById('tipo_persona_publico');
        var tipo_persona_privada = document.getElementById('tipo_persona_privada');

        if(tipo_persona_privada.checked==true){
            tipo_persona=0;
        }
        if(tipo_persona_publico.checked==true){
            tipo_persona=1;
        }
			var txtnomclien=document.getElementById('txtnomclien').innerHTML;
			var patt=document.getElementById('patt').value;
			var patt1=document.getElementById('patt1').value;
			var patt2=document.getElementById('patt2').value;
			var comentario=document.getElementById('contrato').value;
			var montoValidado2=document.getElementById('montoValidado2').value;
        var ingreso = document.getElementById('ingreso').value;
        var ingreso1 = document.getElementById('ingreso1').value;
        var ingreso2 = document.getElementById('ingreso2').value;

        if(ingreso==''){
            alert1('Seleccione la opcion de valor avaluo');
        } else if(montoValidado2=='')
        {
            alert1('Ingrese el monto maximo otrogado por otras instituciones');
        }else if(ingreso1==''){
            alert1('Seleccione la opcion de rentabilidad');
        }else if(ingreso2==''){
        alert1('Seleccione la opcion de caracter');
    }else if(comentario==''){
            alert1('Ingrese un comentario');
        }else if (ingreso!='' && ingreso1!='' && ingreso2!='' && montoValidado2!='' && comentario!=''){
        var pat = document.getElementById('patt').value;
        var pate = parseInt(pat);
        var pat1 = document.getElementById('patt1').value;
        var pate1 = parseInt(pat1);
        var pat2 = document.getElementById('patt2').value;
        var pate2 = parseInt(pat2);
        var montoValido = document.getElementById('montoValidado2').value;
        var montoValidoe2 = parseInt(montoValido);
        var aux = document.getElementById('aux').value;
        var auxe = parseInt(aux);
        
        var aux1 = document.getElementById('aux1').value;
        var auxe1 = parseInt(aux1);
        var aux2 = document.getElementById('aux2').value;
        var auxe2 = parseInt(aux2);
        var aux3 = document.getElementById('aux3').value;
        var auxe3 = parseInt(aux3);
        var aux4 = document.getElementById('aux4').value;
        var auxe4 = parseInt(aux4);

        var Sum=pate+pate1+pate2+montoValidoe2+auxe+auxe1+auxe2+auxe3+auxe4;


        var opcion=8;
        cadena='opcion='+opcion+'&patt='+patt+'&patt1='+patt1+'&patt2='+patt2
        +'&montoValidado2='+montoValidado2+'&ingreso='+ingreso+'&ingreso1='+ingreso1+'&ingreso2='+ingreso2
        +'&txtcodsolic='+txtcodsolic+'&txtideclien='+txtideclien+'&txtnomclien='+txtnomclien
        +'&txtcliempre='+txtcliempre+'&txtcliofici='+txtcliofici+'&txtvalor_trans='+txtvalor_trans+'&Sum='+Sum
        +'&desicion='+desicion+'&tipo_persona='+tipo_persona+'&comentario='+comentario;
        //alert(cadena)
        ajax=objetoAjax();
        ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
        ajax.onreadystatechange=function()
        {
            if (ajax.readyState==4)
            {
                strimprimir();
                grabarRespaldo();
            }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(cadena);
    }
    
    }

	function strimprimir()
	{
        if(isNaN(document.getElementById('ctropcio'))==false) return;
        else
        {
			var ctropcio=document.getElementById('ctropcio').value;
			if(ctropcio==1)
			{
                var usuario=\"$usuario\";
                var p='../impresiones/cartera_de_credito/impresiones/' + usuario + 'rptanlsindicrd.html';
                var opc='toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1000, height=400, top=85, left=140';
                window.open(p,'rptanlsindicrd',opc);
			}
			else
			{
				alert('No tiene Informacion a Imprimir...!');
				return;
			}
		}
	}

    function grabarRespaldo(){
        
        var mfvsl_cod_solic = document.getElementById('mfvsl_cod_solic').value;
        var txtideclien = document.getElementById('txtideclien').value;
        var desicionaprobado=document.getElementById('desicionaprobado');
        var desicionsuspenso=document.getElementById('desicionsuspenso');
        var desicionnegado=document.getElementById('desicionnegado');
        if(desicionaprobado.checked==true){
            desicion=1;
        }
        if(desicionsuspenso.checked==true){
            desicion=2;
        }
        if(desicionnegado.checked==true){
            desicion=0;
        }
        var contrato = document.getElementById('contrato').value;

        var tipo_persona_publico = document.getElementById('tipo_persona_publico');
        var tipo_persona_privada = document.getElementById('tipo_persona_privada');

        if(tipo_persona_privada.checked==true){
            tipo_persona=0;
        }
        if(tipo_persona_publico.checked==true){
            tipo_persona=1;
        }

        var ingreso = document.getElementById('ingreso').value;
        var patt = document.getElementById('patt').value;
        var ingreso1 = document.getElementById('ingreso1').value;
        var patt1 = document.getElementById('patt1').value;
        var ingreso2 = document.getElementById('ingreso2').value;
        var patt2 = document.getElementById('patt2').value;
        var txtvalor_trans = document.getElementById('txtvalor_trans').value;
        var montoValidado2 = document.getElementById('montoValidado2').value;
        var tipo_credito = document.getElementById('tipo_credito').value;

        var pat = document.getElementById('patt').value;
        var pate = parseInt(pat);
        var pat1 = document.getElementById('patt1').value;
        var pate1 = parseInt(pat1);
        var pat2 = document.getElementById('patt2').value;
        var pate2 = parseInt(pat2);
        var montoValido = document.getElementById('montoValidado2').value;
        var montoValidoe2 = parseInt(montoValido);
        var aux = document.getElementById('aux').value;
        var auxe = parseInt(aux);
        
        var aux1 = document.getElementById('aux1').value;
        var auxe1 = parseInt(aux1);
        var aux2 = document.getElementById('aux2').value;
        var auxe2 = parseInt(aux2);
        var aux3 = document.getElementById('aux3').value;
        var auxe3 = parseInt(aux3);
        var aux4 = document.getElementById('aux4').value;
        var auxe4 = parseInt(aux4);

        var Sum=pate+pate1+pate2+montoValidoe2+auxe+auxe1+auxe2+auxe3+auxe4;

        var opcion=7;
        cadena='opcion='+opcion+'&mfvsl_cod_solic='+mfvsl_cod_solic+'&txtideclien='+txtideclien+'&desicion='+desicion+'&contrato='+contrato+'&tipo_persona='+tipo_persona+'&tipo_credito='+tipo_credito
        +'&montoValidado2='+montoValidado2+'&patt='+patt+'&patt1='+patt1+'&patt2='+patt2+'&montoValidado2='+montoValidado2+'&Sum='+Sum
        +'&ingreso='+ingreso+'&ingreso1='+ingreso1+'&ingreso2='+ingreso2+'&txtvalor_trans='+txtvalor_trans;
		ajax=objetoAjax();
        //alert1(cadena)
		ajax.open('POST', 'analisis_indicadores_credito_ajax.php',true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4)
			{
                var campos=ajax.responseText;
				//document.getElementById('clientes').innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(cadena);
    }



  </script>";
print "<div style=\"position: absolute; display:none; width: 600px; height: 100px; z-index: 1; border-style: solid; border-width: 2px;left: 100px; top: 150px; background-color: #EDEDEF\" id='clientes'>";
print $img;
print "</div>";
analisis_indicadores_credito();
function analisis_indicadores_credito()
{
     global $CODIGO_EMPRESA,$CODIGO_OFICINA,$FECHA_ACTUAL,$CODIGO_OPERADOR;
     //Recuadro_Inicio();
     $mi=110;$md=140;
     campo_detalle("","modo","3","","5","hidden","","");
     campo_detalle("","txtcodempre","$CODIGO_EMPRESA","",5,'hidden','','');
     campo_detalle("","txtcodofici","$CODIGO_OFICINA","",5,'hidden','','');
     campo_detalle("","txtcliempre","$CODIGO_EMPRESA","",5,'hidden','','');
     campo_detalle("","txtcliofici","$CODIGO_OFICINA","",5,'hidden','','');
     campo_detalle("","gl00codusuar","$CODIGO_OPERADOR","","3","hidden","","","");
     print "<center><div id='contenido' style='width: 850px;padding-bottom: 5px;text-align: left;padding: 2px 5px 3px 6px;margin: 10px 25px;' class='background'>";
     print "<p class='estilo'>
              <label style='display: inline-block'>
              <span style='display: block;width: 120px;'><b>Identificación:</b></span></label>
              <input style='text-align:right;' size=15 type='text' id='txtideclien' name='txtideclien' value='' disabled='true' OnKeyPress='return soloNumeros(event);' MAXLENGTH='13' />
              <label style='width: 20px;'>&nbsp;</label><label id='txtnomclien' style='width: 400px;'>&nbsp;</label>";
     print "</p>
            <p class='estilo'>
              <label style='width: 120px;'><b>Solicitud:</b></label>";
              $cmbsolic=array();
              cselect_op("","","mfvsl_cod_solic",$cmbsolic,"500","","","OnChange='mostrarTabla();'","disabled");
     print "</p>
     </div>";
     print "<div id='msfanexo' name='msfanexo' style='width: 1000px;height:500px;overflow:auto;border:2px solid silver;padding: 5px;margin: 5px 18px;'>
            </div></center>";
}
function MostrarBarra1($t='',$eliminar='')
{
   global $OP,$DATA_MSG,$DIR_IMG,$JUEGO_DE_ICONOS;
   if(empty($JUEGO_DE_ICONOS))$JUEGO_DE_ICONOS=VAL("tema","gnome");
   $dir=$DIR_IMG.'botones/';
   $cf=$dir."barraploma2.gif";
   $s='';
   $s=$s.tIniTabla('','1','','','',"0","0");
   $s=$s.tIniFila();
   $s=$s.DibujarBotonJavaScript("nuevo",    "Nuevo",     "onclick='ocultar_mostrarcboton2(\"cboton2\"); nuevo();'");
   $s=$s.tIniEncabezadoTabla($cf);
   $s=$s.tFinEncabezadoTabla();
   $s=$s.tFinFila();
   $s=$s.tFinTabla();
   return "$s";
}
function MostrarBarra2($t='',$eliminar='')
{
   global $OP,$DATA_MSG,$DIR_IMG,$JUEGO_DE_ICONOS;
   if(empty($JUEGO_DE_ICONOS))$JUEGO_DE_ICONOS=VAL("tema","gnome");
   $dir=$DIR_IMG.'botones/';
   $cf=$dir."barraploma2.gif";
   $s='';
   $s=$s.tIniTabla('','1','','','',"0","0");
   $s=$s.tIniFila();
   $s=$s.DibujarBotonJavaScript("imprimir1",    "Imprimir",     "onclick='previaImprimir();'");
   $s=$s.DibujarBotonJavaScript("cancelar",    "Cancelar",     "onclick='cancelar();'");
   $s=$s.tIniEncabezadoTabla($cf);
   $s=$s.tFinEncabezadoTabla();
   $s=$s.tFinFila();
   $s=$s.tFinTabla();
   return "$s";
}
fin();
?>
