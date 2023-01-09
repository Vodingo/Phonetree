$(document).ready(function(){

    $("#update-password").jqxCheckBox({ 
        theme: theme,
        width: '100%', 
        height: 30
    });

    $('#update-password').on('change', function (event) { 
        var checked = event.args.checked;
        
        if (checked) {
            $("#new-password").attr('disabled', false );
            $("#confirm-new-password").attr('disabled', false );
            $("#update-password-value").val(true);
        } else {
            $("#new-password").attr('disabled', true);
            $("#confirm-new-password").attr('disabled', true);
            $("#update-password-value").val(false);
        }
    }); 
})