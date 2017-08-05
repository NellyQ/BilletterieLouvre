var tmp = $;
var dp = $.datepicker;

$.datepicker.setDefaults($.datepicker.regional['fr']);

$(".datepicker").datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : 0,
    maxDate : "+1Y",
    showOtherMonths: true,
    selectOtherMonths: true,
    beforeShowDay: 
    
        function disabled(date) {
            
            //Récupération du nombre correspondant au jour de la semaine
            var dayNumber = date.getDay();
            
            //Récupération du jour et du mois pour chaque date à afficher
            var day = date.getDate();
            var month = date.getMonth() + 1;
            
            //Dates affichées sur le calendrier au format dd-mm-yy
            var daysShown = dp.formatDate('dd-mm-yy',date);
            
            //Tableau contenant les dates de commandes et le total de billet vendu par jour
            var totalBillets = JSON.parse(json_totalBillets);
            
            //Boucle sur le tableau totalBillet pour comparer les dates affichées aux nombre de billets restants pour chaque date
            for (i=0; i<totalBillets.length; i++){
                var dateDispo = totalBillets[i].commandeDate
                var billetsRestants = 1000 - (totalBillets[i].nbTotal);
                
                //Désactivation des dimanches (0) et mardi(2) ainsi que des 01-05, 01-11 et 25-12 quelque soit l'année.
                if (dayNumber == 0 || dayNumber == 2) {
                    return [false, '']; 
                } else if (day == '1' & month == "5" || day == '1' & month == "11" || day == '25' & month == "12"  ) {
                    return [false, ''];
                
                } else if ((daysShown == dateDispo)&&(billetsRestants==0)){
                    return [false, 'indispo'];
                    
                } else if ((daysShown == dateDispo)&& ((billetsRestants <=10)&&( billetsRestants>0))){
                    return [true, 'limit'];
    
                } else {}
            }
            return [true, 'dispo'];
        },
    
    onSelect: function (date) {
        $('#commande_commandeDate').val(this.value);
    } 
       
});

