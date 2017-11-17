

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
                    if (obj.errors.schedulerDateMultiple) {
                        alert(obj.errors.schedulerDateMultiple[0]);
                        $("#addScheduler #schedulerDateMultiple-div").addClass("has-error");
                        $('#addScheduler #form-errors-schedulerDateMultiple').html(obj.errors.schedulerDateMultiple[0]);
                    }
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
        
        if('' == $(modalName+" #schedulerDateMultiplenew").val()) return false;
        
        var time = $(modalName+" #schedulerDateMultiplenew").val();
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
function GetSchedulerTime(scheduler_interval,type)
{
//    var type=$('#type').val();    
    if (scheduler_interval == 'daily')
    {
//        $('#schedulerDate').css('display', 'block');
        var html;
        if(type=='add')
        {
            html= getTime();
            $('#schedulerDate').html(html);
        }
        else
        {
            html= getTime('editTime-ico-plus');
            $('#schedulerDateU').html(html);
        }
    }
    //This is in progress Code
    else if (scheduler_interval == 'weekly')
     {
         
        var daysOfWeekHtml = gernerateDayofWeek();
        var timeHtml;
        if(type=='add')
        {
            var timeHtml=getTime();
            $('#schedulerDate').html(daysOfWeekHtml);
            $('#schedulerDate').append(timeHtml);
        }
        else
        {
            var timeHtml=getTime('editTime-ico-plus');
            $('#schedulerDateU').html(daysOfWeekHtml);
            $('#schedulerDateU').append(timeHtml);
        }
        
   //     $('#DynamicData').css('display', 'block');
     } 
     
     else if (scheduler_interval == 'monthly')
     {
        var month;
//        var daysOfWeekHtml = gernerateDayofMonth();
        if(type=='add')
        {
            var month =generateMonth('add');
            $('#schedulerDate').html(month);
        }
        else
        {
            var month =generateMonth('edit');
            $('#schedulerDateU').html(month);
        }
        
     }
}

$('#date').click(function () {
//    alert('date');
    $("#date").datepicker({
//        changeMonth: true,
//        changeYear: true
    });
});

