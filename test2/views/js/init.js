/**
 * Created by Francisco Torres on 21/01/2018.
 */

var url = "index.php"; // the script where you handle the form input.

$(function() {
    showTxt();
    showPdf();

    $(document).on("submit","form", function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $.ajax({
            type: "POST",
            url: url,
            data: $("#download_borme").serialize(), // serializes the form's elements.
            dataType: "json",
            success: function(data)
            {
                if(data.success) {
                     $('#notifications').append("<div class='success'>Éxito: Fichero incluido: "+data.success+"</div>");
                     showTxt();
                     showPdf();
                }
                else{
                    $('#notifications').append("<div class='error'>Error: "+data.error+"</div>");
                    showPdf();
                }
            }
        });
    });

    function showTxt(){
        $.ajax({
            type: "POST",
            url: url,
            data: 'action=ShowTxt',
            dataType: "json",
            success: function(data)
            {
                console.log(data.files);

                $('#txt-results').html("");
                $.each(data, function(index, itemData) {
                    $('#txt-results').append("<div class='file'><a href='files/txt/" + itemData + "' target='_blank'>" + itemData + "</a></div>");
                });
            }
        });
    }
    function showPdf(){
        $.ajax({
            type: "POST",
            url: url,
            data: 'action=ShowPdf',
            dataType: "json",
            success: function(data)
            {
                console.log(data.files);
                $('#pdf-results').html("");
                $.each(data, function(index, itemData) {
                    $('#pdf-results').append("<div class='file'><a href='files/pdf/" + itemData + "' target='_blank'>" + itemData + "</a></div>");
                });
            }
        });
    }

});