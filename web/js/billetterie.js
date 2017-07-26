var tmp = $;
var dp = $.datepicker;


    $.datepicker.setDefaults($.datepicker.regional[ "fr" ]);
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate : 0,
        maxDate : "+1Y",
        showOtherMonths: true,
        selectOtherMonths: true,
        beforeShowDay: function(date){
            var day = date.getDay();
            console.log(dp);
            var bankHoliday = dp.formatDate('dd-mm-yy', date);
            console.log(bankHoliday);
            
            if (day == 0 || day == 2) {
	           return [false, ''];
	           } else if (bankHoliday == "27-07-2017" || bankHoliday == "01-11-2017"){
                return [false, ''];
               } else {
	            return [true, ''];
	           }                
            },
        onSelect: function(date){
            $('#commande_commandeDate').val(this.value);
        },    
    })
