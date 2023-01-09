$(document).on("pagecreate", "#data-entry-page", function() {

    var sessionsList = $("#sessions");
    var supervisorList = $("#supervisor");

    sessionsList.on('change', function(){

        var selectedSession = sessionsList.val();
        var selectedSupervisor = supervisorList.val();

        updateLocalStorage(selectedSession, selectedSupervisor);
        loadSupervisorStaff(selectedSession, selectedSupervisor);
    })

    supervisorList.on('change', function(){

        var selectedSession = sessionsList.val();
        var selectedSupervisor = supervisorList.val();

        updateLocalStorage(selectedSession, selectedSupervisor);
        loadSupervisorStaff(selectedSession, selectedSupervisor);
    });
});

$(document).on("pageshow", "#data-entry-page", function() {

    var session = localStorage.getItem('session');
    var supervisor = localStorage.getItem('supervisor');

    if (session == null) {
        $("#sessions").val($("#sessions option:first").val());
    } else {
        $("#sessions").val(session);
    }
    
    session = $("#sessions").val();

    $('#supervisor').val(supervisor).selectmenu('refresh');
    
    updateLocalStorage(session, supervisor);
    loadSupervisorStaff(session, supervisor);

});

$(document).on("pagecreate", "#create-session-page", function() { 
    $("#create-session-alert").text('').hide();
})

$(document).on("pagecreate", "#entry-popup", function() { 
    $("#create-entry-alert").text('').hide();

    $("#update-staff-status").click(function(){

        var session = $("#sessions").val();
        var staff = $("#staff-id").val();
        var accounted = $("input[name='status']:checked").val();
        var comment = $("#entry-comments").val();

        var data = {
            session: session,
            staff: staff,
            accounted: accounted,
            comments: comment
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            data: data,
            method: "POST",
            url: base_url + "/mobile/register/save",
            success: function(data) {
                
                if (data.status == false) {
                    $("#create-entry-alert").text(data.message).removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                $("#create-entry-alert").text(data.message).addClass('alert-success').removeClass('alert-danger alert-info').show();

                window.location =  base_url + "/mobile/index";

            },
            error: function(data) {
                console.log(data);
                var message = 'An error occured while saving the session. Please contact IT';

                $("#create-entry-alert").text(message).removeClass('alert-success alert-info').addClass('alert-danger').show();
            }
        });
    });
});

function loadSupervisorStaff(session, supervisor) {

    /*$.mobile.loading( 'show', {
        text: 'Loading...',
        textVisible: true,
        theme: 'a'
    });*/

    var staffList = $("#supervisor-staff-list");

    $.getJSON(base_url + "/settings/supervisors-staff-list", {session: session, supervisor: supervisor}, function(data) {

        if (data.length > 0) {

            staffList.empty();
            
            var staffListItems = "";
            
            $.each(data, function(key, item) {

               /* var theme = 'c';

                if (item.accounted == true) {
                    theme = 'b';
                }
                */
               
                var image = '';
                var current_status = '<h6 style="color:red;">(Not contacted)</h6>';

                if (item.status_id == 1) {
                    image = '<img src="'+base_url+'/images/accounted.png" class="ui-li-icon ui-corner-none"></img>';
                    current_status = '<h6 style="color:limegreen;">(Accounted for)</h6>';
                } else if (item.status_id == 2) {
                    image = '<img src="'+base_url+'/images/not-accounted.png" class="ui-li-icon ui-corner-none"></img>';
                    current_status = '<h6 style="color:orange;">(Not accounted for)</h6>';
                }
                
                staffListItems += '<li class="list-group-item">'
                staffListItems += '<a href="' + base_url + '/mobile/entry-form/'+session+'/'+ item.id +'" data-rel="page" data-transition="none">';
                /*staffListItems += image;*/
                staffListItems += '<h5>'+ item.name + '</h5></a>';
                staffListItems += current_status;
                staffListItems += '<p>Phone Numbers:';

                        if (item.personal_phone != null && item.personal_phone.length > 0) {
                            staffListItems +=' <span onclick="window.location.href="tel:'+ item.personal_phone +'" class="text-primary">' + item.personal_phone + '</span>';
                        }

                        if (item.work_phone != null && item.work_phone.length > 0) {
                            staffListItems +=' / <span onclick="window.location.href="tel:'+ item.work_phone +'" class="text-secondary">' + item.work_phone + '</span>';
                        }

                        if (item.secondary_phone != null && item.secondary_phone.length > 0) {
                            staffListItems +=' / <span onclick="window.location.href="tel:'+ item.secondary_phone +'" class="text-danger">' + item.secondary_phone + '</span>';
                        }
                        
                staffListItems += '</p>';
                staffListItems += '</li>';
            });

            staffList.append(staffListItems);

        }

        /*$.mobile.loading( 'hide');*/
    });
}

function updateLocalStorage(session, supervisor) {
    localStorage.setItem('session', session);
    localStorage.setItem('supervisor', supervisor);
}