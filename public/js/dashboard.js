$(document).ready(function(){

    var sessions =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'integer' },
            { name: 'description' },
        ],
        id: 'id',
        url: base_url + "/get-dashboard-sessions-select-list"
    };

    var sessionsDataAdapter = new $.jqx.dataAdapter(sessions, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#sessions").jqxComboBox({ 
        source: sessionsDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'description',
        valueMember: 'id',
        selectedIndex: 0
    });

    $("#sessions").on('select', function(event) {
        var args = event.args;

        if (args) {
            var session = args.item.value;
            getDashboard(myPieChart, session);
        }
    });

    var ctx = $('#myChart');

    data = {
        datasets: [{
            data: [0, 0, 0],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
            ],
        }],
    
        labels: [
            'Staff Not Contacted',
            'Unaccounted Staff',
            'Accounted Staff'
        ]
    };

    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
    });

    $("#supervisors-summary").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Supervisor Name', datafield: 'supervisor', filtercondition: 'contains', width: '30%' },
            { text: 'Supervisor Personal Number', datafield: 'personal_phone', filterable: false, width: '15%', cellsalign: 'center', align: 'center' },
            { text: 'Supervisor Work Number', datafield: 'work_phone', filterable: false, width: '15%', cellsalign: 'center', align: 'center' },
            { text: 'Staff Assigned', datafield: 'total', filterable: false, width: '10%', cellsalign: 'center', align: 'center' },
            { text: 'Accounted', datafield: 'accounted', filterable: false, width: '10%', cellsalign: 'center', align: 'center' },
            { text: 'Not Accounted', datafield: 'unaccounted', filterable: false, width: '10%', cellsalign: 'center', align: 'center' },
            { text: 'Not Contacted', datafield: 'not_contacted', filterable: false, width: '10%', cellsalign: 'center', align: 'center' },
        ]
    });

    $("#accounted-staff").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Staff Name', datafield: 'staff_name', filtercondition: 'contains', width: '18%' },
            { text: 'Supervisor Name', datafield: 'supervisor', filtercondition: 'contains', width: '18%' },
            { text: 'Personal Number', datafield: 'personal_phone', filterable: false, width: '9%' },
            { text: 'Work Number', datafield: 'work_phone', filterable: false, width: '9%' },
            { text: 'Secondary Contact', datafield: 'secondary_phone', filterable: false, width: '9%' },
            { text: 'Accounted', datafield: 'accounted', filtercondition: 'contains', width: '7%', cellsalign: 'center', align: 'center' },
            { text: 'Comments', datafield: 'comments', filterable: false, width: '10%' },
            { text: 'Date Updated', datafield: 'date_updated', filterable: false, width: '8%' },
            { text: 'Updated By', datafield: 'updated_by', filterable: false, width: '12%' },
        ]
    });

    $("#unaccounted-staff").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Staff Name', datafield: 'staff_name', filtercondition: 'contains', width: '18%' },
            { text: 'Supervisor Name', datafield: 'supervisor', filtercondition: 'contains', width: '18%' },
            { text: 'Personal Number', datafield: 'personal_phone', filterable: false, width: '9%' },
            { text: 'Work Number', datafield: 'work_phone', filterable: false, width: '9%' },
            { text: 'Secondary Contact', datafield: 'secondary_phone', filterable: false, width: '9%' },
            { text: 'Accounted', datafield: 'accounted', filtercondition: 'contains', width: '7%', cellsalign: 'center', align: 'center' },
            { text: 'Comments', datafield: 'comments', filterable: false, width: '10%' },
            { text: 'Date Updated', datafield: 'date_updated', filterable: false, width: '8%' },
            { text: 'Updated By', datafield: 'updated_by', filterable: false, width: '12%' },
        ]
    });

    $("#staff-not-contacted").jqxGrid({
        width: '100%',
        height: '100%',
        theme: theme,
        pageable: false,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: false,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Staff Name', datafield: 'staff_name', width: '20%' },
            { text: 'Supervisor Name', datafield: 'supervisor', filtercondition: 'contains', width: '20%' },
            { text: 'Personal Number', datafield: 'personal_phone', filterable: false, width: '10%' },
            { text: 'Work Number', datafield: 'work_phone', filterable: false, width: '10%' },
            { text: 'Secondary Contact', datafield: 'secondary_phone', filterable: false, width: '10%' },
            { text: 'Department', datafield: 'department', filtercondition: 'contains', width: '15%' },
            { text: 'Unit', datafield: 'unit', filtercondition: 'contains', width: '15%' },
        ]
    });

    getAccountedStaff();
    getUnAccountedStaff();
    getStaffNotContacted();
    getSupervisorsSummary();
    viewSupervisorStaff();
});

