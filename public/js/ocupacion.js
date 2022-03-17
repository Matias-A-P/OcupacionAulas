// on ready
$(document).ready(function () { 
	// filtro areas
	var fArea = $('#area');
	$.post(fArea.attr('data-json'), function (areas) {
		$.each(areas, function (key, ar) {
			fArea.append('<option value="' + ar.id + '">' + ar.area + '</option>');
		});
		fArea.val(fArea.attr('data-area')).change();
	});
	// filtro palabra 
	$("#inputFiltro").on("keyup click", function() {
    	var value = $(this).val().toLowerCase();
    	$("#tablaOcup tr").filter(function() {
      		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    	});
  	});
    // cambio de dia
    $('#dia').on('change', function() {
        $(this).closest('form').submit();
    });
});


var err = false;
// validar Ocupacion antes de guardar
function onSubmit(event) {
    err = false;
    $('#msj').text('');
    $(".spinner").css("display", "block");
    var id = $('#idocup').val();
    var au = $('#ocupacion_id_aula').val();
    var ar = $('#ocupacion_id_area').val();
    var ac = $('#ocupacion_id_catedra').val();
    var di = $('#ocupacion_fecha').val();
    var hi = $('#ocupacion_hora_inicio').val();
    var hf = $('#ocupacion_hora_fin').val();
    var co = $('#ocupacion_comision').val();
    var d1 = new Date(di + ' ' + hi);
    var d2 = new Date(di + ' ' + hf);
    var m1 = d1.getMinutes();
    var m2 = d2.getMinutes();
    
    if (!(ar>0) || !(ac>0)) {
        $('#msj').text('Ingrese Area y Actividad');
        err = true;       
    }
    if ((d1.getTime() >= d2.getTime()) || ((m1!=0 && m1!=30) || (m2!=0 && m2!=30)) || (co <= 0)) {
        $('#msj').text('Verifique horario y/o comisión');
        err = true;
    };
    if (!err) {
        $.ajax({
            type: "POST",
            url: "/ocupado",
            data: { 'aula': au, 'dia': di, 'hi': hi, 'hf': hf, 'id': id },
            async: false,
            success: function (res) {
                if (res == 'true') {
                    err = true;
                    $('#msj').text('Se superpone el horario');
                };
            } 
        });
    };
    if (err) {
        event.preventDefault();
        $(".spinner").css("display", "none");
    }
};

// nueva Ocupacion
function nueva(btn) {
    var dr = btn.getAttribute("data-ref");
    var aula = btn.getAttribute("data-id-aula");
    var dia = btn.getAttribute("data-dia");
    var hora = btn.getAttribute("data-hora");
    var vista = btn.getAttribute("data-vista");
    err = false;
    $.post(dr, {'aula': aula, 'dia': dia, 'hora': hora, 'vista': vista }, function (result) {
        $('#newOcup').modal('show');
        $("#new-result").html(result);
        $("#formOcup").on('submit', onSubmit);
        $('#ocupacion_id_area').val(0).change();
    });
};

// editar Ocupacion
function editar(btn) {
    var id = btn.getAttribute("data-edit-id");
    //var vista = btn.getAttribute("data-vista");
    err = false;
    $.post("/ocupacion/edit_modal/" + id, function (result) {
        $('#editOcup').modal('show');
        $("#edit-result").html(result);
        $("#formOcup").on('submit', onSubmit);
    });
};
