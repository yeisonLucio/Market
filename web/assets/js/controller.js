
$(function(){
    $('.modal').modal();
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown();
    $('select').formSelect();
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();
    $('.tooltipped').tooltip();
    $('.tabs').tabs();
  M.textareaAutoResize($('#appbundle_producto_descripcion'));
    
  

    $(".nav > li").click(function() {
        //Busca todos los elementos del nav que tengan la clase active y los elimina
      $(this).closest('.nav').find('li').removeClass('active');
      //Al elemento seleccionado agrega la clase active
     console.log("paso por aqui")
     $(this).addClass('active');
    });
    
});



function cargarVista(contenedor,url){
    console.log(url)
    $(contenedor).load(url);
}

function cargarContenido(contenedor, url){

    $.ajax({
        type: "POST",
        url: url,
        success: function(resultado){
            console.log(resultado);
            $(contenedor).html(resultado);   
        }
    });

}

function datos(){
    $data = $("#formEditarUsuario").serialize()
    alert($data);
}

function guardar(form,url,op,path){

    var data = $(form).serialize();
   // console.log(data);
        $("#preload").addClass("active");
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(resultado){
              console.log(resultado);
            
            if(resultado.status == 200){
                $("#preload").removeClass("active");
                var alert="<div><span><i class='small material-icons right'>check_circle</i> Registrado con exito</span></div>";
                M.toast({html: alert, classes: "teal accent-4 white-text"});
                if (op==0) {
                    cargarVista("#contenedor_listaUsuarios","/usuario/lista");     
                }else if(op==1){
                    cargarVista("#contenedor_listaCategoria",path); 
                }
               
            }else{
                    $.each(resultado.form, function(key,value){
                        console.log("key"+key+" value"+value);
                    
            
                    }); 
            
              

            }
            
            
          }
        });
     
    
  
  }

function eliminar(url,op,path){
    $.ajax({
        type: "POST",
        url: url,
        success: function(resultado){
            console.log(resultado);
            if(resultado.status == 200){
                var alert="<div><span><i class='small material-icons right'>check_circle</i> Registrado eliminado!</span></div>";
                M.toast({html: alert, classes: "teal accent-4 white-text"});
                if(op==0){

                }else if(op==1){
                    cargarVista("#contenedor_listaCategoria",path); 

                }else if(op==2){
                    cargarVista("#contenedor_listaUsuarios",path); 

                }

            }else{
                var alert="<div><span><i class='small material-icons right'>error</i> Error al eliminar</span></div>";
                M.toast({html: alert, classes: "teal accent-4 white-text"});
            }
        }
    });

}

  function crearUsuario(form,url){

    var data = $(form).serialize();

        $("#preload").addClass("active");
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(resultado){

            if(resultado.status == 200){
                $("#preload").removeClass("active");
                var alert="<div><span><i class='small material-icons right'>error</i> Registrado con exito</span></div>";
                M.toast({html: alert, classes: "teal accent-4 white-text"});

            }
            
           
          }
        });
     
    
  
  }






