    function agregarGasto(){
        modalGastoForm();
        $('#gastoId').val(0);
        $('#buttonSubmit').val('Enviar');
    }
    function modalGastoForm(){
        $('#modalGasto').addClass('modal--show');
    }
    function buscarGasto( input ){
        modalGastoForm();
        $('#idproyecto').val( input.getAttribute('data-proyecto') );
        $('#idcategoria').val( input.getAttribute('data-categoria') );
        $('#iditem').val( input.getAttribute('data-item') );
        $('#fecha').val( input.getAttribute('data-fecha') );
        $('#montogasto').val( input.getAttribute('data-monto') );
        $('#comprobante').val( input.getAttribute('data-comprobante') );
        $('#observacion').val( input.getAttribute('data-observacion') );
        $('#buttonSubmit').val('Guardar Cambios');
    }

    function cerrarModal(){
        $('#modalGasto').removeClass('modal--show');
    }

    function allowOnlyFloat(evt) {
        // Permitir: Backspace, Delete, Tab, Escape, Enter y .
        if ([46, 8, 9, 27, 13, 110, 190].indexOf(evt.keyCode) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (evt.keyCode === 65 && (evt.ctrlKey === true || evt.metaKey === true)) ||
            (evt.keyCode === 67 && (evt.ctrlKey === true || evt.metaKey === true)) ||
            (evt.keyCode === 86 && (evt.ctrlKey === true || evt.metaKey === true)) ||
            (evt.keyCode === 88 && (evt.ctrlKey === true || evt.metaKey === true)) ||
            // Permitir: teclas de inicio y fin
            (evt.keyCode >= 35 && evt.keyCode <= 39)) {
            // Dejar funcionar el evento
            return;
        }
        // Asegurarse de que es un número
        if ((evt.shiftKey || (evt.keyCode < 48 || evt.keyCode > 57)) && (evt.keyCode < 96 || evt.keyCode > 105)) {
            evt.preventDefault();
        }
    }

    function validateFloatInput(input) {
        const value = input.value;
        const regex = /^[+-]?\d+(\.\d+)?$/;
        if (!regex.test(value)) {
            input.setCustomValidity("Por favor, ingrese un número decimal válido.");
        } else {
            input.setCustomValidity("");
        }
    }