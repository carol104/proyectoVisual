<?php include 'Controllers/middleware.php'?>
<h2>Manejo de Estudiantes</h2>
<table id="dg" title="Estudiantes" class="easyui-datagrid" style="width:700px;height:250px;" url="Controllers/estudianteController.php?action=acceder"
  toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
  <thead>
    <tr>
      <th field="estCedula" width="50">Cédula</th>
      <th field="estNombre" width="50">Nombre</th>
      <th field="estApellido" width="50">Apellido</th>
      <th field="estTelefono" width="50">Teléfono</th>
      <th field="estDireccion" width="50">Dirección</th>
    </tr>
  </thead>
</table>
<div id="toolbar">
  <a href="javascript:void(0)" class="easyui-linkbutton <?php echo isset($_SESSION['usuario']) ? '' : 'disabled'; ?>" iconCls="icon-add" plain="true" onclick="nuevoEstudiante()">Nuevo Estudiante</a>
  <a href="javascript:void(0)" class="easyui-linkbutton <?php echo isset($_SESSION['usuario']) ? '' : 'disabled'; ?>" iconCls="icon-edit" plain="true" onclick="editar()">Editar Estudiante</a>
  <a href="javascript:void(0)" class="easyui-linkbutton <?php echo isset($_SESSION['usuario']) ? '' : 'disabled'; ?>" iconCls="icon-remove" plain="true" onclick="eliminar()">Eliminar Estudiante</a>
  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="reporte1()">Reporte 1</a>
  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="reporte2()">Reporte 2</a>
  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="reporteCedula()">Reporte Cédula</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:400px"
  data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
  <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
    <h3>Datos Estudiante</h3>
    <div style="margin-bottom:10px">
      <input id="estCedula" name="estCedula" class="easyui-textbox" required="true" label="Cédula:" style="width:100%"
        data-options="validType:['cedulaEcuatoriana'], prompt:'10 dígitos numéricos'">
    </div>
    <div style="margin-bottom:10px">
      <input id="estNombre" name="estNombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%"
        data-options="validType:['soloLetras','length[2,50]'], prompt:'Solo letras'">
    </div>
    <div style="margin-bottom:10px">
      <input id="estApellido" name="estApellido" class="easyui-textbox" required="true" label="Apellido:" style="width:100%"
        data-options="validType:['soloLetras','length[2,50]'], prompt:'Solo letras'">
    </div>
    <div style="margin-bottom:10px">
      <input id="estTelefono" name="estTelefono" class="easyui-textbox" required="true" label="Teléfono:" style="width:100%"
        data-options="validType:['telefono'], prompt:'10 dígitos (ej: 0991234567)'">
    </div>
    <div style="margin-bottom:10px">
      <input id="estDireccion" name="estDireccion" class="easyui-textbox" required="true" label="Dirección:" style="width:100%"
        data-options="validType:['length[3,100]'], prompt:'Dirección domiciliaria'">
    </div>
  </form>
</div>
<div id="dlg-buttons">
  <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="guardar()"
    style="width:90px">Guardar</a>
  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel"
    onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
</div>

<!-- Dialogo de restricción -->
<div id="dlg-restriccion" class="easyui-dialog" style="width:300px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-restriccion'">
  <p style="padding:10px;text-align:center;">Usted no ha iniciado sesión, no puede realizar cambios en los registros.</p>
</div>

<!-- Botón de la restricción -->
<div id="dlg-buttons-restriccion">
  <a href="login.php" class="easyui-linkbutton c6" style="width:100px">Iniciar Sesión</a>
</div>

<!-- Diálogo para el reporte por cédula -->
<div id="dlg-cedula" class="easyui-dialog" style="width:320px"
     data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-cedula'">
    <div style="padding:15px">
      <p>Ingrese la cédula del estudiante:</p>
      <input id="cedula" class="easyui-textbox" style="width:100%" required="true"
        data-options="validType:['cedulaEcuatoriana'], prompt:'10 dígitos numéricos'">
    </div>
</div>

<!-- Botón para enviar la cédula -->
<div id="dlg-buttons-cedula">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="width:100px" onclick="generarReporteCedula()">Aceptar</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" style="width:100px"
       onclick="javascript:$('#dlg-cedula').dialog('close')">Cancelar</a>
</div>

<script type="text/javascript">
var url;
var isLoggedIn = false;

// Reglas de validación personalizadas para EasyUI
$.extend($.fn.validatebox.defaults.rules, {
    cedulaEcuatoriana: {
        validator: function(value) {
            value = $.trim(value);
            if (!/^\d{10}$/.test(value)) {
                return false;
            }
            var prov = parseInt(value.substring(0, 2), 10);
            if ((prov < 1 || prov > 24) && prov !== 30) {
                return false;
            }
            var tercer = parseInt(value.charAt(2), 10);
            if (tercer >= 6) {
                return false;
            }
            var coef = [2, 1, 2, 1, 2, 1, 2, 1, 2];
            var suma = 0;
            for (var i = 0; i < 9; i++) {
                var mul = parseInt(value.charAt(i), 10) * coef[i];
                if (mul >= 10) mul -= 9;
                suma += mul;
            }
            var digitoVerificador = (10 - (suma % 10)) % 10;
            return digitoVerificador === parseInt(value.charAt(9), 10);
        },
        message: 'Ingrese una cédula ecuatoriana válida de 10 dígitos.'
    },
    soloLetras: {
        validator: function(value) {
            return /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test($.trim(value));
        },
        message: 'Solo se permiten letras y espacios.'
    },
    telefono: {
        validator: function(value) {
            return /^\d{10}$/.test($.trim(value));
        },
        message: 'El teléfono debe contener exactamente 10 dígitos numéricos.'
    }
});

