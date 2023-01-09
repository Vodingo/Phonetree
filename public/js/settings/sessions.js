$(document).ready(function(){

    var sessions =
    {
        datatype: "json",
        datafields: [
            { name: 'description', type: 'string' },
            { name: 'incident_date', type: 'string' },
            { name: 'created_by', type: 'string' },
            { name: 'created_at', type: 'string' },
            { name: 'status', type: 'string' },
            { name: 'updated_by', type: 'string' },
            { name: 'updated_at', type: 'string' },
        ],
        id: 'id',
        url: base_url + "/settings/sessions/list"
    };

    var dataAdapter = new $.jqx.dataAdapter(sessions, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });

    $("#sessions").jqxGrid({
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
            { text: 'Description', datafield: 'description', width: '20%' },
            { text: 'Date', datafield: 'incident_date', width: '10%', align: 'center', cellsalign: 'center' },
            { text: 'Created By', datafield: 'created_by', width: '15%', align: 'center', cellsalign: 'center' },
            { text: 'Created At', datafield: 'created_at', width: '15%', align: 'center', cellsalign: 'center' },
            { text: 'Status', datafield: 'status', width: '10%', align: 'center', cellsalign: 'center' },
            { text: 'Updated By', datafield: 'updated_by', width: '15%', align: 'center', cellsalign: 'center' },
            { text: 'Updated At', datafield: 'updated_at', width: '15%', align: 'center', cellsalign: 'center' },
        ]
    });

    createSession();
    editSession();
    filterSessions();
    completeteSession();
    deleteSession();

})

function createSession()
{
    $("#roll-call-date").jqxDateTimeInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        formatString: 'dd-MMM-yyyy'
    });

    $("#roll-call-description").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#create-session").click(function(){
        $("#createSession").modal({'backdrop': 'static', 'show': true});
    })

    $("#createSession").on('show.bs.modal', function(){
        $("#create-session-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
    })

    $("#save-session").click(function(){

        var date = $("#roll-call-date").val();
        var description = $("#roll-call-description").val();

        var data = {
            date: date,
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
            url: base_url + "/settings/sessions/save",
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-session-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#create-session-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#sessions").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the session. Please contact IT';

                $("#create-session-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }
        })

        
    })
}

function editSession()
{
    $("#edit-roll-call-date").jqxDateTimeInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        formatString: 'dd-MMM-yyyy'
    });

    $("#edit-roll-call-description").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#edit-session").click(function(){
        var selectedRow = $("#sessions").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select a session to edit");
            return;
        }

        var rowdata = $("#sessions").jqxGrid('getrowdata', selectedRow);

        $("#edit-roll-call-id").val(rowdata.uid);
        $("#edit-roll-call-date").val(new Date(rowdata.incident_date));
        $("#edit-roll-call-description").val(rowdata.description);

        $("#editSession").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#editSession").on('show.bs.modal', function(){
        $("#edit-session-alert").text('').addClass('alert-info').removeClass('alert-success alert-danger').hide();
    })

    $("#update-session").click(function(){

        var id = $("#edit-roll-call-id").val();
        var date = $("#edit-roll-call-date").val();
        var description = $("#edit-roll-call-description").val();

        var data = {
            id: id,
            date: date,
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
            url: base_url + "/settings/sessions/update",
            success: function(data) {
                
                if (data.status == false) {
                    $("#edit-session-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#edit-session-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                $("#editSession").modal('hide');

                $("#sessions").jqxGrid('updatebounddata');
                
            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the session. Please contact IT';

                $("#edit-session-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }
        })

        
    })
}

function filterSessions()
{

    $("#filter-sessions").click(function(){
        $("#filterSessions").modal({ 'backdrop': 'static', 'show': true });
    })

    $("#filterSessions").on('show.bs.modal', function(){
        $("#filter-description").val('');
        $("#filter-dates").val(true);
    })

    $("#filter-description").jqxInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#filter-dates").jqxCheckBox({ 
        theme: theme,
        width: '100%', 
        height: 30,
    });

    $("#filter-start-date").jqxDateTimeInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        formatString: 'dd-MMM-yyyy'
    });

    $("#filter-end-date").jqxDateTimeInput({ 
        theme: theme,
        width: '100%', 
        height: 30,
        formatString: 'dd-MMM-yyyy'
    });

    $('#filter-dates').on('change', function (event) { 
        var checked = event.args.checked;
        
        if (checked) {
            $("#filter-start-date").jqxDateTimeInput({ disabled: false });
            $("#filter-end-date").jqxDateTimeInput({ disabled: false });
        } else {
            $("#filter-start-date").jqxDateTimeInput({ disabled: true });
            $("#filter-end-date").jqxDateTimeInput({ disabled: true });
        }
    }); 

    $("#filter").click(function(){

        $("#filterSessions").modal('hide');

        var description = $('#filter-description').val();
        var filterByDates = $('#filter-dates').val();
        var startDate = $('#filter-start-date').val();
        var endDate = $('#filter-end-date').val();

        var sessions =
        {
            datatype: "json",
            datafields: [
                { name: 'description', type: 'string' },
                { name: 'incident_date', type: 'string' },
                { name: 'created_by', type: 'string' },
                { name: 'created_at', type: 'string' },
                { name: 'status', type: 'string' },
                { name: 'updated_by', type: 'string' },
                { name: 'updated_at', type: 'string' },
            ],
            id: 'id',
            url: base_url + "/settings/sessions/filter",
            data: {
                description: description,
                filterByDates: filterByDates,
                startDate: startDate,
                endDate: endDate
            }
        };
    
        var dataAdapter = new $.jqx.dataAdapter(sessions, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });
    
        $("#sessions").jqxGrid({ source: dataAdapter });
    })

    $("#clear-filters").click(function(){
        var sessions =
        {
            datatype: "json",
            datafields: [
                { name: 'description', type: 'string' },
                { name: 'incident_date', type: 'string' },
                { name: 'created_by', type: 'string' },
                { name: 'created_at', type: 'string' },
                { name: 'status', type: 'string' },
                { name: 'updated_by', type: 'string' },
                { name: 'updated_at', type: 'string' },
            ],
            id: 'id',
            url: base_url + "/settings/sessions/list"
        };

        var dataAdapter = new $.jqx.dataAdapter(sessions, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });

        $("#sessions").jqxGrid({ source: dataAdapter });
    })
}

function completeteSession()
{
    $("#complete-session").click(function() { 

        var selectedRow = $("#sessions").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the session to continue");
            return;
        }

        var confirmation = confirm("Are you sure you want to update the session status to completed?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#sessions").jqxGrid('getrowdata', selectedRow);

        var data = {
            session: rowdata.uid
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/sessions/complete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#sessions").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while updating session details. Please contact IT";

                alert(message);
            }

        })
    })
}

function deleteSession()
{
    $("#delete-session").click(function() { 

        var selectedRow = $("#sessions").jqxGrid('getselectedrowindex');

        if (selectedRow == -1) {
            alert("Please select the session to delete");
            return;
        }

        var confirmation = confirm("Are you sure you want to delete the selected session?");

        if (confirmation == false) {
            return;
        }

        var rowdata = $("#sessions").jqxGrid('getrowdata', selectedRow);

        var data = {
            session: rowdata.uid
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/settings/sessions/delete",
            success: function(data) {
                
                if (data.status == false) {
                    alert(data.message);
                    return;
                }

                alert(data.message);

                $("#sessions").jqxGrid('updatebounddata');

            },
            error: function(data) {
                console.log(data);

                var message = "An error occured while deleting session. Please contact IT";

                alert(message);
            }

        })
    })
}