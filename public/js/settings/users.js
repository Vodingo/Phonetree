$(document).ready(function(){

    var users =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'username', type: 'string' },
            { name: 'email', type: 'string' },
            { name: 'role_id', type: 'integer', map: 'role>id' },
            { name: 'role', type: 'string', map: 'role>name' },
        ],
        id: 'id',
        url: base_url + "/settings/registered-users"
    };

    var dataAdapter = new $.jqx.dataAdapter(users, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#users").jqxGrid({
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
            { text: 'Fullname', datafield: 'name', filtercondition: 'contains' },
            { text: 'Username', datafield: 'username',  filtercondition: 'contains' },
            { text: 'Email Address', datafield: 'email',  filtercondition: 'contains' },
            { text: 'Role', datafield: 'role_id', displayfield: 'role',  filtercondition: 'contains' },
        ]
    });

    var uregisteredUsers =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/unregistered-users"
    };

    var uregisteredUsersDataAdapter = new $.jqx.dataAdapter(uregisteredUsers, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#user").jqxComboBox({ 
        source: uregisteredUsersDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#username").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
        // disabled: true
    })

    $("#email").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
        // disabled: true
    })

    $("#password").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    var roles =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/roles/list"
    };

    var rolesDataAdapter = new $.jqx.dataAdapter(roles, {
        loadComplete: function(data) {
            $("#edit-role").jqxComboBox({ source: rolesDataAdapter.records });
        }
    });

    $("#role").jqxComboBox({ 
        source: rolesDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });


    $("#addUserModal").on('show.bs.modal', function(){
        $("#add-user-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
        $('#add-user-form').trigger("reset");
    })

    $("#save-user").click(function(){
        
        $("#add-user-alert").text('Saving user data. Please wait...').addClass('alert-info')
            .removeClass('alert-success alert-danger').show();

        var user = $("#user").jqxComboBox('getSelectedItem');

        var id = user.value;
        var name = user.label;
        var username = $("#username").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var password_confirmation = $("#password-confirm").val();
        var role = $("#role").val();

        var data = {
            id: id,
            name: name,
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            role: role
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/users/register",
            success: function(data) {
                
                if (data.status == false) {
                    $("#add-user-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger');
                    return;
                }

                $("#add-user-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info');
                $('#add-user-form').trigger("reset");

                $("#users").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while trying to save the user. Please contact IT";

                $("#add-user-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger');
            }

        })
    })

    $("#deactivate-user").click(function() { 

        var selectedRow = $("#users").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a user to delete");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected user?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#users").jqxGrid('getrowdata', selectedRow);

        var data = {
            user: rowdata.uid
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/users/deactivate",
            success: function(data) {
                
                if (data.status == false) {
                    $("#add-user-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger');
                    return;
                }

                $("#add-user-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info');
                $('#add-user-form').trigger("reset");

                $("#users").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while saving user data. Please contact IT";

                $("#add-user-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger');
            }

        })
    })


    //Edit form

    $("#edit-name").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    $("#edit-username").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    $("#edit-email").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
    })

    $("#update-password").jqxCheckBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-password").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
        disabled: true
    })

    $("#edit-password-confirm").jqxInput({
        theme: theme,
        width: '100%', 
        height: 30,
        disabled: true
    })

    $("#edit-role").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $('#update-password').on('change', function (event) { 
        var checked = event.args.checked;
        
        if (checked) {
            $("#edit-password").jqxInput({ disabled: false });
            $("#edit-password-confirm").jqxInput({ disabled: false });
        } else {
            $("#edit-password").jqxInput({ disabled: true });
            $("#edit-password-confirm").jqxInput({ disabled: true });
        }
    }); 


    $("#edit-user").click(function(){
        var selectedRow = $("#users").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a user whose details you want to update");
            return;
        }

        var rowdata = $("#users").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-name").val(rowdata.name);
        $("#edit-username").val(rowdata.username);
        $("#edit-email").val(rowdata.email);
        $("#edit-role").val(rowdata.role_id)

        $("#editUserModal").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#editUserModal").on('show.bs.modal', function(){
        $("#edit-user-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
    })

    $("#editUserModal").on('hidden.bs.modal', function(){
        $("#edit-user-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
        $('#edit-user-form').trigger("reset");
    })

    $("#update-user").click(function(){
        
        $("#edit-user-alert").text('Saving user data. Please wait...').addClass('alert-info')
            .removeClass('alert-success alert-danger').show();

        var id = $("#edit-id").val();
        var name = $("#edit-name").val();
        var username = $("#edit-username").val();
        var email = $("#edit-email").val();
        var password = $("#edit-password").val();
        var password_confirmation = $("#edit-password-confirm").val();
        var updatepassword = $("#update-password").val();
        var role = $("#edit-role").val();

        var data = {
            id: id,
            name: name,
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            updatepassword: updatepassword,
            role: role
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/users/update",
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-user-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger');
                    return;
                }

                $("#edit-user-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info');

                $("#users").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while trying to update user data. Please contact IT";

                $("#edit-user-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger');
            }

        })
    })

})