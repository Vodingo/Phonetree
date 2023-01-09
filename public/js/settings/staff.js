$(document).ready(function(){

    $("#staff").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,              
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Name', datafield: 'full_name', width: '20%' },
            { text: 'Work Phone', datafield: 'work_phone', width: '10%' },
            { text: 'Personal Phone', datafield: 'personal_phone', width: '10%' },
            { text: 'Secondary Contact', datafield: 'secondary_phone', width: '10%' },
            { text: 'Supervisor', datafield: 'supervisor', width: '20%' },
            { text: 'Department', datafield: 'department', width: '10%' },
            { text: 'Unit', datafield: 'unit', width: '10%' },
            { text: 'Site', datafield: 'site', width: '10%' },
        ]
    });

    getStaff();
    deleteStaff();
    filterStaff();
    uploadStaff();
    createStaff();
    editStaff();

    
})

function uploadStaff()
{
    $("#upload-staff-modal").click(function(){
        $("#uploadStaffList").modal({ backdrop: 'static', show: true });
    })

    $("#uploadStaffList").on('show.bs.modal', function(){
        $("#upload-file-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
    })

    $("#upload-staff-list").click(function(e) {

        var extension = $('#staff-list-file').val().split('.').pop().toLowerCase();

        if ($.inArray(extension, ['xls', 'xlsx']) == -1) {

            $('#upload-file-alert')
                .html('Please select an Excel file (.xls or .xlsx)')
                .addClass('alert-danger')
                .removeClass('d-none alert-info alert-success');

        } else {
    
            $('#upload-file-alert')
                .html('Uploading staff list. Please wait...')
                .addClass('alert-info')
                .removeClass('d-none alert-danger alert-success');

            var file_data =$("#staff-list-file").get(0).files;// $('#staff-list-file').prop('files')[0];

            var formData = new FormData();
            formData.append('file', file_data[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: base_url + "/settings/staff/upload", 
                data: formData,
                type: 'POST',
                contentType: false, 
                cache: false,
                processData: false,
                beforeSend: function() {
                        $("#upload-file-alert").text('Uploading the staff list. Please wait...').removeClass('alert-success alert-danger')
                            .addClass('alert-info').show();
                    },
                success: function(data) {
                    if (data.status == false) {
                        $("#upload-file-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                        return;
                    }

                    $("#upload-file-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                    $("#uploadStaffList").modal('hide');

                    $("#staff").jqxGrid('updatebounddata');

                }
                
            });
        }
    });


}

function filterStaff()
{
    $("#clear-filters").click(function(){
        getStaff();
    });

    $("#filter-staff").click(function(){
        $("#filter-name").val('');
        $("#filter-department").jqxComboBox('clearSelection');
        $("#filter-unit").jqxComboBox('clearSelection');
        $("#filter-supervisor").jqxComboBox('clearSelection');

        $("#filterStaff").modal({ backdrop: 'static', show: true });
    });

    $("#filter-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/staff/department-select-list"
    };

    var departmentsDataAdapter = new $.jqx.dataAdapter(departments, {
        loadComplete: function () {
            $("#department").jqxComboBox({ source: departmentsDataAdapter.records });
            $("#edit-department").jqxComboBox({ source: departmentsDataAdapter.records })
        }
    });

    $("#filter-department").jqxComboBox({ 
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
        url: base_url + "/settings/staff/unit-select-list"
    };

    var unitsDataAdapter = new $.jqx.dataAdapter(units, {
        loadComplete: function () {
            $("#unit").jqxComboBox({ source: unitsDataAdapter.records });
            $("#edit-unit").jqxComboBox({ source: unitsDataAdapter.records })
        }
    });

    $("#filter-unit").jqxComboBox({ 
        source: unitsDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });


    var supervisors =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'name', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/staff/supervisors-select-list"
    };

    var supervisorsDataAdapter = new $.jqx.dataAdapter(supervisors, {
        loadComplete: function () {
            $("#supervisor").jqxComboBox({ source: supervisorsDataAdapter.records });
            $("#edit-supervisor").jqxComboBox({ source: supervisorsDataAdapter.records })
        }
    });

    $("#filter-supervisor").jqxComboBox({ 
        source: supervisorsDataAdapter, 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#filter").click(function(){

        $("#filterStaff").modal('hide');

        var name = $('#filter-name').val();
        var department = $('#filter-department').val();
        var unit = $('#filter-unit').val();
        var supervisor = $('#filter-supervisor').val();

        var staff = {
            datatype: "json",
            datafields: [
                { name: 'full_name', type: 'string' },
                { name: 'first_name', type: 'string' },
                { name: 'last_name', type: 'string' },
                { name: 'other_name', type: 'string' },
                { name: 'work_phone', type: 'string' },
                { name: 'personal_phone', type: 'string' },
                { name: 'secondary_phone', type: 'string' },
                { name: 'employee_number', type: 'string' },
                { name: 'email', type: 'string' },
                { name: 'department', map: 'department>name' },
                { name: 'department_id', map: 'department>id' },
                { name: 'unit', map: 'unit>name' },
                { name: 'unit_id', map: 'unit>id' },
                { name: 'supervisor', map: 'supervisor>name' },
                { name: 'supervisor_id', map: 'supervisor>id' },
                { name: 'site', type: 'string' },
            ],
            id: 'id',
            url: base_url + "/settings/staff/filter",
            data: {
                name: name,
                department: department,
                unit: unit,
                supervisor: supervisor
            }
        };
    
        var dataAdapter = new $.jqx.dataAdapter(staff);
    
        $("#staff").jqxGrid({ source: dataAdapter });
    });
}

function deleteStaff()
{
    $("#delete-staff").click(function() { 

        var selectedRow = $("#staff").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the staff to remove from the system");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected staff?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#staff").jqxGrid('getrowdata', selectedRow);
        var staff = rowdata.uid;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: null,
            method: "POST",
            url: base_url + "/settings/staff/" + staff + "/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#staff").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while saving user data. Please contact IT";

                alert(message);
            }
        });
    });
}

