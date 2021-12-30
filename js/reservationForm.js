const search = '?r=reservation/ajaxcalc';
const url = window.location.origin + window.location.pathname + search;

$('#Reservation_endTime').add($('#Reservation_startTime')).change(() => {
    let data = {
        ajaxRoomName: $('#Reservation_roomName').val(),
        ajaxStartTime: $('#Reservation_startTime').val(),
        ajaxEndTime: $('#Reservation_endTime').val(),
    }

    $.post(url,data,function(result){
        $('#Reservation_totalAmount').val(result);
    });
});