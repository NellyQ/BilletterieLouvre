var $collectionHolder;
var prixDeBase = 16;
var prices = [];


$(document).ready(function () {
    
    $('#renseignements').removeClass("disabled");
    $('#renseignements').addClass("active");
    $('#commande a').append(' <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
    
    for (i = 0; i < commandeNbBillets; i++) {
        //Initialisation des prix avec le prix de base
        prices.push( prixDeBase );
    };
    console.log(prices);

    // setup an "add a tag" link
    var $newLinkLi = $('<li></li>').append();

    $collectionHolder = $('ul.details');

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    addDetailForm($collectionHolder, $newLinkLi);

    //recalcul du prix total si modification du formulaire
    $("input[id^='global']").change(function () {
        calculPrixTotal();
    });
});


function addDetailForm($collectionHolder, $newLinkLi) {
    for (i = 0; i < commandeNbBillets; i++) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li
        var $newFormLi = $('<li></li>').append("<h2>"+ renseignements +" " + (i + 1) + " : </h2>" + newForm + "<div class='prixBilletInd'>"+ prixBillet +" "+ (i + 1) + " : <span type='text'  class='global_details_" + i + "_visitorAge global_details_" + i + "_visitorReduc'>16,00 €</span></div><div class='global_details_" + i + "_visitorReduc'></div>");
        $newLinkLi.before($newFormLi);
   
        //Affichage du datepicker
        $.datepicker.setDefaults({
            yearRange: 60,
            defaultDate: -365 * 20
        });
        
        $('#global_details_' + i + '_visitorAge').attr('numero',i);

        $('#global_details_' + i + '_visitorAge').datepicker({
            dateFormat: "dd-mm-yy",
            yearRange: '-100y:c+nn',
            maxDate: "-1",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            onSelect: function definePrice(date) {

                //Calcul de l'âge du visiteur
                var j = this.id;

                var visitorBirthday = $(this).val();
                visitorBirthday = visitorBirthday.split('-');

                var commandeDate = JSON.parse(json_CommandeDate);
                commandeDate = commandeDate.split('-');

                var birthDay = visitorBirthday[0];
                var birthMonth = visitorBirthday[1];
                var commandeDateDay = commandeDate[0];
                var commandeDateMonth = commandeDate[1];
                var age = commandeDate[2] - visitorBirthday[2];

                if (commandeDateMonth < birthMonth || commandeDateMonth == birthMonth && commandeDateDay < birthDay) {
                    age--;
                };
                
                l = $('#' + j).attr("numero");
                
                //Calcul du prix individuel selon l'âge
                if (age < 4) {
                    $('.' + j).html('0,00 €');
                    prices[l] = 0;
                } else if (age >= 4 && age < 12) {
                    $('.' + j).html('8,00 €');
                    prices[l] = 8;
                } else if (age >= 12 && age < 60) {
                    $('.' + j).html('16,00 €');
                    prices[l] = 16;
                } else if (age >= 60) {
                    $('.' + j).html('12,00 €');
                    prices[l] = 12;
                };

                prixAvantReduc = prices[l];
                htmlAvantReduc = $('.' + j).html();
                //Calcul du prix total
                calculPrixTotal();
            },
        });

        //Application du tarif réduit
        $('#global_details_' + i + '_visitorReduc').attr('numero',i);
        caseCoche = $('#global_details_' + i + '_visitorReduc');
        caseCoche.change(function () {
            var k = this.id;
            l = $('#' + k).attr("numero");
            if (this.checked) {
                $('div.' + k).html(justif);
                $('span.' + k).html('10,00 €');
                prices[l] = 10;
            } else {
                $('div.' + k).empty();
                $('span.' + k).html(htmlAvantReduc);
                prices[l] = prixAvantReduc;     
            };
        });      
    }
    
};

function calculPrixTotal() {
    var prixTotal = 0;
        for (l = 0; l < commandeNbBillets; l++) {
            prixTotal += prices[l];
            
            $('#global_commandePrixTotal').val(prixTotal);
            $('#global_commandePrixTotal').html(prixTotal);
            $('.prixTotal').html(prixTotal + ",00 €");
        }
};