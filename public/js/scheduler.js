

$(document).ready(function () {
//    console.log('hello');

    $('#checkbox1').mousedown(function () {
        if (!$(this).is(':checked')) {
            this.checked = confirm("Are you sure?");
            $(this).trigger("change");
        }
    });

    //This is used to add new scheduler.
    var addSchedulerForm = $("#frmAddScheduler");
    addSchedulerForm.submit(function (e) {
        e.preventDefault();
        var formData = addSchedulerForm.serialize();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/add_scheduler',
            dataType: "json",
            type: 'POST',
            data: formData,
//            data: formData,            
            success: function (data) {
                if (data)
                {
                    $("#addScheduler").modal('hide');
                    location.reload();
                } else
                {
                    //If not get proper responce.
                    location.reload();
                }
            },
            error: function (data) {
                console.log(data);
                if (data.responseText != '')
                {
//                    console.log(data);
                    var obj = jQuery.parseJSON(data.responseText);
//                            console.log(obj.errors.schedulerDateMultiple['profile']);
                    if (obj.errors.schedulerName) {
                        $("#addScheduler #schedulerName-div").addClass("has-error");
                        $('#addScheduler #form-errors-schedulerName').html(obj.errors.schedulerName[0]);
                    }
                    if (obj.errors.schedulerType) {
                        $("#addScheduler #schedulerType-div").addClass("has-error");
                        $('#addScheduler #form-errors-schedulerType').html(obj.errors.schedulerType[0]);
                    }
                    if (obj.errors.schedulerInterval) {
                        $("#addScheduler #schedulerInterval-div").addClass("has-error");
                        $('#addScheduler #form-errors-schedulerInterval').html(obj.errors.schedulerInterval[0]);
                    }
                    if (obj.errors.scheduleTemplate) {
                        $("#addScheduler #scheduleTemplate-div").addClass("has-error");
                        $('#addScheduler #form-errors-scheduleTemplate').html(obj.errors.scheduleTemplate[0]);
                    }
//                    if (obj.errors.schedulerDateMultiple) {
//                        alert(obj.errors.schedulerDateMultiple[0]);
//                        $("#addScheduler #schedulerDate-div").addClass("has-error");
//                        $('#addScheduler #form-errors-schedulerDate').html(obj.errors.schedulerDateMultiple[0]);
//                    }
                    if (obj.errors.schedulerDate) {
                        alert(obj.errors.schedulerDate[0]);
                        $("#addScheduler #schedulerDate-div").addClass("has-error");
                        $('#addScheduler #form-errors-schedulerDate').html(obj.errors.schedulerDate[0]);
                    }
                }
            }
        });
    });

    //For Edit scheduler details.
    var editSchedulerForm = $("#frmEditScheduler");
    editSchedulerForm.submit(function (e) {
        e.preventDefault();
        var formData = editSchedulerForm.serialize();
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/edit_scheduler',
            dataType: "json",
            type: 'POST',
            data: formData,
//            data: formData,            
            success: function (data) {
                if (data)
                {
                    $("#editScheduler").modal('hide');
                    location.reload();
                } else
                {
                    //If not get proper responce.
                    location.reload();
                }
            },
            error: function (data) {
                console.log(data);
                if (data.responseText != '')
                {
                    console.log(data);
                    var obj = jQuery.parseJSON(data.responseText);                    
                    if (obj.errors.schedulerName) {
                        $("#editSchedulerPopup #schedulerNameU-div").addClass("has-error");
                        $('#editSchedulerPopup #form-errors-schedulerNameU').html(obj.errors.schedulerName[0]);
                    }
                    if (obj.errors.schedulerType) {
                        $("#editSchedulerPopup #schedulerTypeU-div").addClass("has-error");
                        $('#editSchedulerPopup #form-errors-schedulerTypeU').html(obj.errors.schedulerType[0]);
                    }
                    if (obj.errors.schedulerInterval) {
                        $("#editSchedulerPopup #schedulerIntervalU-div").addClass("has-error");
                        $('#editSchedulerPopup #form-errors-schedulerIntervalU').html(obj.errors.schedulerInterval[0]);
                    }
                    if (obj.errors.scheduleTemplate) {
                        $("#editSchedulerPopup #scheduleTemplateU-div").addClass("has-error");
                        $('#editSchedulerPopup #form-errors-scheduleTemplateU').html(obj.errors.scheduleTemplate[0]);
                    }
                    if (obj.errors.schedulerDate) {
                        
                        $("#editSchedulerPopup #schedulerDate-div").addClass("has-error");
                        $('#editSchedulerPopup #form-errors-schedulerDate').html(obj.errors.schedulerDate[0]);
                    }
                }
            }
        });

    });
    
    //For adding multiple time for daily scheduler.
    $(document).on("click", "#addDateTime-ico-plus", function() {
		manageEvent("#addScheduler");
	});
    
    //For editing multiple time for daily scheduler.
    $(document).on("click", "#editTime-ico-plus", function() {
		manageEvent("#editSchedulerPopup");
	});
        
    function manageEvent(modalName){
        if('' == $(modalName+" #schedulerDateMultiple").val()) return false;
        
        var time = $(modalName+" #schedulerDateMultiple").val();
        var row = addTimeRow('', time);
        if(time == '')
        {
            return false;
        }
        else
        {
            $(modalName+" #eventRows").append(row);
        }
    }
    
    //For validating checkbox field.
