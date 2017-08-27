var $collectionHolder;

$(document).ready(function () {

    // setup an "add a tag" link
    var $newLinkLi = $('<li></li>').append();

    $collectionHolder = $('ul.details');

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    addDetailForm($collectionHolder, $newLinkLi);

    //recalcul du prix total si modification du formulaire
    $("input[name^='global']").change(function () {
        var prixTotal = 0;
        for (l = 0; l < commandeNbBillets; l++) {
            inputPrixInd = $('.global_details_' + l + '_visitorAge').html();
            if (inputPrixInd == "0,00 €") {
                prixInd = 0;
            } else if (inputPrixInd == "8,00 €") {
                prixInd = 8;
            } else if (inputPrixInd == "16,00 €") {
                prixInd = 16;
            } else if (inputPrixInd == "12,00 €") {
                prixInd = 12;
            } else if (inputPrixInd == "10,00 €") {
                prixInd = 10;
            }
            prixTotal += prixInd;

            $('#global_commandePrixTotal').val(prixTotal);
            $('#global_commandePrixTotal').html(prixTotal);
            $('.prixTotal').html(prixTotal + ",00 €");
        }
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
        var $newFormLi = $('<li></li>').append("<h2> Renseignements visiteur " + (i + 1) + " : </h2>" + newForm + "<div class='prixBilletInd'>Prix du billet visiteur " + (i + 1) + " : <span type='text'  class='global_details_" + i + "_visitorAge global_details_" + i + "_visitorReduc'></span></div><div class='global_details_" + i + "_visitorReduc'></div>");
        $newLinkLi.before($newFormLi);


        //Affichage du datepicker
        $.datepicker.setDefaults({
            yearRange: 60,
            defaultDate: -365 * 20
        });

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
                //Calcul du prix individuel selon l'âge
                if (age < 4) {
                    $('.' + j).html('0,00 €');
                } else if (age >= 4 && age < 12) {
                    $('.' + j).html('8,00 €');
                } else if (age >= 12 && age < 60) {
                    $('.' + j).html('16,00 €');
                } else if (age >= 60) {
                    $('.' + j).html('12,00 €');
                };

                prixAvantReduc = $('.' + j).html();

                //Calcul du prix total
                var prixTotal = 0;
                for (l = 0; l < commandeNbBillets; l++) {
                    inputPrixInd = $('.global_details_' + l + '_visitorAge').html();
                    if (inputPrixInd == "0,00 €") {
                        prixInd = 0;
                    } else if (inputPrixInd == "8,00 €") {
                        prixInd = 8;
                    } else if (inputPrixInd == "16,00 €") {
                        prixInd = 16;
                    } else if (inputPrixInd == "12,00 €") {
                        prixInd = 12;
                    } else if (inputPrixInd == "10,00 €") {
                        prixInd = 10;
                    }

                    prixTotal += prixInd;

                    $('#global_commandePrixTotal').val(prixTotal);
                    $('#global_commandePrixTotal').html(prixTotal);
                    $('.prixTotal').html(prixTotal + ",00 €");
                }
            },
        });

        //Application du tarif réduit
        caseCoche = $('#global_details_' + i + '_visitorReduc');
        caseCoche.change(function () {
            var k = this.id;
            if (this.checked) {
                $('div.' + k).html('Veuillez apporter un justificatif(carte édudiante, militaire,...) le jour de la visite.');
                $('span.' + k).html('10,00 €');
            } else {
                $('div.' + k).empty();
                $('span.' + k).html(prixAvantReduc);
            };
        });
    }
};
