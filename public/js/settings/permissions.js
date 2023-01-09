$(document).ready(function(){

    var permissions =
    {
        datatype: "json",
        datafields: [
            { name: 'description', type: 'string' },
            { name: 'key', type: 'string' }
        ],
        id: 'id',
        url: base_url + "/settings/permissions/list"
    };

    var dataAdapter = new $.jqx.dataAdapter(permissions, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#permissions").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        source: dataAdapter,                
        pageable: false,
        filterable: true,
        showfilterrow: true,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Permission Description', datafield: 'description', width: '70%', filtercondition: 'contains' },
            { text: 'Permission Name', datafield: 'key', width: '30%', filtercondition: 'contains' }
        ]
    });

    addPermission();
    editPermission();
    deletePermission();
})

function addPermission()
{
    $("#name").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#permission_description").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#addPermissionModal").on('show.bs.modal', function() {
        $("#add-permission-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
        $("#name").val('');
        $("#permission_description").val('');
    });

    $("#save-permission").click(function(){

        var name = $("#name").val();
        var description = $("#permission_description").val();

        var data = {
            name: name,
            description: description,
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/permissions/save",
            beforeSend: function() {
                $("#add-permission-alert").text('Saving permission. Please wait...').addClass('alert-info').removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#add-permission-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#add-permission-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();
                $("#name").val('');
                $("#permission_description").val('');
                $("#permissions").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
                var message = "An error occured while trying to save permission. Please contact IT";
                $("#add-permission-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger');
            }
        });
    });
}

function editPermission()
{
    $("#edit-name").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit_permission_description").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-permission").click(function(){

        var selectedRow = $("#permissions").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a permission whose details you want to update");
            return;
        }

        $("#editPermissionModal").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#editPermissionModal").on('show.bs.modal', function(){
        $("#edit-permission-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        var selectedRow = $("#permissions").jqxGrid('getselectedrowindex');
        var rowdata = $("#permissions").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-name").val(rowdata.key);
        $("#edit_permission_description").val(rowdata.description);
    });

    $("#update-permission").click(function(){

        var id = $("#edit-id").val();
        var name = $("#edit-name").val();
        var description = $("#edit_permission_description").val();

        var data = {
            id: id,
            name: name,
            description: description
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/permissions/" + id + "/update",
            beforeSend: function() {
                $("#edit-permission-alert").text('Updating permission. Please wait...').addClass('alert-info').removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-permission-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }
                $("#edit-permission-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();
                $("#permissions").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
                var message = "An error occured while trying to update permission. Please contact IT";
                $("#edit-permission-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }
        });
    });
}

function deletePermission()
{
    $("#delete-permission").click(function() { 

        var selectedRow = $("#permissions").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a permission to delete");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected permission?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#permissions").jqxGrid('getrowdata', selectedRow);
        var permission = rowdata.uid;

        var data = {
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/permissions/" + permission + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }
                alert(data.message);
                $("#permissions").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
                var message = "An error occured while deleting system permission. Please contact IT";
                alert(message);
            }
        });
    });
}