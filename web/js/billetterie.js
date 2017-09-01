//Tableau contenant les dates de commandes et le total de billet vendu par jour
var totalBillets = JSON.parse(json_totalBillets);
var now = new Date();
var nowyears = now.getFullYear();
var nowmonth = now.getMonth() + 1;
var nowday = now.getDate();

if (nowday<10) {
    nowdayFormat = "0" + nowday;
    } else { nowdayFormat = nowday;
    };

if (nowmonth<10) {
    nowmonthFormat = "0" + nowmonth;
    } else { nowmonthFormat = nowmonth;
    };

var nowFormat = nowdayFormat + "-" + nowmonthFormat + "-" + nowyears;

var heure = now.getHours();

$.datepicker.setDefaults($.datepicker.regional['fr']);

$(".datepicker").datepicker({
    dateFormat: 'dd-mm-yy',
    minDate: 0,
    maxDate: "+1Y",
    defaultDate: "+1d",
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
        var daysShown = $.datepicker.formatDate('dd-mm-yy', date);


        //Boucle sur le tableau totalBillet pour comparer les dates affichées aux nombre de billets restants pour chaque date
        for (i = 0; i < totalBillets.length; i++) {
            var dateDispo = totalBillets[i].commandeDate;
            var billetsRestants = 1000 - (totalBillets[i].nbTotal);

            //Désactivation des dimanches (0) et mardi(2) ainsi que des 01-05, 01-11 et 25-12 quelque soit l'année.
            if (dayNumber == 0 || dayNumber == 2) {
                return [false, ''];
            } else if ((day == '1' && month == "5") || (day == '1' && month == "11") || (day == '25' && month == "12")) {
                return [false, ''];
            
            //Désactivation et ajout d'une classe indispo si le nombre de billet déjà vendu est de 1000
            } else if ((daysShown == dateDispo) && ((billetsRestants == 0) || (billetsRestants < 0))) {
                return [false, 'indispo'];
            
            //Ajout d'une classe limit si le nombre de billet déjà vendu est compris entre 990 et 999
            } else if ((daysShown == dateDispo) && ((billetsRestants <= 10) && (billetsRestants > 0))) {
                return [true, 'limit'];

            } else {}
        }
        return [true, 'dispo'];
    },

    onSelect: function (date) {
        $('#billetsRestants').html("");
        $('#commande_commandeDate').val(this.value);

        for (i = 0; i < totalBillets.length; i++) {
            var dateDispo = totalBillets[i].commandeDate;
            var billetsRestants = 1000 - (totalBillets[i].nbTotal);
            
            //Si le nombre de billet restant est compris entre 1 et 10: ajout d'une phrase spécifiant le nombre de billet restant et limitation du nombre de billets réservables.
            if ((this.value == dateDispo) && ((billetsRestants <= 10) && (billetsRestants > 0))) {
                $('#billetsRestants').text("Il reste " + billetsRestants + " billet(s) disponible(s) pour cette date");
                $('#commande_commandeNbBillet').attr('max', billetsRestants);
            }
        }

        //Desactivation du bouton radio Journee si date selectionnée = aujourd'hui et heure > 14h00
        if (date == nowFormat && heure >= 14) {
            $('input[value="Journee"]').attr('disabled', "disabled");
            $('input[value="Journee"]').removeAttr('checked');
            $('input[value="Demi-journee"]').attr('checked', "checked");
        } else {
            $('input[value="Journee"]').removeAttr('disabled');
            $('input[value="Demi-journee"]').removeAttr('checked');
        }
    }
});