//Function is to delete perticuler scheduler.
function deleteScheduler(schedulerid)
{
    if (window.confirm("Are you sure you want to delete this scheduler?"))
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/deleteScheduler',
            type: 'POST',
            data: {schedulerId: schedulerid},
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

    $("#editSchedulerPopup #eventRows").html('');
    $('#editSchedulerPopup').modal('show');    
    var schedulerId = $(this).attr("schedulerId");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/getSchedulerDetails',
        type: 'POST',
        data: {schedulerId: schedulerId},
        success: function (data) {
            console.log(data);
            $.each(data["daily_schedular"],function(i, value){   
                var row=addTimeRow(i, value.time);
                $("#editSchedulerPopup #eventRows").append(row);
            });
            if(data['schedular'].interval=='weekly')
            {
                 var daysHtml= gernerateDayofWeek(data["scheduler_interval"]);
                $("#editSchedulerPopup #eventRows").append(daysHtml);
            }
            else if(data['schedular'].interval=='monthly')
            {
                var key;
                var monthHtml= generateMonth('edit',data["scheduler_interval"]);
                $.each(data["scheduler_interval"],function(keys,value){                    
                    key=keys;
                });
                var daysOfMonthHtml=gernerateDayofMonth(data["scheduler_interval"][key]['month'],'edit',data["scheduler_interval"]);
                $("#editSchedulerPopup #eventRows").append(monthHtml);
                $("#editSchedulerPopup #eventRows").append(daysOfMonthHtml);
            }
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
function getTime(id)
{
    var addclass;
    //For addDateTime-ico-plus
    if(typeof id != 'undefined')
    {        
        addclass=id;
    }
    //For editTime-ico-plus
    else
    {
        addclass='addDateTime-ico-plus';
    }
    var html='<h5>Scheduler Time *</h5><div class="row"><div class="col-sm-6"><input type="text" class="form-control" id="schedulerDateMultiplenew" name="schedulerDateMultiplenew[]" value="" required/><span class="form-highlight"></span><span class="form-bar"></span><span class="text-danger" id="schedulerDateMultiple-div"><strong id="form-errors-schedulerDateMultiple"></strong></span></div><div class="col-sm-1"><!--plus button come here--><a href="#" id="'+addclass+'" class="table-icon"><span class="glyphicon glyphicon-plus"></span></a></div></div><div id="eventRows"></div>';
    return html;
}
//This is in progress Code
function gernerateDayofWeek(interval)
 {
    var daysOfWeek = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var html ='<div class="checkbox">';
    var selectedDays = [];
    var keys=[];
    $.each(interval,function(key,value){
            selectedDays[key] = value['s_day']; 
            keys[value['s_day']]=key;
        });
        
    for(var i=0;i<7;i++)
    {
        console.log(selectedDays[keys[daysOfWeek[i]]]);
        console.log(daysOfWeek[i]);
        if (typeof selectedDays[keys[daysOfWeek[i]]] !== "undefined" && daysOfWeek[i] == selectedDays[keys[daysOfWeek[i]]]) {
            //Already exist    
            html+='<label><input checked type="checkbox" name="dayEdit['+keys[daysOfWeek[i]]+']" id="day_'+daysOfWeek[i]+'" value="'+daysOfWeek[i]+'">'+daysOfWeek[i]+'</label>';
        }
        else
        {
            html+='<label><input type="checkbox" name="day[]" id="day_'+daysOfWeek[i]+'" value="'+daysOfWeek[i]+'">'+daysOfWeek[i]+'</label>';
        }
    }
    html+='</div>';
    return html;
 }
 
 function generateMonth(flag,interval)
 {
    var monthid=1;
    var selectedDays = [];
    var keys=[];
    $.each(interval,function(key,value){
            selectedDays[key] = value['month'];
            keys[value['month']]=key;
    });
    console.log(selectedDays);
    var html='<select name="month" id="month" onchange="gernerateDayofMonth(this.value,\''+flag+'\')">';     
    var theMonths = ["January", "February", "March", "April", "May","June", "July", "August", "September", "October", "November", "December"];
    for (var i=0; i<theMonths.length; i++,monthid++) {
    
    if (typeof selectedDays[keys[monthid]] !== "undefined" && monthid == selectedDays[keys[monthid]]) {
        html+= '<option selected value="'+monthid+'">'+theMonths[i]+'</option>';
    }
    else
    {
        html+= '<option value="'+monthid+'">'+theMonths[i]+'</option>';
    }

    }
    html+='</select>';
    return html;
 }
 
 
function gernerateDayofMonth(month,flag,interval)
{    
//    alert("flag::"+flag+"::int::"+interval);
    var dayid=1;
    var date = new Date();
    var year=date.getFullYear();
    var totalDay=new Date(year, month, 0).getDate();
    
    var html ='<div id="schedulermonth"><div class="checkbox">';
    var selectedDays = [];
    
    $.each(interval,function(key,value){
            selectedDays[value['s_day']] = value['s_day'];            
    });
        
    for(var i=0;i<totalDay;i++,dayid++)
    {
        $('#schedulermonth').remove();
        if (typeof selectedDays[dayid] !== "undefined" && dayid == selectedDays[dayid]) {
            html+='<label>&nbsp;&nbsp;&nbsp;<input checked type="checkbox" name="dayEdit[]" id="day_'+dayid+'" value="'+dayid+'">'+dayid+'</label>';
        }
        else
        {
           html+='<label>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="day[]" id="day_'+dayid+'" value="'+dayid+'">'+dayid+'</label>';
        }
    }
    html +='</div>';
    if(typeof interval === "undefined" && flag === 'add')
    {        
        var timeHtml=getTime();
        html+=   timeHtml;
        $('#schedulerDate').append(html);
    }
    if(typeof interval === "undefined" && flag === 'edit')
    {         
        var timeHtml=getTime('editTime-ico-plus');
        html+=   timeHtml;
        $("#editSchedulerPopup #schedulerDateU").append(html);
    }
    else if(flag === 'edit')
    {
//        alert('else');
//         $('#schedulermonth').append(html);
        return html;
    }
}