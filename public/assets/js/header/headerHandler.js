
	$(document).ready(function(){

		var showHeaderAt = 292;

		var win = $(window);
		var body = $('body');

		var pane = $('.settings-pane');
		var timeControl = $('#timeControler');

		var changePanehandler = function changePanehandler() {
			if (pane.hasClass('open')) {
				pane.removeClass('open');
			} else {
				pane.addClass('open');
			}
		};

		timeControl.click(changePanehandler);

		// Show the fixed header only on larger screen devices

		if(win.width() > 600){

			// When we scroll more than 150px down, we set the
			// "fixed" class on the body element.

			win.on('scroll', function(e){

				if(win.scrollTop() > showHeaderAt) {
					body.addClass('fixed');
				}
				else {
					body.removeClass('fixed');
				}
			});

		}

	});