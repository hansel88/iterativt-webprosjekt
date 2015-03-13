registerBooking = function(){

  var opts = {
    url: 'php/sendConfirmationMail.php', // send ajax-request til denne filen
    type: 'POST', // http-verb
    data : { mailInput : 'roomNumber', roomNumber : 'foo' },
  };

  $.when($.ajax(opts)).then(function() {
    // suksess - dvs 200 i respons fra server
    alert('hurra!');
});
   // window.location.href="php/search.php";
  }, function() {
    // epic fail - dvs 500 i respons fra server
    alert('oj, noe gikk feil');
  });
}