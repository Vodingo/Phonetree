$(document).on( "pagecreate", "#dashboard-page", function() {

    $("#dashboard-sessions").on('change', function(event){
        var session = $("#dashboard-sessions").val();
        getDashboard(session);
    });
});

$(document).on( "pageshow", "#dashboard-page", function() {
    var session = $("#dashboard-sessions").val();
    getDashboard(session);
});

function getDashboard(session)
{
    var ctx = $('#summary-chart');

    data = {
        datasets: [{
            data: [ 0, 0, 0],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
            ],
        }],

        labels: [
            'Not Contacted',
            'Unaccounted',
            'Accounted'
        ]
    };

    options = {
        legend: {
            display: true,
            position: 'bottom'
        }
    }

    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });

    var accountedCountElement = $("#accounted-count");
    var unaccountedCountElement = $("#unaccounted-count");
    var notContactedCountElement = $("#not-contacted-count");
    var totalStaffCountElement = $("#total-staff-count");


    $(".links").each(function() {
        var href = $(this).attr('href');
        var url = new URL(href);

        url.searchParams.set("session", session);
        var newUrl = url.href; 

        $(this).attr('href', newUrl);
    });
    
    $.ajaxSetup({
        headers:{
           'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
        }
     });

    $.get(base_url + '/management/summary/'+session, function(data){ 
        
        accountedCountElement.text(data.accountedCount);
        unaccountedCountElement.text(data.unaccountedCount);
        notContactedCountElement.text(data.staffNotContacted);
        totalStaffCountElement.text(data.totalStaff);
        
        myPieChart.data.datasets[0].data = [data.staffNotContacted, data.unaccountedCount,  data.accountedCount];
        myPieChart.update();
       
    })

    setTimeout (function() { getDashboard(chart, session); }, 60000);
}