//    $('#frmAddScheduler').validate({ // initialize the plugin
//        rules: {
//            'day[]': {
//                required: true,
//                maxlength: 2
//            }
//        },
//        messages: {
//            'day[]': {
//                required: "You must check at least 1 day",
//                maxlength: "Check no more than {0} boxes"
//            }
//        },
//        submitHandler: function (form) { // for demo
//            alert('valid form submitted'); // for demo
//            return false; // for demo
//        }
//    });
    
});

//For appending new time row on click of plus button.
function addTimeRow(id, time)
{
    var row= '';
    //For Edit
    if(id != '')
    {
        row+='<div class="row"><div class="col-sm-6"><input type="text" class="form-control" id="schedulerDateMultipleEdit" name="schedulerDateMultipleEdit['+id+']" value="'+time+'" required/><span class="form-highlight"></span><span class="form-bar"></span><span class="text-danger" id="schedulerDate-div"><strong id="form-errors-schedulerDate"></strong></span></div><div class="col-sm-1"><a href="#" id="deleteDateTime" class="table-icon "><span class="glyphicon glyphicon-minus"></span></a></div></div>';
    }
    //For add new row.
    else
    {
        row+= '<div class="row"><div class="col-sm-6"><input type="text" class="form-control" id="schedulerDateMultiple" name="schedulerDateMultiple[]" value="'+time+'" required/><span class="form-highlight"></span><span class="form-bar"></span><span class="text-danger" id="schedulerDate-div"><strong id="form-errors-schedulerDate"></strong></span></div><div class="col-sm-1"><a href="#" id="deleteDateTime" class="table-icon "><span class="glyphicon glyphicon-minus"></span></a></div></div>';
    }
    return row;
    
}

    $(document).on("click", "#deleteDateTime", function() {
        $(this).parent().parent().remove();
    });
        
//Function is to display template drop down on selecting scheduler type.
function showTemplate(schedule_type)
{
    var option = '';
//    var formData = $("#frmAddScheduler").serialize();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/get_template',
        type: 'POST',
//        data: formData,
        success: function (data) {

            for (var i = 0; i < data.length; i++) {
                option += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
            }
            $('#Template').html('<select class="form-control has-info" id="scheduleTemplate" name="scheduleTemplate" placeholder="Placeholder" required>' + option + '</select>');
            $('#Template').css('display', 'block');
        },
        error: function (data) {
            var obj = jQuery.parseJSON(data.responseText);
            console.log(obj);
        }
    });
}
function GetSchedulerTime(scheduler_interval)
{
    if (scheduler_interval == 'daily')
    {
//        $('#schedulerDate').css('display', 'block');
        var html= getTime();
        $('#schedulerDate').html(html);
    }
    //This is in progress Code
    else if (scheduler_interval == 'weekly')
     {
     alert('weekly');
     var daysOfWeekHtml = gernerateDayofWeek();
     var timeHtml=getTime();
     $('#schedulerDate').html(daysOfWeekHtml);
     $('#schedulerDate').append(timeHtml);
//     $('#DynamicData').css('display', 'block');
     } 
     
     /*else if (scheduler_interval == 'monthly')
     {
     alert('monthly');
     var daysOfWeekHtml = gernerateDayofMonth();
     $('#schedulerDate').html(daysOfWeekHtml);
     }*/
}