function getAccountedStaff()
{ 
    $("#nav-accounted-tab").on('shown.bs.tab', function(){
        getAccountedStaffData();
    });

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/department-select-list"
    };

    var departmentDataAdapter = new $.jqx.dataAdapter(departments);

    $("#filter-accounted-department").jqxComboBox({ 
        source: departmentDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-accounted-department").on('select', function(event){

        var args = event.args;

        if (args) {
            console.log(args)
            getAccountedStaffData();
        }
        
    })

    var units =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/unit-select-list"
    };

    var unitDataAdapter = new $.jqx.dataAdapter(units);

    $("#filter-accounted-unit").jqxComboBox({ 
        source: unitDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-accounted-unit").on('select', function(event){

        var args = event.args;

        if (args) {
            getAccountedStaffData();
        }
        
    })

    var supervisors =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/supervisors-select-list"
    };

    var supervisorDataAdapter = new $.jqx.dataAdapter(supervisors);

    $("#filter-accounted-supervisor").jqxComboBox({ 
        source: supervisorDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-accounted-supervisor").on('select', function(event){

        var args = event.args;

        if (args) {
            getAccountedStaffData();
        }
        
    })

    $("#filter-accounted-staff").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30
    });

    $("#filter-accounted-staff").on('keypress', function(event){

        if (event.keyCode == 13) {
            getAccountedStaffData();
        }
        
    })

    $("#clear-accounted-filters").on('click', function(event){

        $("#filter-accounted-department").jqxComboBox('clearSelection');
        $("#filter-accounted-unit").jqxComboBox('clearSelection');
        $("#filter-accounted-supervisor").jqxComboBox('clearSelection');
        $("#filter-accounted-staff").val('');

        getAccountedStaffData();
        
    })
}

function getUnAccountedStaff()
{
    $("#nav-unaccounted-tab").on('shown.bs.tab', function(){ 
        getUnAccountedStaffData();
    });
  
    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/department-select-list"
    };

    var departmentDataAdapter = new $.jqx.dataAdapter(departments);

    $("#filter-unaccounted-department").jqxComboBox({ 
        source: departmentDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-unaccounted-department").on('select', function(event){

        var args = event.args;

        if (args) {
            getUnAccountedStaffData();
        }
        
    })

    var units =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/unit-select-list"
    };

    var unitDataAdapter = new $.jqx.dataAdapter(units);

    $("#filter-unaccounted-unit").jqxComboBox({ 
        source: unitDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-unaccounted-unit").on('select', function(event){

        var args = event.args;

        if (args) {
            getUnAccountedStaffData();
        }
        
    })

    var supervisors =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/supervisors-select-list"
    };

    var supervisorDataAdapter = new $.jqx.dataAdapter(supervisors);

    $("#filter-unaccounted-supervisor").jqxComboBox({ 
        source: supervisorDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-unaccounted-supervisor").on('select', function(event){

        var args = event.args;

        if (args) {
            getUnAccountedStaffData();
        }
        
    })

    $("#filter-unaccounted-staff").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30
    });

    $("#filter-unaccounted-staff").on('keypress', function(event){

        if (event.keyCode == 13) {
            getUnAccountedStaffData();
        }
        
    })

    $("#clear-unaccounted-filters").on('click', function(event){

        $("#filter-unaccounted-department").jqxComboBox('clearSelection');
        $("#filter-unaccounted-unit").jqxComboBox('clearSelection');
        $("#filter-unaccounted-supervisor").jqxComboBox('clearSelection');
        $("#filter-unaccounted-staff").val('');

        getUnAccountedStaffData();
        
    })
}

