$(document).ready(function(){

    //$("#supervisorsSplitter").jqxSplitter({ theme: theme, width: '100%', height: '100%', panels: [{ size: '50%' }, { size: '50%'}] });

    var supervisors =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'department', type: 'string' },
            { name: 'unit', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/supervisors-list"
    };

    var dataAdapter = new $.jqx.dataAdapter(supervisors, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#supervisors").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        source: dataAdapter,                
        pageable: false,
        sortable: true,
        showfilterrow: true,
        filterable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'selectrow',
        columns: [
            { text: 'Supervisor', datafield: 'name', filtertype: 'input', filtercondition: 'contains' },
            { text: 'Department', datafield: 'department', filtertype: 'input', filtercondition: 'contains' },
            { text: 'Unit', datafield: 'unit', filtertype: 'input', filtercondition: 'contains' },
        ]
    });

   /* $("#supervisors-staff").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        showfilterrow: true,
        filterable: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'selectrow',
        columns: [
            { text: 'Staff Name', datafield: 'name', filtertype: 'input', filtercondition: 'contains' },
            { text: 'Department', datafield: 'department', filtertype: 'input', filtercondition: 'contains' },
            { text: 'Unit', datafield: 'unit', filtertype: 'input', filtercondition: 'contains' },
        ]
    });*/

    /*  $("#supervisors").on('rowclick', function(event){

        var args = event.args;

      if (args) {

            $("#supervisors-staff").jqxGrid('clear')

            var id = args.row.bounddata.uid;

            var staff =
            {
                datatype: "json",
                datafields: [
                    { name: 'name', type: 'string' },
                    { name: 'department', type: 'string' },
                    { name: 'unit', type: 'string' },
                ],
                id: 'id',
                url: base_url + "/settings/supervisors-staff/" + id
            };

            var dataAdapter = new $.jqx.dataAdapter(staff, {
                downloadComplete: function (data, status, xhr) { },
                loadComplete: function (data) { },
                loadError: function (xhr, status, error) { }
            });

            $("#supervisors-staff").jqxGrid({ source: dataAdapter });
        }
    })*/

    addSupervisor();
   // addSupervisorStaff();
   // deleteSupervisorStaff();
    deleteSupervisor();
})

function addSupervisor()
{
    var staff =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'full_name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/supervisors/supervised-staff"
    };

    var staffDataAdapter = new $.jqx.dataAdapter(staff);

    $("#supervisor").jqxComboBox({ 
        source: staffDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'full_name',
        valueMember: 'id'
    });

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/supervisors/department-select-list"
    };

    var departmentsDataAdapter = new $.jqx.dataAdapter(departments);

    $("#department").jqxComboBox({ 
        source: departmentsDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });


    var units =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/supervisors/unit-select-list"
    };

    var unitsDataAdapter = new $.jqx.dataAdapter(units);

    $("#unit").jqxComboBox({ 
        source: unitsDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#create-supervisor").on('click', function(){
        $("#create-supervisor-response").text('');
        $("#create-supervisor-alert").addClass('alert-danger d-none').removeClass('alert-success alert-info');

        $("#supervisor").jqxComboBox('clearSelection');
        $("#department").jqxComboBox('clearSelection');
        $("#unit").jqxComboBox('clearSelection');

        $("#addSupervisor").modal({ backdrop: 'static', show: true });
    })

    $("#save-supervisor").click(function(){

        var supervisor = $("#supervisor").val();
        var department = $("#department").val();
        var unit = $("#unit").val();
       
        var data = {
            supervisor: supervisor,
            department: department,
            unit: unit
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/supervisors/save",
            beforeSend: function(){
                $("#create-supervisor-response").text('Saving supervisor details. Please wait...');
                $("#create-supervisor-alert").addClass('alert-info').removeClass('alert-danger alert-success d-none');
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-supervisor-response").text(data.message);
                    $("#create-supervisor-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
                    return;
                }

                $("#create-supervisor-response").text(data.message);
                $("#create-supervisor-alert").addClass('alert-success').removeClass('alert-danger alert-info d-none');

                $("#department").jqxComboBox('clearSelection');
                $("#unit").jqxComboBox('clearSelection');
                $("#supervisor").jqxComboBox('clearSelection');

                $("#supervisors").jqxGrid('clearselection');
                $("#supervisors").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the supervisor. Please contact IT';

                $("#create-supervisor-response").text(message);
                $("#create-supervisor-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
            }
        })
    })
}