$(document).ready(function() {
    // Verificar sesión al cargar la página
    $.getJSON('Controllers/statusSesion.php', function(response) {
        isLoggedIn = response.sesionValida;
        if (!isLoggedIn) {
            $('.easyui-linkbutton').each(function() {
                var onclickAttr = $(this).attr('onclick');
                if (onclickAttr && (onclickAttr.indexOf('nuevoEstudiante') !== -1 || onclickAttr.indexOf('editar') !== -1 || onclickAttr.indexOf('eliminar') !== -1)) {
                    $(this).linkbutton('disable');
                }
            });
        }
    });
});

function nuevoEstudiante() {
  if (!isLoggedIn) {
    $('#dlg-restriccion').dialog('open').dialog('center').dialog('setTitle', 'Acceso Restringido');
    return;
  }
  $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Estudiante');
  $('#fm').form('clear');
  $('#estCedula').textbox('readonly', false);
  url = 'Controllers/estudianteController.php?action=guardar';
}

function editar() {
  if (!isLoggedIn) {
    $('#dlg-restriccion').dialog('open').dialog('center').dialog('setTitle', 'Acceso Restringido');
    return;
  }
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Estudiante');
    $('#fm').form('load', row);
    $('#estCedula').textbox('readonly', true); // La cédula (clave primaria) no se puede modificar
    url = 'Controllers/estudianteController.php?action=editar&estCedula=' + encodeURIComponent(row.estCedula);
  } else {
    $.messager.alert('Atención', 'Seleccione un estudiante de la tabla para editar.', 'warning');
  }
}

function guardar() {
  $('#fm').form('submit', {
    url: url,
    iframe: false,
    onSubmit: function() {
      return $(this).form('validate');
    },
    success: function(result) {
      try {
        var data = (typeof result === 'object') ? result : JSON.parse(result);
        if (data.errorMsg) {
          $.messager.show({
            title: 'Error de Validación',
            msg: data.errorMsg
          });
        } else {
          $('#dlg').dialog('close');
          $('#dg').datagrid('reload');
          $.messager.show({
            title: 'Éxito',
            msg: data.message || 'Operación realizada con éxito.'
          });
        }
      } catch (e) {
        $.messager.alert('Error', 'Respuesta inesperada del servidor: ' + result, 'error');
      }
    }
  });
}

function eliminar() {
  if (!isLoggedIn) {
    $('#dlg-restriccion').dialog('open').dialog('center').dialog('setTitle', 'Acceso Restringido');
    return;
  }
  var row = $('#dg').datagrid('getSelected');
  if (row) {
    $.messager.confirm('Confirmar', '¿Desea eliminar al estudiante seleccionado (Cédula: ' + row.estCedula + ')?', function(r) {
      if (r) {
        $.post('Controllers/estudianteController.php?action=borrar', {
          cedula: row.estCedula
        }, function(result) {
          if (result.success) {
            $('#dg').datagrid('reload');
            $.messager.show({
              title: 'Éxito',
              msg: result.message || 'Estudiante eliminado con éxito.'
            });
          } else {
            $.messager.alert('Error', result.errorMsg || 'Error al eliminar el estudiante.', 'error');
          }
        }, 'json').fail(function() {
          $.messager.alert('Error', 'Error de comunicación con el servidor.', 'error');
        });
      }
    });
  } else {
    $.messager.alert('Atención', 'Seleccione un estudiante de la tabla para eliminar.', 'warning');
  }
}

function reporte1() {
    window.location.href = 'reporte.php';
}

function reporte2() {
    window.location.href = 'ireporte.php';
}

function reporteCedula() {
    $('#cedula').textbox('clear');
    $('#dlg-cedula').dialog('open').dialog('center').dialog('setTitle', 'Reporte por Cédula');
}

function generarReporteCedula() {
    if (!$('#cedula').textbox('isValid')) {
        $.messager.alert('Validación', 'Por favor ingrese una cédula ecuatoriana válida de 10 dígitos.', 'warning');
        return;
    }
    var cedula = $.trim($('#cedula').textbox('getValue'));
    if (!cedula) {
        $.messager.alert('Error', 'Debe ingresar una cédula válida.', 'error');
        return;
    }

    var form = $('<form>', {
        method: 'POST',
        action: 'reporteIndividual.php'
    });
    $('<input>', {
        type: 'hidden',
        name: 'cedula',
        value: cedula
    }).appendTo(form);
    form.appendTo('body').submit();
    $('#dlg-cedula').dialog('close');
}
</script>
