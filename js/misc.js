 $('#searchForm').on('submit', function(e) {
    e.preventDefault();

  // stopp formet i å bli submitted


  // en referanse til form-taggen
  var $form = $(this);

  // lag et options-objekt som gir info til $.ajax etterpå
  var opts = {
    url: 'php/registerBooking.php', // send ajax-request til denne filen
    type: 'POST', // http-verb
    data: $form.serialize() // serialiser skjemaet og legg det i post-bodyen
  };
console.log($form.serialize());

  $.when($.ajax(opts)).then(function() {
    // suksess - dvs 200 i respons fra server
    alert('hurra, vi har fått mailen din!');
    $( '#searchForm' ).each(function(){
    this.reset();
});
  }, function() {
    // epic fail - dvs 500 i respons fra server
    alert('oj, noe gikk feil - skrev du feil epostadresse?')
  });
});