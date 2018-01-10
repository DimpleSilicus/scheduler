    

$(document).ready(function () {
//    console.log('hello');
});

//For appending new time row on click of plus button.
function viewNaicsResponse()
{
    var NaicsCode = $("#NaicsCode").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/naicsdata',
        type: 'POST',
        data: {NaicsCode: NaicsCode},
        success: function (data) {
            console.log(data);
            if (data)
            {
//                location.reload();
//                var jsonStr = $("pre").text();
                var jsonObj = JSON.parse(data);
                var jsonPretty = JSON.stringify(jsonObj, null, '\t');

                $("pre").text(jsonPretty);
//                $('#DynamicData').append(data);
                $('pre').css('display', 'block');
            }
        },
        error: function (data) {
//            console.log(data);
            var obj = jQuery.parseJSON(data.responseText);
            console.log(obj['message']);
            if (data.responseText != '')
            {
                $('.flash-message').html('<p class="alert alert-danger">' + obj['message'] + ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
            }
        }
    });

}

 