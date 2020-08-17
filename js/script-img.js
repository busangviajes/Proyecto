$(function() {
    $('#img').on('change', function() {
        $('#archivoSubido').text($('#img')[0].files[0].name)
        // Si ya leyo un archivo, borra el mensaje de advertencia
        document.getElementById('mensaje').style.display = 'none';
    })
})