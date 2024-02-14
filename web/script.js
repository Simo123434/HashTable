$(document).ready(function(){
    $('#hashForm').on('submit', function(e){
        e.preventDefault();
        var hash = $('#hash').val();
        $.ajax({
            url: 'websearch.php',
            type: 'post',
            data: {hash: hash},
            success: function(response){
                $('#result').html(response);
            }
        });
    });
});