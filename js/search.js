
  var start;
  var end;

  testMethod = function(time, hours)
  {
    for(var i = 0; i < hours; i++)
    {
      if($('#timeInput' + (time + i)).hasClass('redTime'))
      {
         $('#infoText').text('Kan ikke reservere ' + hours + ' timer fra valgt starttidspunkt.');
         return;
      }
    }
    if((time + hours - 1) > 20)
    {
      $('#infoText').text('Du har valgt tidsrommet ' + time + ' - ' + '21');
      start = time;
      end = 21;

    }
    else
    {
      //document.getItemById("infoText").innerHTML='Hello';
      //$('#chooseRoomSubmit').css('color','red');
       $('#infoText').text('Du har valgt tidsrommet ' + time + ' - ' + (time + hours));
        start = time;
        end = (time + hours);
    }
  }


  book = function()
  {
    $.ajax({
      type: "POST",
      url: "sendConfirmationMail.php",
      data: {startTime : lbtest},
      success: function(data)
      {
          alert("Successful");
      }
});
/*
    if($('#mailForm').is(":visible"))
    {
      alert('visible');
    }
    else
    {
      $('#mailForm').show();
    }
    */
    /*
    if(start !== null && end !== null && start < end)
    {
        request = $.ajax({
          url: "/sendConfirmationMail.php",
          type: "post",
          data: 
        });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        console.log("Hooray, it worked!");
    });

    }
    else
    {
      alert('Du må velge tidsrommet du vil booke!');
    }
    */
  }
  

  showError = function()
  {
    $('#infoText').text('Ugyldig tidspunkt. Prøv de grønne boksene..');
  }