var editedRows = [];

try {

    $(document).ready(function () {

        $("#sessions").jqxComboBox({
            theme: theme,
            width: '100%',
            height: 30,
            displayMember: 'description',
            valueMember: 'id',
            selectedIndex: 0
        });

        var sessions =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'integer' },
                { name: 'description', type: 'string' },
            ],
            id: 'id',
            url: base_url + "/get-home-sessions-select-list"
        };



        var sessionsDataAdapter = new $.jqx.dataAdapter(sessions, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });

        $("#sessions").jqxComboBox({ source: sessionsDataAdapter });

        $("#sessions").on('bindingComplete', function (event) {
            $("#sessions").jqxComboBox('selectIndex', 0)
        });

        $("#supervisor").jqxComboBox({
            source: supervisorsDataAdapter,
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
            url: base_url + "/get-home-supervisor-select-list"
        };

        var supervisorsDataAdapter = new $.jqx.dataAdapter(supervisors, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });



        $("#supervisor").on('bindingComplete', function (event) {
            $("#supervisor").val(user);
        });

        $("#supervisor").jqxComboBox({ source: supervisorsDataAdapter });

        var status =
        {
            datatype: "json",
            datafields: [
                { name: 'status_id', type: 'integer' },
                { name: 'status_description', type: 'string' },
            ],
            id: 'status_id',
            url: base_url + "/get-home-register-status-select-list"
        };
        

        var statusDataAdapter = new $.jqx.dataAdapter(status, { autoBind: true });

        $("#staff-list").jqxGrid({
            width: '100%',
            height: '100%',
            theme: theme,
            pageable: false,
            sortable: true,
            altrows: true,
            enabletooltips: true,
            editable: true,
            selectionmode: 'singlerow',
            editmode: 'selectedrow',
            columns: [
                { text: 'Name', datafield: 'name', width: '25%', editable: false },
                { text: 'Personal Phone', datafield: 'personal_phone', width: '12.5%', editable: false, align: 'center', cellsalign: 'center' },
                { text: 'Work Phone', datafield: 'work_phone', width: '12.5%', editable: false, align: 'center', cellsalign: 'center' },
                { text: 'Secondary Phone', datafield: 'secondary_phone', width: '12.5%', editable: false, align: 'center', cellsalign: 'center' },
                {
                    text: 'Status', displayfield: 'status_description', datafield: 'status_id', width: '15%', columntype: 'dropdownlist', align: 'center', cellsalign: 'center',
                    createeditor: function (row, column, editor) {
                        editor.jqxDropDownList({
                            autoDropDownHeight: true,
                            source: statusDataAdapter.records,
                            valueMember: 'status_id',
                            displayMember: 'status_description',
                        });
                    },
                    cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
                        if (newvalue == "") return oldvalue;
                    }
                },
                { text: 'Comments', datafield: 'comments', width: '22.5%', align: 'center', cellsalign: 'center' },
            ]
        });

        


        $("#supervisor").on('change', function (event) {
            var args = event.args;
            if (args) {                
                getSupervisorsStaff();
            }
        });

        $("#sessions").on('change', function (event) {
            var args = event.args;
            if (args) {
                getSupervisorsStaff();
            }
        });
    });


} catch (error) {

    alert("error" + JSON.stringify(error));

}

try {

    function getSupervisorsStaff() {
        var session = $("#sessions").val();
        var supervisor = $("#supervisor").val();

        var staff =
        {
            datatype: "json",
            datafields: [
                { name: 'name', type: 'string' },
                { name: 'personal_phone', type: 'string' },
                { name: 'work_phone', type: 'string' },
                { name: 'secondary_phone', type: 'string' },
                { name: 'department', type: 'string' },
                { name: 'unit', type: 'string' },
                { name: 'status_id' },
                { name: 'status_description' },
                { name: 'comments', type: 'string' },
            ],
            id: 'id',
            url: base_url + "/home/supervisors/staff",
            data: {
                session: session,
                supervisor: supervisor
            },
            updaterow: function (rowid, rowdata, commit) {

                var session = $("#sessions").val();

                var data = {
                    session: session,
                    staff: rowdata.uid,
                    staffName: rowdata.name,
                    status_id: rowdata.status_id,
                    comments: rowdata.comments
                };

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    data: data,
                    method: "POST",
                    url: base_url + "/home/register/save",
                    success: function (data) {

                        if (data.status == false) {
                            alert(data.message);
                            commit(false);
                            return;
                        }
                        commit(true);
                    },
                    error: function (data) {
                        commit(false);
                    }
                });
            }
        };

        var dataAdapter = new $.jqx.dataAdapter(staff, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });

        $("#staff-list").jqxGrid({ source: dataAdapter });
    }
} catch (error) {
    alert("error" + JSON.stringify(error));
}


