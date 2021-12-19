
// $(document).ready(() => {
   
$('#Reservation_endTime').change(() => {
    let data = {
        ajaxStartTime: $('#Reservation_startTime').val(),
        ajaxEndTime: $('#Reservation_endTime').val(),
    }

    $.post('http://localhost/booking/index.php/reservation',data,function(result,status){
        console.log(result)
    });
});

// let startTimeValue = '';
// let endTimeValue = '';
// let totalAmountValue = $('#Reservation_totalAmount').value;

// function formatValue(value) {
//     let format = value.replace(':','') + '00';
//     return +format;
// }

// $('#Reservation_startTime').change((e) => {
//     startTimeValue = formatValue(e.target.value);
// });

// $('#Reservation_endTime').change((e) => {
//     endTimeValue = formatValue(e.target.value);
//     console.log(startTimeValue + endTimeValue);
// });
// });