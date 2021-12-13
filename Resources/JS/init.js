function LoadAjaxContent(url){
	$.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
    })
    .done(function(data) {
        $('#ajax-content').html(data);
        $('body').removeClass('sidebar-open');
    });
}
$(document).ready(function () {
	var ajax_url = location.hash.replace(/^#/, '');
	if (ajax_url.length < 1) {
		ajax_url = 'welcome.php';
	}
	LoadAjaxContent(ajax_url);

	$('#myNavbar').on('click', 'a', function (e) {

		if ($(this).hasClass('ajax-link')) {
			e.preventDefault();
			var url = $(this).attr('href');
			window.location.hash = url;
			LoadAjaxContent(url);
		}
		
	});
});

function redirect(url){
    //console.log(window.location.hash);
    window.location.hash = url;
    LoadAjaxContent(url);
}
function showMessage(title, message,type){
    var t;
    switch(type){
        case 'd':
            t = BootstrapDialog.TYPE_DEFAULT;
            break;
        case 'p':
            t = BootstrapDialog.TYPE_PRIMARY;
            break;
        case 's':
            t = BootstrapDialog.TYPE_SUCCESS;
            break;
        case 'w':
            t = BootstrapDialog.TYPE_WARNING;
            break;
        case 'e':
            t = BootstrapDialog.TYPE_DANGER;
            break;
        default:
            t = BootstrapDialog.TYPE_INFO;
            break;
    }
    BootstrapDialog.show({
        type: t,
        title: title,
        message: message,
        buttons: [{
            label: 'Aceptar',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}
function deleteRow(id, bll,list){
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_WARNING,
        title: 'Eliminar',
        message: 'Realmente deseas eliminarlo?',
        buttons: [{
            label: 'Si',
            action: function(dialogItself){
                $.ajax({
                    url: "../BLL/"+bll+"BLL.php",
                    type: 'POST',
                    dataType: 'json',
                    data: {action: 'delete',id:id}
                })
                    .done(function(data) {
                        console.log('done');
                        //console.log(data);
                        var t = '';
                        var m = '';
                        var type = '';
                        if(typeof data === 'object'){
                            t = 'Error';
                            m = data.message;
                            type = 'e';
                            showMessage(t,m,type);
                        }else{
                            t = 'Eliminado';
                            m = 'El registro ha sido eliminado correctamente';
                            type = 's';
                            showMessage(t,m,type);
                            redirect(list);
                        }
                    })
                    .fail(function(error) {
                        console.log('error');
                        //console.log(error);
                        var t = 'Error';
                        var m = 'Ha ocurrido un error al eliminar registro';
                        var type = 'e';
                        showMessage(t,m,type);
                    })
                    .always(function() {
                        console.log("complete");
                        dialogItself.close();
                    });
            }
        },
            {
                label: 'Cancelar',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }
        ]
    });
}
function validaAcceso(id){
    $.ajax({
        url: "../BLL/PermisosBLL.php",
        type: 'POST',
        dataType: 'json',
        data: {action : 'acceso',id : id}
    })
        .done(function(data) {
            if(!data.valid){
                var t = 'Aviso';
                var m = 'No tienes permisos para acceder a esta pagina';
                var type = 'w';
                redirect("welcome.php");
                showMessage(t,m,type);
            }
        })
        .fail(function(error) {
            redirect("main_menu.php");
        })
        .always(function() {
            console.log("complete");
        });
}
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.hash);
    //console.log(regex);
    //console.log(results);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function generaError(msj) {
    var t = 'Aviso';
    showMessage(t,msj,'e');
}

function addAjax(url) {
    var title = "";

    var dialog = new BootstrapDialog({
        title: title,
        message: $('<div></div>').load(url),
        buttons: [{
            label: 'Cerrar',
            cssClass: 'btn-success',
            action: function(dialogItself){
                dialogItself.close();
                if (typeof afterClose === 'function') { 
                    afterClose();
                }
            }
        },{
            label: 'Cancelar',
            cssClass: 'btn-danger',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
    dialog.realize();
    dialog.getModalHeader().hide();
    dialog.open();
}
        
    
