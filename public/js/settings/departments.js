$(document).ready(function(){

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/departments-list"
    };

    var dataAdapter = new $.jqx.dataAdapter(departments);

    $("#departments").jqxGrid({
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
            { text: 'Department Name', datafield: 'name' },
        ]
    });

   createDepartment();
   editDepartment();
   deleteDepartment();
   filterDepartments();

})

function createDepartment()
{
    $("#create-department").click(function(){
        $("#createDepartment").modal({ backdrop: 'static', show: true });
    })

    $("#createDepartment").on('show.bs.modal', function(){
        $("#create-department-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
        $("#name").val('');
    })

    $("#name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $("#save-department").click(function(){

        var name = $("#name").val();
        var data = { name: name };

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/departments",
            beforeSend: function() {
                $("#create-department-alert").text('Saving department info. Please wait...').addClass('alert-info')
                    .removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-department-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                }

                $("#create-department-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();
                $("#name").val('');
                $("#departments").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
            }
        })
    })
}

function editDepartment()
{
    $("#edit-department").click(function(){

        var selectedRow = $("#departments").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a department whose details you want to update");
            return;
        }

        $("#editDepartment").modal({ backdrop: 'static', show: true });
    })

    $("#editDepartment").on('show.bs.modal', function(){
        $("#edit-department-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        var selectedRow = $("#departments").jqxGrid('getselectedrowindex');
        var rowdata = $("#departments").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-name").val(rowdata.name);
    })

    $("#edit-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#update-department").click(function(){

        var uid = $("#edit-id").val();
        var name = $("#edit-name").val();

        var data = {
            name: name
        };

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/departments/" + uid + "/update",
            beforeSend: function() {
                $("#edit-department-alert").text('Saving department info. Please wait...').addClass('alert-info')
                    .removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-department-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                }

                $("#edit-department-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();
                $("#departments").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = 'An error occured while saving the department. Please contact IT';

                $("#edit-department-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }

        })
    })
}

function deleteDepartment()
{
    $("#delete-department").click(function() { 

        var selectedRow = $("#departments").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the department to remove from the system");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected department?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#departments").jqxGrid('getrowdata', selectedRow);
        var uid = rowdata.uid;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: null,
            method: "POST",
            url: base_url + "/settings/departments/" + uid + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }
                alert(data.message);
                $("#departments").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
                var message = "An error occured while saving department data. Please contact IT";
                alert(message);
            }

        })
    })
}