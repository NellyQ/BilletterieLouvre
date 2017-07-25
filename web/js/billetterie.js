
    $.datepicker.setDefaults($.datepicker.regional[ "fr" ]);
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate : 0,
        maxDate : "+1Y",
        showOtherMonths: true,
        selectOtherMonths: true,
        beforeShowDay: function(date){
            var day = date.getDay();
            var bankHoliday = jQuery.datepicker.formatDate('dd-mm-yy', date);
            console.log(bankHoliday);        
            if (day == 0 || day == 2) {
	           return [false, ''];
	           } else if (bankHoliday == "27-07-2017" || bankHoliday == "01-11-2017"){
                return [false, ''];
               } else {
	            return [true, ''];
	           } 
        },
        
    }).attr("readonly","readonly").focus();
    