/*
function addSupervisorStaff()
{
    var staff =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'full_name', type: 'string' },
            { name: 'department', map: 'department>name' },
            { name: 'unit', map: 'unit>name' },
        ],
        id: 'id',
        url: base_url + "/settings/supervisors/supervised-staff"
    };

    var staffDataAdapter = new $.jqx.dataAdapter(staff);

    $("#staff-list").jqxGrid({
        width: '100%',
        height: '350',
        source: staffDataAdapter,
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'checkbox',
        rendered: function(){
            $("#staff-list").jqxGrid('clearselection');
        },
        columns: [
            { text: 'Staff Name', datafield: 'full_name' },
            { text: 'Department', datafield: 'department' },
            { text: 'Unit', datafield: 'unit' },
        ]
    });

    $("#filter-staff-names").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        placeHolder: "Enter staff name"
    });

    $("#filter-staff-names").on('change', function(){

        var value = $('#filter-staff-names').val(); 

        if (value.length == 0) {
            $("#staff-list").jqxGrid('clearfilters');
        } else if (value.length > 3) {
            
            var filtergroup = new $.jqx.filter();
            var filtercondition = 'contains';
            var filter1 = filtergroup.createfilter('stringfilter', value, filtercondition);
           
            filtergroup.addfilter(1, filter1);

            $("#staff-list").jqxGrid('addfilter', 'full_name', filtergroup);
            $("#staff-list").jqxGrid('applyfilters');
        }
    })

    $("#add-supervisor-staff").click(function() { 
        
        var selectedRow = $("#supervisors").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the supervisor to continue");
            return;
        }

        $("#addSupervisorStaff").modal({ backdrop: 'static', show: true });
    });

    $("#addSupervisorStaff").on('show.bs.modal', function() {
        $("#create-supervisor-staff-response").text('');
        $("#create-supervisor-staff-alert").addClass('alert-danger d-none').removeClass('alert-success alert-info');

        $('#staff-list').jqxGrid('render');
    })

    $("#save-supervisor-staff").click(function(){

        var selectedRow = $("#supervisors").jqxGrid('getselectedrowindex');
        var rowdata = $("#supervisors").jqxGrid('getrowdata', selectedRow);
        var supervisor = rowdata.uid;

        var selectedRows = $("#staff-list").jqxGrid('getselectedrowindexes');
        var staff = [];
     
        for (i in selectedRows) {
            var rowdata = $("#staff-list").jqxGrid('getrowdata', selectedRows[i]);
            staff.push(rowdata.uid)
        }

        var data = {
            supervisor: supervisor,
            staff: staff,
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/supervisors/staff/save",
            beforeSend: function(){
                $("#create-supervisor-staff-response").text('Saving supervisor staff. Please wait...');
                $("#create-supervisor-staff-alert").addClass('alert-info').removeClass('alert-danger alert-success d-none');
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-supervisor-staff-response").text(data.message);
                    $("#create-supervisor-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
                    return;
                }

                $("#create-supervisor-staff-response").text(data.message);
                $("#create-supervisor-staff-alert").addClass('alert-success').removeClass('alert-danger alert-info d-none');

                $("#supervisors").jqxGrid('clearselection');
                $("#supervisors-staff").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the supervisor. Please contact IT';

                $("#create-supervisor-staff-response").text(message);
                $("#create-supervisor-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
            }
        })
    })
}

function deleteSupervisorStaff()
{
    $("#delete-supervisor-staff").click(function() { 

        var selectedRow = $("#supervisors-staff").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the staff to delete");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected staff?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#supervisors-staff").jqxGrid('getrowdata', selectedRow);
        var staff = rowdata.uid;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: null,
            method: "POST",
            url: base_url + "/settings/supervisors/staff/" + staff + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#supervisors-staff").jqxGrid('clearselection');
                $("#supervisors-staff").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while deleting supervisor staff. Please contact IT";

                alert(message);
            }

        })
    })
}
*/
function deleteSupervisor()
{
    $("#delete-supervisor").click(function() { 

        var selectedRow = $("#supervisors").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the supervisor to delete from the system");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected supervisor?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#supervisors").jqxGrid('getrowdata', selectedRow);
        var staff = rowdata.uid;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: null,
            method: "POST",
            url: base_url + "/settings/supervisors/" + staff + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#supervisors").jqxGrid('clearselection');
                $("#supervisors").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while deleting the supervisor. Please contact IT";

                alert(message);
            }

        })
    })
}