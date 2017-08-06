var tmp = $;
var dp = $.datepicker;

//Tableau contenant les dates de commandes et le total de billet vendu par jour
var totalBillets = JSON.parse(json_totalBillets);

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
        $('#billetsRestants').html("");
        $('#commande_commandeDate').val(this.value);
        
        for (i=0; i<totalBillets.length; i++){
                var dateDispo = totalBillets[i].commandeDate
                var billetsRestants = 1000 - (totalBillets[i].nbTotal);
            if ((this.value == dateDispo)&&((billetsRestants <=10)&&( billetsRestants>0))){
                $('#billetsRestants').html("Il reste " +billetsRestants+ " billet(s) disponible(s) pour cette date");
                $('#commande_commandeNbBillet').attr('max', billetsRestants);
            }
        
    };
    },
       
});

//Affichage Multiple de formulaire détail

var commandeNbBillets = '{{commande.commandeNbBillet}}';
    
var $collectionHolder;

// setup an "add a tag" link
var $newLinkLi = $('<li></li>').append();

    jQuery(document).ready(function() {

    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.details');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

        addDetailForm($collectionHolder, $newLinkLi);

});
function addDetailForm($collectionHolder, $newLinkLi) {
    for (i=0;i<commandeNbBillets;i++){
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace('/__name__/g', index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);
        }};
