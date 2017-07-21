function afficherCalendrier(commandeDate)
{
    $(commandeDate).datepicker({
        dateFormat: 'dd/mm/yy',
        firstDay: 1
    });
}