function getStaffNotContacted()
{
    $("#nav-not-contacted-tab").on('shown.bs.tab', function(){ 
        getStaffNotContactedData();
    });

    var departments =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/department-select-list"
    };

    var departmentDataAdapter = new $.jqx.dataAdapter(departments);

    $("#filter-not-contacted-department").jqxComboBox({ 
        source: departmentDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-not-contacted-department").on('select', function(event){

        var args = event.args;

        if (args) {
            console.log(args)
            getStaffNotContactedData();
        }
        
    })

    var units =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/unit-select-list"
    };

    var unitDataAdapter = new $.jqx.dataAdapter(units);

    $("#filter-not-contacted-unit").jqxComboBox({ 
        source: unitDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-not-contacted-unit").on('select', function(event){

        var args = event.args;

        if (args) {
            getStaffNotContactedData();
        }
        
    })

    var supervisors =
    {
        datatype: "json",
        datafields: [
            { name: 'name', type: 'string' },
            { name: 'id', type: 'integer' },
        ],
        id: 'id',
        url: base_url + "/management/supervisors-select-list"
    };

    var supervisorDataAdapter = new $.jqx.dataAdapter(supervisors);

    $("#filter-not-contacted-supervisor").jqxComboBox({ 
        source: supervisorDataAdapter,
        theme: theme,
        width: '100%', 
        height: 30,
        displayMember: 'name',
        valueMember: 'id',
    });

    $("#filter-not-contacted-supervisor").on('select', function(event){

        var args = event.args;

        if (args) {
            getStaffNotContactedData();
        }
        
    })

    $("#filter-not-contacted-staff").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30
    });

    $("#filter-not-contacted-staff").on('keypress', function(event){

        if (event.keyCode == 13) {
            getStaffNotContactedData();
        }
        
    })

    $("#clear-notcontacted-filters").on('click', function(event){

        $("#filter-not-contacted-department").jqxComboBox('clearSelection');
        $("#filter-not-contacted-unit").jqxComboBox('clearSelection');
        $("#filter-not-contacted-supervisor").jqxComboBox('clearSelection');
        $("#filter-not-contacted-staff").val('');

        getStaffNotContactedData();
        
    })
}

function getDashboard(chart, session)
{

    $.ajaxSetup({
        headers:{
           'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
        }
     });

    $.get('management/summary/'+session, function(data){ 
        
        chart.data.datasets[0].data = [data.staffNotContacted, data.unaccountedCount,  data.accountedCount];
        chart.update();

        $("#updated-at").text("Last Updated at : "+new Date());
    })

    setTimeout (function() { getDashboard(chart, session); }, 60000);
}

function getStaffNotContactedData()
{
    var session = $("#sessions").val();
    var department = $("#filter-not-contacted-department").val();
    var unit = $("#filter-not-contacted-unit").val();
    var supervisor = $("#filter-not-contacted-supervisor").val();
    var staff = $("#filter-not-contacted-staff").val();

    var staff =
    {
        datatype: "json",
        datafields: [
            { name: 'staff_name', type: 'string' },
            { name: 'supervisor' },
            { name: 'personal_phone' },
            { name: 'work_phone' },
            { name: 'secondary_phone' },
            { name: 'department' },
            { name: 'unit' },
            { name: 'updated_by' },
            { name: 'date_updated' },
        ],
        id: 'id',
        url: base_url + "/management/notcontacted/",
        data: {
            session: session,
            department: department,
            unit: unit,
            supervisor: supervisor,
            staff: staff
        }
    };

    var dataAdapter = new $.jqx.dataAdapter(staff, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#staff-not-contacted").jqxGrid({ source: dataAdapter });
}

function getUnAccountedStaffData()
{
    var session = $("#sessions").val();
    var department = $("#filter-unaccounted-department").val();
    var unit = $("#filter-unaccounted-unit").val();
    var supervisor = $("#filter-unaccounted-supervisor").val();
    var staff = $("#filter-unaccounted-staff").val();

    var staff =
        {
            datatype: "json",
            datafields: [
                { name: 'staff_name', type: 'string' },
                { name: 'supervisor' },
                { name: 'personal_phone' },
                { name: 'work_phone' },
                { name: 'secondary_phone' },
                { name: 'department' },
                { name: 'unit' },
                { name: 'accounted' },
                { name: 'comments' },
                { name: 'updated_by' },
                { name: 'date_updated' },
            ],
            id: 'id',
            url: base_url + "/management/unaccounted",
            data: {
                session: session,
                department: department,
                unit: unit,
                supervisor: supervisor,
                staff: staff
            }
        };
    
        var dataAdapter = new $.jqx.dataAdapter(staff, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });
    
        $("#unaccounted-staff").jqxGrid({ source: dataAdapter });
}

