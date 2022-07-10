$(document).ready(function(){
    $('#fileBtn').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $('#fileInput').trigger('click');
    })
    $('#fileInput').on('change', function(e) {
        var file = e.target.files[0].name;
        $('#fileInputLabel').text(file)
        $('#uploadBtn').removeClass('hidden');
    })
    $('.taskStatus').on('change', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        checkbox = this;
        var url = 'task/statusChange';
        $row = $(this).closest('tr');
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                id: $(this).attr('data-id'),
                status: this.checked ? 1 : 0,
                _token: $('#token').attr('data-token'),
            },
            success: function(response){
                jsonResponse = JSON.parse(response);
                if(jsonResponse.error){ 
                    alert(jsonResponse.message);
                    $(checkbox).prop('checked', false);
                    return;
                }
                checkbox.checked ? $row.addClass('strikeout') : $row.removeClass('strikeout');
            }
        })
    })
});