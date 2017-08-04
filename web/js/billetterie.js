var tmp = $;
var dp = $.datepicker;

//JSON
var totalBillets = JSON.parse(json_totalBillets);
console.log(totalBillets);

$.datepicker.setDefaults($.datepicker.regional[ "fr" ]);

$(".datepicker").datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : 0,
    maxDate : "+1Y",
    showOtherMonths: true,
    selectOtherMonths: true,
    beforeShowDay: 
    
        function disabled(date){
            
            //Récupération du nombre correspondant au jour de la semaine
            var dayNumber = date.getDay();
            
            //Récupération du jour et du mois pour chaque date à afficher
            var day = date.getDate();
            var month = date.getMonth()+1;
            
            var daysShown = dp.formatDate('dd-mm-yy', date); 
            
            //Désactivation des dimanches (0) et mardi(2) ainsi que des 01-05, 01-11 et 25-12 quelque soit l'année.
            if (dayNumber == 0 || dayNumber == 2) {
               return [false, ''];
               } else if (day == '1' & month == "5" || day == '1' & month == "11" || day == '25' & month == "12"  ){
                   return [false, ''];
                } else {
                   return [true, ''];
                   };              
        },

    onSelect: function(date){
        $('#commande_commandeDate').val(this.value);
    },    
});



