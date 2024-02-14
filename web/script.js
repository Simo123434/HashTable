$(document).ready(function(){
    $('#hashForm').on('submit', function(e){
        e.preventDefault();
        var hashes = $('#hash').val().split('\n'); // split by newline
        $.ajax({
            url: 'websearch.php',
            type: 'post',
            data: {hashes: hashes},
            success: function(response){
                var results = JSON.parse(response);
                var table = $('<table></table>');
                for (var i = 0; i < results.length; i++) {
                    var row = $('<tr></tr>');
                    var cell = $('<td></td>').text(results[i]);
                    row.append(cell);
                    table.append(row);
                }
                $('#result').empty().append(table); // Clear previous content and append the table
            }
        });
    });
});