function getStaff()
{
    var staff =
    {
        datatype: "json",
        datafields: [
            { name: 'full_name', type: 'string' },
            { name: 'first_name', type: 'string' },
            { name: 'last_name', type: 'string' },
            { name: 'other_name', type: 'string' },
            { name: 'work_phone', type: 'string' },
            { name: 'personal_phone', type: 'string' },
            { name: 'secondary_phone', type: 'string' },
            { name: 'employee_number', type: 'string' },
            { name: 'email', type: 'string' },
            { name: 'department', map: 'department>name' },
            { name: 'department_id', map: 'department>id' },
            { name: 'unit', map: 'unit>name' },
            { name: 'unit_id', map: 'unit>id' },
            { name: 'supervisor', map: 'supervisor>name' },
            { name: 'supervisor_id', map: 'supervisor>id' },
            { name: 'site', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/staff-list"
    };
    var dataAdapter = new $.jqx.dataAdapter(staff, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#staff").jqxGrid({ source: dataAdapter });
}

function createStaff()
{
    $("#create-staff").click(function(){
        $("#createStaff").modal({ backdrop: 'static', show: true });
    });

    $("#createStaff").on('show.bs.modal', function(){
        $("#create-staff-response").text('');
        $("#create-staff-alert").addClass('alert-danger d-none').removeClass('alert-success alert-info');
    });

    $("#first-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#last-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#other-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#personal-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#work-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#secondary-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });
    $("#email").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#employee_no").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });
    $("#department").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#unit").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#supervisor").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    var sites = [
        "Kenya",
        "Tanzania",
        "Uganda",
        "South Africa"
    ];

    $("#site").jqxComboBox({ 
        source: sites,
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#save-staff").click(function(){

        var firstname = $("#first-name").val();
        var lastname = $("#last-name").val();
        var othername = $("#other-name").val();
        var work_phone = $("#work-phone").val();
        var personal_phone = $("#personal-phone").val();
        var secondary_phone = $("#secondary-phone").val();
        var email = $("#email").val();
        var employee_no = $("#employee_no").val();
        var department = $("#department").val();
        var unit = $("#unit").val();
        var supervisor = $("#supervisor").val();
        var site = $("#site").val();

        var data = {
            firstname: firstname,
            lastname: lastname,
            othername: othername,
            work_phone: work_phone,
            personal_phone: personal_phone,
            secondary_phone: secondary_phone,
            email: email,
            employee_no: employee_no,
            department: department,
            unit: unit,
            supervisor: supervisor,
            site: site
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/staff/save",
            beforeSend: function(){
                $("#create-staff-response").text('Saving staff details. Please wait...');
                $("#create-staff-alert").addClass('alert-info').removeClass('alert-danger alert-success d-none');
            },
            success: function(data) {
                if (data.status == false) {
                    $("#create-staff-response").text(data.message);
                    $("#create-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
                    return;
                }

                $("#create-staff-response").text(data.message);
                $("#create-staff-alert").addClass('alert-success').removeClass('alert-danger alert-info d-none');

                $("#first-name").val('');
                $("#last-name").val('');
                $("#other-name").val('');
                $("#work-phone").val('');
                $("#personal-phone").val('');
                $("#secondary-phone").val('');
                $("#department").jqxComboBox('clearSelection');
                $("#unit").jqxComboBox('clearSelection');
                $("#supervisor").jqxComboBox('clearSelection');
                $("#site").jqxComboBox('clearSelection');

                $("#staff").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                var message = 'An error occured while saving the staff. Please contact IT';

                $("#create-staff-response").text(message);
                $("#create-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
            }
        });
    });
}

function editStaff()
{
    $("#edit-staff").click(function(){
        var selectedRow = $("#staff").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a staff whose details you want to update");
            return;
        }

        $("#editStaff").modal({ backdrop: 'static', show: true });
    });

    $("#editStaff").on('show.bs.modal', function(){

        $("#edit-staff-response").text('');
        $("#edit-staff-alert").addClass('alert-danger d-none').removeClass('alert-success alert-info');

        var selectedRow = $("#staff").jqxGrid('getselectedrowindex');
        var rowdata = $("#staff").jqxGrid('getrowdata', selectedRow);

        $("#edit-id").val(rowdata.uid);
        $("#edit-first-name").val(rowdata.first_name);
        $("#edit-last-name").val(rowdata.last_name);
        $("#edit-other-name").val(rowdata.other_name);
        $("#edit-work-phone").val(rowdata.work_phone);
        $("#edit-personal-phone").val(rowdata.personal_phone);
        $("#edit-secondary-phone").val(rowdata.secondary_phone);
        $("#edit_email").val(rowdata.email);
        $("#edit_employee_no").val(rowdata.employee_number);
        $("#edit-department").val(rowdata.department_id);
        $("#edit-unit").val(rowdata.unit_id);
        $("#edit-supervisor").val(rowdata.supervisor_id);
        $("#edit-site").val(rowdata.site);
    });

    $("#editStaff").on('hidden.bs.modal', function(){
        $("#edit-staff-response").text('');
        $("#edit-staff-alert").addClass('alert-danger d-none').removeClass('alert-success alert-info');
        
        $("#edit-id").val('');
        $("#edit-first-name").val('');
        $("#edit-last-name").val('');
        $("#edit-other-name").val('');
        $("#edit-work-phone").val('');
        $("#edit-personal-phone").val('');
        $("#edit-secondary-phone").val('');
        $("#edit_email").val('');
        $("#edit_employee_no").val('');
        $("#edit-department").val('');
        $("#edit-unit").val('');
        $("#edit-supervisor").val('');
        $("#edit-site").val('');
    });

    $("#edit-first-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-last-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-other-name").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-personal-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-work-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-secondary-phone").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit_email").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit_employee_no").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-department").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#edit-unit").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#edit-supervisor").jqxComboBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    var sites = [
        "Kenya",
        "Tanzania",
        "Uganda",
        "South Africa"
    ];

    $("#edit-site").jqxComboBox({ 
        source: sites,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id'
    });

    $("#update-staff").click(function(){

        var uid = $("#edit-id").val();
        var firstname = $("#edit-first-name").val();
        var lastname = $("#edit-last-name").val();
        var othername = $("#edit-other-name").val();
        var work_phone = $("#edit-work-phone").val();
        var personal_phone = $("#edit-personal-phone").val();
        var secondary_phone = $("#edit-secondary-phone").val();
        var email = $("#edit_email").val();
        var employee_no = $("#edit_employee_no").val();
        var department = $("#edit-department").val();
        var unit = $("#edit-unit").val();
        var supervisor = $("#edit-supervisor").val();
        var site = $("#edit-site").val();

        var data = {
            firstname: firstname,
            lastname: lastname,
            othername: othername,
            work_phone: work_phone,
            personal_phone: personal_phone,
            secondary_phone: secondary_phone,
            email: email,
            employee_number: employee_no,
            department: department,
            unit: unit,
            supervisor: supervisor,
            site: site
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/staff/" + uid + "/update",
            beforeSend: function(){
                $("#edit-staff-response").text('Saving staff details. Please wait...');
                $("#edit-staff-alert").addClass('alert-info').removeClass('alert-danger alert-success d-none');
            },
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-staff-response").text(data.message);
                    $("#edit-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
                    return;
                }

                $("#edit-staff-response").text(data.message);
                $("#edit-staff-alert").addClass('alert-success').removeClass('alert-danger alert-info d-none');

                $("#editStaff").modal('hide');

                $("#staff").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the staff. Please contact IT';

                $("#edit-staff-response").text(message);
                $("#edit-staff-alert").addClass('alert-danger').removeClass('alert-success alert-info d-none');
            }
        });
    });
}

