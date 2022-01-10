const search = '?r=reservation/ajaxcalc';
const url = window.location.origin + window.location.pathname + search;

$('#Reservation_endTime').add($('#Reservation_startTime')).add($('#Reservation_roomId')).change(() => {
    let data = {
        ajaxRoomId: $('#Reservation_roomId').val(),
        ajaxStartTime: $('#Reservation_startTime').val(),
        ajaxEndTime: $('#Reservation_endTime').val(),
    }

    if(data.ajaxEndTime && data.ajaxStartTime) {
        $.post(url,data,function(result) {
            $('#Reservation_totalAmount').val(result);
        });
    }
});