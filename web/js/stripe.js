//STRIPE
var stripe = Stripe('pk_test_G0WPeLNB9kIYOIeYcOjweQv9');
var elements = stripe.elements();
var commandePrixTotal = '{{commande.commandePrixTotal}}';
var commandeCode = '{{commande.commandeCode}}'

var card = elements.create('card', {
  style: {
    base: {
      iconColor: '#666EE8',
      color: '#31325F',
      lineHeight: '40px',
      fontWeight: 300,
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSize: '15px',

      '::placeholder': {
        color: '#CFD7E0',
      },
    },
  }
});
card.mount('#card-element');

function setOutcome(result) {
  var successElement = document.querySelector('.success');
  var errorElement = document.querySelector('.error');
  successElement.classList.remove('visible');
  errorElement.classList.remove('visible');

  if (result.token) {
    document.querySelector('.token').value = result.token.id;
    successElement.classList.add('visible');
    // Submit the form
    var form = document.querySelector('form');
    form.submit();
    
  } else if (result.error) {
    errorElement.textContent = result.error.message;
    errorElement.classList.add('visible');
  }
}

card.on('change', function(event) {
  setOutcome(event);
});

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
       
    stripe.createToken(card).then(setOutcome);
});

