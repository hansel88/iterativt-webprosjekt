
  var start;
  var end;
  var roomId;

  String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
  };

  testMethod = function(time, hours, room)
  {
    if(room === null || room === '')
    {
         $('#infoText').text('Noe gikk fryktelig galt :(');
    }
    else
    {
      roomId = room;
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
    

  }

  $('#mailForm').submit(function() {
    alert('sdkfdfdsa');
     $('#fromTime').val(start);
     $('#toTime').val(end);
     $('#room').val(roomId);

});


/*
  book = function(date, hours)
  {
    var _email =  $('#email').val();

    if(! _email.toLowerCase().endsWith('@student.westerdals.no'))
    {
             alert('Ugyldig epost.');
    }
    else
    {
      $.ajax({
          type: "POST",
          url: "sendConfirmationMail.php",
          data: {fromTime : start, toTime : end, date : date, room : roomId, email : _email},
          success: function(data)
          {
            window.location.href = 'sendConfirmationMail.php';
          },
        error: function (xhr, ajaxOptions, thrownError) {
          alert('Noe gikk galt :(');

      }
    });
    }
  }
  */
  

  showError = function()
  {
    $('#infoText').text('Ugyldig tidspunkt. Prøv de grønne boksene..');
  }

      /*
    $.ajax({
          type: "POST",
          url: "sendConfirmationMail.php",
          data: {startTime : start, endtime : end, date : date, hours : hours},
          success: function(data)
          {
              alert("Successful");
          }
    });
*/
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