function getAccountedStaffData()
{
    var session = $("#sessions").val();
    var department = $("#filter-accounted-department").val();
    var unit = $("#filter-accounted-unit").val();
    var supervisor = $("#filter-accounted-supervisor").val();
    var staff = $("#filter-accounted-staff").val();

    var data =
    {
        datatype: "json",
        datafields: [
            { name: 'staff_name' },
            { name: 'supervisor' },
            { name: 'personal_phone' },
            { name: 'work_phone' },
            { name: 'secondary_phone' },
            { name: 'department' },
            { name: 'unit' },
            { name: 'accounted' },
            { name: 'comments' },
            { name: 'updated_by' },
            { name: 'date_updated' },
        ],
        id: 'id',
        url: base_url + "/management/accounted/",
        data: {
            session: session,
            department: department,
            unit: unit,
            supervisor: supervisor,
            staff: staff
        }
    };

    var dataAdapter = new $.jqx.dataAdapter(data, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#accounted-staff").jqxGrid({ source: dataAdapter });

}

function getSupervisorsSummary()
{ 
    $("#nav-supervisors-summary-tab").on('shown.bs.tab', function(){
        getSupervisorsSummaryData();
    });

    $("#supervisor-view-all").on('click', function(){
        var filter = $(this).attr('filter');

        if (filter == 'true') {
            $(this).removeClass('btn-info').addClass('btn-secondary');
            $(this).attr('filter', false);
            $(this).text('View Supervisors with Unaccouted/Not Contacted Staff');
        } else {
            $(this).removeClass('btn-secondary').addClass('btn-info');
            $(this).attr('filter', true);
            $(this).text('View All Supervisors');
        }

        getSupervisorsSummaryData()
    })
}

function getSupervisorsSummaryData()
{
    var filter = $('#supervisor-view-all').attr('filter');
    var filterValue = (filter == 'true') ? true : false;
    
    var session = $("#sessions").val();

    var summary =
    {
        datatype: "json",
        datafields: [
            { name: 'supervisor' },
            { name: 'personal_phone' },
            { name: 'work_phone' },
            { name: 'secondary_phone' },
            { name: 'total' },
            { name: 'accounted' },
            { name: 'unaccounted' },
            { name: 'not_contacted'}
        ],
        id: 'id',
        url: base_url + "/management/supervisors-summary",
        data: {
           session: session,
           filter: filterValue
        }
    };

    var dataAdapter = new $.jqx.dataAdapter(summary, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) {

            if(data.error === false) {
                alert(data.message);
            }
         },
        loadError: function (xhr, status, error) {

            alert(error)
         }
    });

    $("#supervisors-summary").jqxGrid({ source: dataAdapter });

}

