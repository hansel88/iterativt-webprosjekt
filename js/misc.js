
/*
 $('#searchForm').on('submit', function(e) {
    e.preventDefault();

  // stopp formet i å bli submitted


  // en referanse til form-taggen
  var $form = $(this);

  // lag et options-objekt som gir info til $.ajax etterpå
  var opts = {
    url: 'php/search.php', // send ajax-request til denne filen
    type: 'POST', // http-verb
    data: $form.serialize() // serialiser skjemaet og legg det i post-bodyen
  };
console.log($form.serialize());
//header('Location: php/registerBooking.php');


  $.when($.ajax(opts)).then(function() {
    // suksess - dvs 200 i respons fra server
    $( '#searchForm' ).each(function(){
    this.reset();
});
   // window.location.href="php/search.php";
  }, function() {
    // epic fail - dvs 500 i respons fra server
    alert('oj, noe gikk feil. Fylte du inn alle feltene?')
  });
 
});

*/