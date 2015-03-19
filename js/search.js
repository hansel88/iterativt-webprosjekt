
  var start;
  var end;
  var roomId;

  String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
  };


// Methods started for changing day - didnt get to finish these
  /*nextDay = function(_date, _hours, _size, _projector)
  { 
    var year = _date.substring(0, 4);
    var month = _date.substring(5, 7);
    var day = _date.substring(8, 10);

    day = parseInt(day) + 1;
    _date = year + '-' + month + '-' + day;

      $.ajax({
          type: "POST",
          url: "../php/search.php",
          data: {date: _date, hours : _hours, size : _size, projector : _projector},
          success: function(data)
          {
              alert("Successful");
          }
    });
  }

  previousDay = function(_date, _hours, _size, _projector)
  { 
    var year = _date.substring(0, 4);
    var month = _date.substring(5, 7);
    var day = _date.substring(8, 10);

    day = parseInt(day) - 1;
    _date = year + '-' + month + '-' + day;

      $.ajax({
          type: "POST",
          url: "../php/search.php",
          data: {date: _date, hours : _hours, size : _size, projector : _projector},
          success: function(data)
          {
              alert(data);
          }
    });
  }
  */


  goBack = function()
  {
    document.location.href = '../'; 
  }

  chooseTime = function(time, hours, room)
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
           $('#infoText').text('Du har valgt tidsrommet ' + time + ' - ' + (time + hours));
            start = time;
            end = (time + hours);
        }
    }
    

  }

  $('#mailForm').submit(function() {
     $('#fromTime').val(start);
     $('#toTime').val(end);
     $('#room').val(roomId);

});
  

  showError = function()
  {
    $('#infoText').text('Ugyldig tidspunkt. Prøv de grønne boksene..');
  }