$('#schedulerDate').click(function () {
    $("#schedulerDate").datepicker({
        changeMonth: true,
        changeYear: true
    });
});

//Function is to delete perticuler scheduler.
function deleteScheduler()
{
    var schedulerId = $("#schedulerId").val();

    if (window.confirm("Are you sure you want to delete this scheduler?"))
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/deleteScheduler',
            type: 'POST',
            data: {schedulerId: schedulerId},
            success: function () {
                location.reload();
            },
            error: function (data) {
                console.log(data);
            }
        });
    } else
    {
        return false;
    }
}

//Function is to get scheduler details.
$(document).on("click", "#editScheduler", function () {
//function editScheduler()
//{
    
    $('#editSchedulerPopup').modal('show');
//    var schedulerId = $("#schedulerId").val();
    var schedulerId = $(this).attr("schedulerId");
//    alert('editScheduler'+schedulerId);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/getSchedulerDetails',
        type: 'POST',
        data: {schedulerId: schedulerId},
        success: function (data) {
            console.log(data);
            $.each(data["daily_schedular"],function(i, value){   
                var row=addTimeRow(i, value.time_of_day);
                $("#editSchedulerPopup #eventRows").append(row);
            });
            
            $("#editSchedulerPopup #schedulerNameU").val(data['schedular'].name);
            $("#editSchedulerPopup #schedulerTypeU").val(data['schedular'].type);
            //Not showing which template is selected for now. i will fix it later.
            $("#editSchedulerPopup #scheduleTemplate").val(data['schedular'].temp_name);
            $("#editSchedulerPopup #schedulerIntervalU").val(data['schedular'].interval);
            $("#editSchedulerPopup #schedulerFromDateU").val(data['schedular'].start_date);
            $("#editSchedulerPopup #schedulerToDateU").val(data['schedular'].end_date);
            $("#editSchedulerPopup #schedulerId").val(data['schedular'].id);
//                location.reload();
        },
        error: function (data) {
            console.log(data);
        }
    });
//}
});

//Function is to get common time text field
function getTime()
{
    var html='<h5>Scheduler Time *</h5><div class="row"><div class="col-sm-6"><input type="text" class="form-control" id="schedulerDateMultiple" name="schedulerDateMultiple[]" value="" required/><span class="form-highlight"></span><span class="form-bar"></span><span class="text-danger" id="schedulerDate-div"><strong id="form-errors-schedulerDate"></strong></span></div><div class="col-sm-1"><!--plus button come here--><a href="#" id="addDateTime-ico-plus" class="table-icon"><span class="glyphicon glyphicon-plus"></span></a></div></div><div id="eventRows"></div>';
    return html;
}
//This is in progress Code
function gernerateDayofWeek()
 {
 var daysOfWeek = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
 var html ='<div class="checkbox">';
 for(i=0;i<7;i++)
 {
 html+='<label><input type="checkbox" name="day[]" id="day_'+daysOfWeek[i]+'" value="'+daysOfWeek[i]+'" onchange="StoreDaysOfWeek(\''+daysOfWeek[i]+'\')">'+daysOfWeek[i]+'</label>';
 }
 html+='</div>';
 return html;
 }
 /*
 function gernerateDayofMonth()
 {
 // Since no month has fewer than 28 days
 var date = new Date(2017, 10, 1);
 var days = [];
 console.log('month', 10, 'date.getMonth()', date.getMonth());
 while (date.getMonth() === 10) {
 days.push(new Date(date));
 date.setDate(date.getDate() + 1);
 }
 console.log(days);
 }
 */
 function StoreDaysOfWeek(day)
 {
    alert(day);
    var WeekDays=[];
    var html ='';

    WeekDays.push(day);
    alert(WeekDays);
    html+='<input type="hidden" name="WeekDays" value="'+WeekDays+'">';
    
    alert(html);
 }


