
  var start;
  var end; 
  testMethod = function(k)
  {
    if(start === null)
      start = k;
    else if(start < k)
    {
      end = k;
    }
    else if(start > k)
    {
      alert('Starttidspunkt må være før sluttidspunkt');
    }
  }

  book = function()
  {
    if(start !== null && end !== null && start < end)
    {
        var opts = {
          url: 'search.php', // send ajax-request til denne filen
          type: 'POST', // http-verb
          data : { start : start, end : end }
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
    else
    {
      alert('Du må velge tidsrommet du vil booke!');
    }
  }

  showError = function()
  {
    alert('Ikke et gyldig tidspunkt');
  }

/*
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
*/