function viewSupervisorStaff()
{
    $("#supervisor-view-accounted-staff").on('click', function(){

        $("#supervisors-staff-view").jqxGrid({
            width: '100%',
            height: 400,
            theme: theme,
            pageable: false,
            sortable: true,
            altrows: true,
            enabletooltips: true,
            editable: false,
            selectionmode: 'singlerow',
            columns: [
                { text: 'Staff Name', datafield: 'staff_name', filtercondition: 'contains', width: '50%' },
                { text: 'Date Contacted', datafield: 'date_updated', filterable: false, width: '25%' },
                { text: 'Contacted By', datafield: 'updated_by', filterable: false, width: '25%' },
            ]
        });
        
        if ( validateClickEvent('Accounted Staff')) {

            var session = $("#sessions").val();
            var selectedRow = $("#supervisors-summary").jqxGrid('getselectedrowindex');
            var rowdata = $("#supervisors-summary").jqxGrid('getrowdata', selectedRow); 

            var data =
            {
                datatype: "json",
                datafields: [
                    { name: 'staff_name' },
                    { name: 'comments' },
                    { name: 'updated_by' },
                    { name: 'date_updated' },
                ],
                id: 'id',
                url: base_url + "/management/accounted/",
                data: {
                    session: session,
                    supervisor: rowdata.uid
                }
            };
        
            var dataAdapter = new $.jqx.dataAdapter(data);
        
            $("#supervisors-staff-view").jqxGrid({ source: dataAdapter });
        }

    })

    $("#supervisor-view-unaccounted-staff").on('click', function(){

        $("#supervisors-staff-view").jqxGrid({
            width: '100%',
            height: 400,
            theme: theme,
            pageable: false,
            sortable: true,
            altrows: true,
            enabletooltips: true,
            editable: false,
            selectionmode: 'singlerow',
            columns: [
                { text: 'Staff Name', datafield: 'staff_name', filtercondition: 'contains', width: '20%' },
                { text: 'Personal Number', datafield: 'personal_phone', filterable: false, width: '12%', cellsalign: 'center', align: 'center' },
                { text: 'Work Number', datafield: 'work_phone', filterable: false, width: '12%', cellsalign: 'center', align: 'center' },
                { text: 'Secondary Contact', datafield: 'secondary_phone', filterable: false, width: '12%', cellsalign: 'center', align: 'center' },
                { text: 'Comments', datafield: 'comments', filterable: false, width: '20%' },
                { text: 'Date Contacted', datafield: 'date_updated', filterable: false, width: '12%' },
                { text: 'Contacted By', datafield: 'updated_by', filterable: false, width: '12%' },
            ]
        });

        if (validateClickEvent('Unaccounted Staff')) {
            var session = $("#sessions").val();
            var selectedRow = $("#supervisors-summary").jqxGrid('getselectedrowindex');
            var rowdata = $("#supervisors-summary").jqxGrid('getrowdata', selectedRow); 

            var data =
            {
                datatype: "json",
                datafields: [
                    { name: 'staff_name' },
                    { name: 'personal_phone' },
                    { name: 'work_phone' },
                    { name: 'secondary_phone' },
                    { name: 'comments' },
                    { name: 'updated_by' },
                    { name: 'date_updated' },
                ],
                id: 'id',
                url: base_url + "/management/unaccounted/",
                data: {
                    session: session,
                    supervisor: rowdata.uid
                }
            };
        
            var dataAdapter = new $.jqx.dataAdapter(data);
        
            $("#supervisors-staff-view").jqxGrid({ source: dataAdapter });
        }

    })

    $("#supervisor-view-notcontacted-staff").on('click', function(){

        $("#supervisors-staff-view").jqxGrid({
            width: '100%',
            height: 400,
            theme: theme,
            pageable: false,
            sortable: true,
            altrows: true,
            enabletooltips: true,
            editable: false,
            selectionmode: 'singlerow',
            columns: [
                { text: 'Staff Name', datafield: 'staff_name', width: '40%' },
                { text: 'Personal Number', datafield: 'personal_phone', filterable: false, width: '20%', cellsalign: 'center', align: 'center' },
                { text: 'Work Number', datafield: 'work_phone', filterable: false, width: '20%', cellsalign: 'center', align: 'center' },
                { text: 'Secondary Contact', datafield: 'secondary_phone', filterable: false, width: '20%', cellsalign: 'center', align: 'center' },
            ]
        });

        if (validateClickEvent('Staff Not Contacted')) {
            var session = $("#sessions").val();
            var selectedRow = $("#supervisors-summary").jqxGrid('getselectedrowindex');
            var rowdata = $("#supervisors-summary").jqxGrid('getrowdata', selectedRow); 

            var data =
            {
                datatype: "json",
                datafields: [
                    { name: 'staff_name' },
                    { name: 'personal_phone' },
                    { name: 'work_phone' },
                    { name: 'secondary_phone' },
                ],
                id: 'id',
                url: base_url + "/management/notcontacted",
                data: {
                    session: session,
                    supervisor: rowdata.uid
                }
            };
        
            var dataAdapter = new $.jqx.dataAdapter(data);
        
            $("#supervisors-staff-view").jqxGrid({ source: dataAdapter });
        }

    })

    $("#supervisorViewModal").on('hidden.bs.modal', function(){
        $("#supervisors-staff-view").jqxGrid('clear');
    })
}

function validateClickEvent(title)
{
    var selectedRow = $("#supervisors-summary").jqxGrid('getselectedrowindex');

    if (selectedRow == -1) {
        alert("Please select a supervisor to continue");
        return;
    }

    var session = $("#sessions").jqxComboBox('getSelectedItem');
    var rowdata = $("#supervisors-summary").jqxGrid('getrowdata', selectedRow);

    $("#view-title").text(title);
    $("#view-session-name").text(session.label);
    $("#view-supervisor-name").text(rowdata.supervisor);

    $("#supervisorViewModal").modal({ backdrop: 'static', show: true });

    return true;
}