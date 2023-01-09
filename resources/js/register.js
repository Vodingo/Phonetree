import Axios from "axios";

$(document).ready(function(){

    $("#session").select2({
        theme: 'bootstrap4',
    });

    $("#supervisor").select2({
        theme: 'bootstrap4',
    });

    $("#staff").select2({
        theme: 'bootstrap4',
    });

    $("#session").on('change', function(e){

        var session = $(this).val();

        getSessionRegister(session);

    })

    var sessionRegister = $("#session").val();

    getSessionRegister(sessionRegister);

    $("#dashboard-session").on('change', function(e){

        var session = $(this).val();

        getSessionData(session);

    })

    var session = $("#dashboard-session").val();
   
    getSessionData(session);
})

function getSessionData(session) {
    var summary = [];

    var ctx = document.getElementById('myChart').getContext('2d');

    $.ajaxSetup({
        headers:{
           'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
        }
     });

    $.get('management/summary/'+session, function(data){
        summary = [data.accountedCount, data.unaccountedCount];

        var data = {
            datasets: [{
                data: summary,
                backgroundColor: ["#0074D9", "#FF4136"]
            }],
        
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Accounted',
                'Unaccounted'
            ]
        };
    
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data
        });

    })

    $("#accounted-table").DataTable().clear();
    $("#accounted-table").DataTable().destroy();

    $("#unaccounted-table").DataTable().clear();
    $("#unaccounted-table").DataTable().destroy();

    $("#notcontacted-table").DataTable().clear();
    $("#notcontacted-table").DataTable().destroy();

    $("#accounted-table").DataTable({
        "theme": 'bootstrap4',
        "ajax": 'management/accounted/'+session,
        "columns": [
            { "data": "staff", "defaultContent": "" },
            { "data": "work_phone", "defaultContent": "" },
            { "data": "personal_phone", "defaultContent": "" },
            { "data": "accounted", "defaultContent": "" },
            { "data": "comment", "defaultContent": "" }
        ]
    });

    $("#unaccounted-table").DataTable({
        "theme": 'bootstrap4',
        "ajax": 'management/unaccounted/'+session,
        "columns": [
            { "data": "staff", "defaultContent": "" },
            { "data": "work_phone", "defaultContent": "" },
            { "data": "personal_phone", "defaultContent": "" },
            { "data": "accounted", "defaultContent": "" },
            { "data": "comment", "defaultContent": "" }
        ]
    });

    $("#notcontacted-table").DataTable({
        "theme": 'bootstrap4',
        "ajax": 'management/notcontacted/'+session,
        "columns": [
            { "data": "name", "defaultContent": "" },
            { "data": "work_phone_number", "defaultContent": "" },
            { "data": "personal_phone_number", "defaultContent": "" },
            { "data": "emergency_contact_number", "defaultContent": "" },
            { "data": "active", "defaultContent": "" }
        ]
    });
}

function getSessionRegister(sessionRegister) {

    $("#register").DataTable().clear();
    $("#register").DataTable().destroy();

    $("#register").DataTable({
        "theme": 'bootstrap4',
        "ajax": "register/"+sessionRegister,
        "columns": [
            { "data": "staff" },
            { "data": "accounted" },
            { "data": "comment" }
        ]
    });
}