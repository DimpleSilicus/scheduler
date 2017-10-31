

$(document).ready(function () {
	console.log('hello');
        
        $('#checkbox1').mousedown(function() {
        if (!$(this).is(':checked')) {
            this.checked = confirm("Are you sure?");
            $(this).trigger("change");
        }
    });
    
    var addSchedulerForm = $("#frmUploadPicture");
	addSchedulerForm.submit(function(e) {
		e.preventDefault();		
		var formData = addSchedulerForm.serialize();
		$.ajax({
	    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	    	url: '/add_scheduler',
	        type: 'POST',
	        data: formData,
	        success: function(data) {
                    console.log('dsfds');
	        	//$("#addScheduler").modal('hide');	        	
	        	//location.reload(); 
	        },
	        error: function(data) {      
	        		var obj = jQuery.parseJSON(data.responseText);	   
//		        	 if (obj.familyName) {
//			        	$("#addFamilyModal #famName-div").addClass("has-error");
//		            	$('#addFamilyModal #form-errors-famName').html(obj.familyName);
//		        	 }
	        }
	    });
	});
    
});



function addScheduler()
{
    alert('addScheduler');
}

function GetSchedulerTime(scheduler_type)
{
//    var html='';
    alert("type:::"+scheduler_type);
    if(scheduler_type== 'daily')
    {
        alert('daily');
        $('#schedulerDate').append('<div class="input-group"><input type="text" class="form-control" id="schedulerDate" name="schedulerDate" /><span class="form-highlight"></span><span class="form-bar"></span><label class="hasdrodown" for="personDob">Date</label><label class="input-group-addon modal-datepicker-ico" for="schedulerDate"><span class="glyphicon glyphicon-th"></span></label><span class="text-danger" id="personEvents-div"><strong id="form-errors-personEvents"></strong></div>');
    }
    else if(scheduler_type == 'weekly')
    {
        alert('weekly');
        var daysOfWeekHtml=gernerateDayofWeek();
        $('#schedulerDate').html(daysOfWeekHtml);
    }
    else if(scheduler_type == 'monthly')
    {
        alert('monthly');
        var daysOfWeekHtml=gernerateDayofMonth();
        $('#schedulerDate').html(daysOfWeekHtml);
    }
}

/*function gernerateDayofWeek()
{
    var daysOfWeek = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var html ='<div class="checkbox">';
    for(i=0;i<7;i++)
    {
        html+='<label><input type="checkbox" name="day_'+daysOfWeek[i]+'" id="day_'+daysOfWeek[i]+'" value="'+daysOfWeek[i]+'" onchange="StoreDaysOfWeek("'+daysOfWeek[i]+'")">'+daysOfWeek[i]+'</label>';
    }
    html+='</div>';
    return html;
}

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

function StoreDaysOfWeek(day)
{
    alert(day);
    var WeekDays=[];
    var html ='';
    
    WeekDays.push(day);
    alert(WeekDays);
    html+='<input type="hidden" name="WeekDays" value="'+WeekDays+'">';
}*/

//$(function() {
    $('#schedulerDate').click(function() {
        $( "#schedulerDate").datepicker({
      changeMonth: true,
      changeYear: true
    });
    });
    
//  });