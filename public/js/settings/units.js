$(document).ready(function(){

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/units-list"
    };

    var dataAdapter = new $.jqx.dataAdapter(departments, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#units").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        source: dataAdapter,                
        pageable: false,
        sortable: true,
        altrows: true,
        showfilterrow: true,
        filterable: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Name', datafield: 'name',filtertype: 'input', filtercondition: 'contains'  },
        ]
    });

    createUnit();
    editUnit();
    deleteUnit();
})

function createUnit()
{
    $("#create-unit").click(function(){
        $("#createUnit").modal({ backdrop: 'static', show: true });
    })

    $("#createUnit").on('show.bs.modal', function(){
        $("#create-unit-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
        $("#name").val('');
    })

    $("#name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#save-unit").click(function(){

        var name = $("#name").val();

        var data = {
            name: name
        };

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/units",
            beforeSend: function() {
                $("#create-unit-alert").text('Saving unit info. Please wait...').addClass('alert-info')
                    .removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-unit-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                }

                $("#create-unit-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#name").val('');

                $("#units").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = 'An error occured while saving the unit. Please contact IT';

                $("#create-unit-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }
        })
    })
}

function editUnit()
{
    $("#edit-unit").click(function(){

        var selectedRow = $("#units").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a unit whose details you want to update");
            return;
        }

        $("#editUnit").modal({ backdrop: 'static', show: true });
    })

    $("#editUnit").on('show.bs.modal', function(){
        $("#edit-unit-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();

        var selectedRow = $("#units").jqxGrid('getselectedrowindex');
        var rowdata = $("#units").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-name").val(rowdata.name);
    })

    $("#edit-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#update-unit").click(function(){

        var uid = $("#edit-id").val();
        var name = $("#edit-name").val();

        var data = {
            name: name
        };

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/units/" + uid + "/update",
            beforeSend: function() {
                $("#edit-unit-alert").text('Saving unit info. Please wait...').addClass('alert-info')
                    .removeClass('alert-success alert-danger').show();
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-unit-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                }

                $("#edit-unit-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#units").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = 'An error occured while saving the unit. Please contact IT';

                $("#edit-unit-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }

        })
    })
}

function deleteUnit()
{
    $("#delete-unit").click(function() { 

        var selectedRow = $("#units").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the unit to remove from the system");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected unit?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#units").jqxGrid('getrowdata', selectedRow);
        var uid = rowdata.uid;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: null,
            method: "POST",
            url: base_url + "/settings/units/" + uid + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }
                alert(data.message);
                $("#units").jqxGrid('updatebounddata');
            },
            error: function(data) {
                console.log(data);
                var message = "An error occured while saving department data. Please contact IT";
                alert(message);
            }
        });
    });
}