/* $(function(){

    $('.soportesAdmin').click(function(){
        console.log("voy a cargar la vista inicio");
        $("#contenedor").load('{{ path('soportes_admin') }} #contenedor_soportesAdmin');
       
    });
     
    $('.usuarios').click(function(){
        console.log("voy a cargar la vista inicio");
        $("#contenedor").load('{{ path('lista_usuarios') }} #contenedor_listaUsuarios');

    });
    $('.tipo_soportes').click(function(){
        console.log("voy a cargar la vista inicio");
        $("#contenedor").load('{{ path('tiposoporte_index') }} #contenedor_tipoSoportes');

    });
    $("#contenedor").on("click","#newUsuario",function(e){
            $("#contenedor").load('{{ path('usuario_new') }} ');
       
    });

});

function Eliminar($url, $url_cargar){
    $.ajax({
        type: "POST",
        url: $url,
        success: function(resultado){
            if(resultado.status==200){
                $("#contenedor").load(""+$url_cargar+"");
                var alert="<div><span>Eliminado con exito</span></div>";
                Materialize.toast(alert, 4000);

            }else{
                var alert="<div><span>Existen elementos relacionados con ese registro</span></div>";
                Materialize.toast(alert, 4000);
            }
        }
    });

}

function Buscar(){
    $url = '{{ path('buscar') }}';
    $nombre = $('#search').val();
    $.ajax({
        type: "POST",
        url: $url,
        data: {nombre: $nombre},
        success: function(resultado){
                

            if(resultado.status==400){
               
               var alert="<div><span>Usuario no encontrado</span></div>";
                Materialize.toast(alert, 4000);
            }else{
                
                $("#contenedor").html(resultado);
                $('#btn_buscar').click(function(){
                    Buscar();
                });
            }
        }
    });

}

function GuardarUsuario($url){
    var data = $("#formRegistroUsuario").serialize();
    
    $.ajax({
        type: "POST",
        url: $url,
        data: data,
        success: function(resultado){
            console.log(resultado.status);
            if(resultado.status==200){
                var alert="<div><span>Guardado correctamente</span></div>";
                Materialize.toast(alert, 4000)
                vaciarCamposUser();

            }
            
        }
    });
}

function GuardarTipoSoporte($url){
    var data = $("#formTipoSoporte").serialize();

    $nombreTipoSoporte = $("#appbundle_tiposoporte_nombre").val();
    
    if($nombreTipoSoporte==""){
         var alert="<div><span>Ingrese los datos</span></div>";
            Materialize.toast(alert, 4000)

    }else{
         $.ajax({
        type: "POST",
        url: $url,
        data: data,
        success: function(resultado){
            console.log(resultado.status);
            if(resultado.status==200){
                var alert="<div><span>Guardado correctamente</span></div>";
                Materialize.toast(alert, 4000)
                

            }
            
        }
    });

    }
   
}



function cerrar(){
    $("#contenedor_tomarSoporte").removeClass("hide");
}

function revisarSoporte($url,$id){
    
    console.log($url+""+$id);
    $.ajax({
        type: "POST",
        url: $url,
        data: {
            id: $id
        },
        success: function(resultado){
            console.log(resultado.asunto);
            $asunto = resultado.asunto;
            $descripcion = resultado.descripcion;
            $id = resultado.id;
            $estadoSoporte = "";
            $prioridadSoporte = "";
            $url = '{{ path('guardar_soporte') }}';
            $idUsuario = resultado.idUsuario;
            $idTipoSoporte = resultado.idTipoSoporte;

            $estado = "";
            $prioridad = "";
               
                $estado="<div class='input-field'><select id='elegir'>\
                            <option value='' disabled selected>Seleccionar Estado</option>\
                            <option value='1' id='listo' >Listo</option>\
                            <option value='2'>Por hacer</option>\
                        </select></div>";
                
                

            
                $prioridad="<div class='input-field'><select id='prioridad'>\
                            <option value='' disabled selected>Seleccionar Prioridad</option>\
                            <option value='1'>1</option>\
                            <option value='2'>2</option>\
                            <option value='3'>3</option>\
                            <option value='4'>4</option>\
                            <option value='5'>5</option>\
                        </select></div>";
            

            $form = "<form>"+$estado+""+$prioridad+"\
                        <div id='conte_descripcion' class='input-field hide'>\
                            <textarea id='descripcion' class='materialize-textarea '></textarea>\
                            <label for='descripcion'>Agregue una decripción</label>\
                        </div>\
                        <a id='btn_guardar' class='btn teal darken-4 waves-effect waves-light right'>Guardar</a>\
                    </form>";

            
            $('#contenedor_tomarSoporte').html(
                "<a id='cerrar' class='btn-floating btn-large teal darken-4 waves-effect waves-light right'><i class='Tiny material-icons '>close</i></a><br><br>"+
                "<h4>"+resultado.asunto+"</h4>"+
                "<p>"+resultado.descripcion+"</p>"+
                $form
                
            );
         
           
            $('select').material_select();
    
            $("#cerrar").click(function(){
                $('#contenedor_tomarSoporte').addClass("hide");
            });

            $('#elegir').change(function(){
                var sel = $('#elegir option:selected').val();
                $estadoSoporte = sel;
                if (sel == "1") {
                    $("#conte_descripcion").removeClass("hide");   
                }else{
                    $("#conte_descripcion").addClass("hide");
                }

            }); 

            $('#prioridad').change(function(){
                var sel = $('#prioridad option:selected').val();
                $prioridadSoporte = sel;
            }); 

            $("#btn_guardar").click(function(){
                guardarDatos($idTipoSoporte,$idUsuario,$prioridadSoporte,$estadoSoporte,$asunto,$id,$descripcion,$url);
                 $("#contenedor").load('{{ path('soportes_admin') }} #contenedor_soportesAdmin');
            });
    } 
    });         
}

function guardarDatos($idTipoSoporte,$idUsuario,$prioridadSoporte,$estadoSoporte,$asunto,$id,$descripcion,$url){
    $descripcionSolucion = $("#descripcion").val();
    $.ajax({
        type: "POST",
        url: $url,
        data: {
            id: $id,
            asunto: $asunto,
            descripcion: $descripcion,
            prioridad: $prioridadSoporte,
            estado: $estadoSoporte,
            descripcionSolucion: $descripcionSolucion,
            idUsuario: $idUsuario,
            idTipoSoporte: $idTipoSoporte

        },
        success: function(resultado){
            console.log(resultado);
        }
    });


}
function cargar($ruta){
    console.log($ruta);
    $("#contenedor").load(''+$ruta+' #contenedor_revisarSoporte');
}
function vaciarCamposUser(){
    $('#appbundle_Usuario_username').val("");
    $('#appbundle_Usuario_plainPassword_first').val("");
    $('#appbundle_Usuario_plainPassword_second').val("");
    $('#appbundle_Usuario_nombre').val("");
    $('#appbundle_Usuario_apellido').val("");
}
function vaciar(){
    $('#appbundle_soporte_asunto').val("");
    $('#appbundle_soporte_descripcion').val("");
} */




