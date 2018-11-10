const clockHtml = document.querySelector(".chatroom__time__clock");
const dateHtml = document.querySelector(".chatroom__time__date");

const months = ["stycznia","lutego","marca","kwietnia","maja","czerwca","lipca","sierpnia","września","października","listopada","grudnia"];

var showHour = () => {
  //pobierz wartości do zmiennej
  let date = new Date();
  let hour = date.getHours(); 
  let minute = date.getMinutes();
  let second = date.getSeconds();

  // jeżeli godzina równa 0 zastąp ją 12
  if(hour == 0) hour = 12;

  //wstaw 0 przed wartość jeżeli poniżej 10
  hour = (hour < 10) ? "0" + hour : hour;
  minute = (minute < 10) ? "0" + minute : minute;
  second = (second < 10) ? "0" + second : second;

  //utwórz gotowy łańcuch
  let currentTime = `${hour}:${minute}:${second}`;
  //zaktualizuj date
  clockHtml.innerText = currentTime;

  //po sekundzie wywołaj funkcje - rekurencja
  setTimeout(showHour, 1000);
}


var showDate = () => {
  //pobierz wartości daty
  let date = new Date();
  let day = date.getDate();
  let month = date.getMonth();
  let year = date.getFullYear();
  //utwórz gotowy string
  let currentDate = `${day} ${months[month]}, ${year}`;

  //zaktualizuj date
  dateHtml.innerText = currentDate;

  //rekurencja jest niewymagana - aktualizacja tylko po załadowaniu
}

// wywołaj funkcje po załadowaniu
showHour();
showDate();


( function ( document, window, index )
{
	var inputs = document.querySelectorAll('.file-form__file-button');
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = document.querySelector('.file-form__file-submit'),
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( event )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = event.target.value.split( '\\' ).pop();

			if( fileName )
				label.innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));