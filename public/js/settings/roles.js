$(document).ready(function(){

    $("#rolesSplitter").jqxSplitter({ theme: theme, width: '100%', panels: [{ size: '50%' }, { size: '50%'}] });

    var roles =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'method', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/roles/list"
    };

    var dataAdapter = new $.jqx.dataAdapter(roles, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#roles").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        source: dataAdapter,                
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Name', datafield: 'name'},
        ]
    });

    addRole();
    editRole();
    deleteRole();
    rolePermissions();
    assignRolePermissions();
})

function addRole()
{
    $("#name").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    $("#addRoleModal").on('show.bs.modal', function(){
        $("#add-role-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        $("#name").val('');
    })

    $("#save-role").click(function(){

        var name = $("#name").val();

        var data = {
            name: name
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/roles/save",
            beforeSend: function() {
                $("#add-role-alert").text('Saving role. Please wait...').addClass('alert-info').removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#add-role-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#add-role-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#name").val('');

                $("#roles").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while trying to save the role. Please contact IT";

                $("#add-role-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger');
            }

        })
    })

}

function editRole()
{
    $("#edit-name").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    $("#edit-role").click(function(){

        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a role whose details you want to update");
            return;
        }

        $("#editRoleModal").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#editRoleModal").on('show.bs.modal', function(){
        $("#edit-role-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');
        var rowdata = $("#roles").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-name").val(rowdata.name);
    })

    $("#update-role").click(function(){

        var id = $("#edit-id").val();
        var name = $("#edit-name").val();

        var data = {
            id: id,
            name: name,
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/roles/" + id + "/update",
            beforeSend: function() {
                $("#edit-role-alert").text('Updating role. Please wait...').addClass('alert-info').removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-role-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#edit-role-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#roles").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while trying to update the role. Please contact IT";

                $("#edit-role-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }

        })
    })

}

function deleteRole()
{
    $("#delete-role").click(function() { 

        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a role to delete");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected role?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#roles").jqxGrid('getrowdata', selectedRow);
        var role = rowdata.uid;

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
            url: base_url + "/settings/roles/" + role + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#roles").jqxGrid('updatebounddata');
                $("#role-permissions").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while deleting system role. Please contact IT";

                alert(message);
            }

        })
    })

}

function rolePermissions()
{
    $("#role-permissions").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: true,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Permission Name', datafield: 'description' },
        ]
    });

    $("#roles").on('rowselect', function(event){

        var args = event.args;

        if (args) {

            var id = args.row.uid;

            var permissions =
            {
                datatype: "json",
                datafields: [
                    { name: 'description', type: 'string' },
                    { name: 'controller', type: 'string'}
                ],
                id: 'id',
                url: base_url + "/settings/roles/" + id + "/permissions"
            };

            var dataAdapter = new $.jqx.dataAdapter(permissions, {
                downloadComplete: function (data, status, xhr) { },
                loadComplete: function (data) { },
                loadError: function (xhr, status, error) { }
            });

            $("#role-permissions").jqxGrid({ source: dataAdapter });
        }
    })
}

function assignRolePermissions()
{

    $("#assign-permissions-grid").jqxGrid({
        width: '100%',
        height: '400',
        theme: theme,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: true,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Permission Name', datafield: 'description', editable: false },
            { text: 'Assigned', datafield: 'assigned', columntype: 'checkbox', align: 'center' },
        ]
    });

    $("#filter-assign-permissions").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        placeHolder: "Enter permission name"
    });

    $("#filter-assign-permissions").on('change', function(){

        var value = $('#filter-assign-permissions').val(); 

        if (value.length == 0) {
            $("#assign-permissions-grid").jqxGrid('clearfilters');
        } else if (value.length >= 3) {
            
            var filtergroup = new $.jqx.filter();
            var filtercondition = 'contains';
            var filter1 = filtergroup.createfilter('stringfilter', value, filtercondition);
           
            filtergroup.addfilter(1, filter1);

            $("#assign-permissions-grid").jqxGrid('addfilter', 'name', filtergroup);
            $("#assign-permissions-grid").jqxGrid('applyfilters');
        }
    })

    $("#assign-permissions").click(function(){

        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a role to assign permissions");
            return;
        }

        $("#assignPermissionsModal").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#assignPermissionsModal").on('show.bs.modal', function(){
        $("#assign-permission-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        $("#assign-permissions-grid").jqxGrid('clearfilters');
        
        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');
        var rowdata = $("#roles").jqxGrid('getrowdata', selectedRow);

        $("#permissions-title").text(rowdata.name);

        var id = rowdata.uid;

        var permissions =
        {
            datatype: "json",
            datafields: [
                { name: 'description', type: 'string' },
                { name: 'assigned', type: 'bool'}
            ],
            id: 'id',
            url: base_url + "/settings/roles/" + id + "/permissions/edit"
        };

        var dataAdapter = new $.jqx.dataAdapter(permissions);

        $("#assign-permissions-grid").jqxGrid({ source: dataAdapter });

    })

    $("#assignPermissionsModal").on('hide.bs.modal', function(){ 
        $("#assign-permissions-grid").jqxGrid('clear');
    })

    $('#update-permissions').on('click', function(){

        $("#assign-permission-alert").text('Updating role permissions. Please wait..')
            .addClass('alert-success').removeClass('alert-info alert-danger').show();


        var selectedRow = $("#roles").jqxGrid('getselectedrowindex');
        var rowdata = $("#roles").jqxGrid('getrowdata', selectedRow);
        var role = rowdata.uid;

        var rows = $("#assign-permissions-grid").jqxGrid('getboundrows');

        var assignedPermissions = [];

        for (i in rows) {
            if (rows[i].assigned == true || rows[i].assigned == 1) {
                assignedPermissions.push(rows[i].uid);
            }
        }

        var data = {
           assignedPermissions: assignedPermissions
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/roles/" + role + "/permissions/save",
            success: function(data) {
                
                if (data.status == false) {
                    $("#assign-permission-alert").text(data.message).addClass('alert-danger').removeClass('alert-info alert-success').show();
                    return;
                }

                $("#assign-permission-alert").text(data.message).addClass('alert-success').removeClass('alert-info alert-danger').show();

                $("#role-permissions").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while deleting system role. Please contact IT";
                
                $("#assign-permission-alert").text(message).addClass('alert-danger').removeClass('alert-info alert-success').show();
            }

        });
    });
}