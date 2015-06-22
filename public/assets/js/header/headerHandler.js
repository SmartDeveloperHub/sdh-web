var setTimeInfo;

	$(document).ready(function(){

		var showHeaderAt = 150;
		var closePaneAt = 300;

		var win = $(window);
		var body = $('body');

		var lastTop = win.scrollTop();

		var pane = $('.settings-pane');
		var timeControl = $('#timeControler');
		var controlIco = $('#timeBarIcon');
		var fromLabel = $('#fromLabel');
		var sinceLabel = $('#sinceLabel');
		var toLabel = $('#toLabel');

		var changePanehandler = function changePanehandler() {
			if (pane.hasClass('open')) {
				closePanehandler();
			} else {
				openPanehandler();
			}
		};

		var closePanehandler = function closePanehandler() {
			pane.removeClass('open');
			controlIco.removeClass("fa-caret-up");
			controlIco.addClass("fa-caret-down");
		}

		var openPanehandler = function openPanehandler() {
			pane.addClass('open');
			closePaneAt = win.scrollTop();
			lastTop = win.scrollTop();
			controlIco.removeClass("fa-caret-down");
			controlIco.addClass("fa-caret-up");
		}

		setTimeInfo = function setTimeInfo (from, to) {
			if (moment(from).isValid() && moment(from).isValid()) {
				fromLabel.text(moment(from).format("YYYY-MM-DD"));
				sinceLabel.text(moment.duration(to-from).humanize());
				toLabel.text(moment(to).format("YYYY-MM-DD"));
			} else {
				console.log("setTimeInfo... invalid dates");
			}
		}
		timeControl.click(changePanehandler);

		// When we scroll more than 150px down, we set the
		// "fixed" class on the body element.

		win.on('scroll', function(e){
			// auto close time Panel
			if(Math.abs(win.scrollTop() - closePaneAt) > 300) {
				closePanehandler();
			}

			// auto hide/show header
			if((win.scrollTop() > 350)) {
				if ((lastTop > win.scrollTop()) && (Math.abs(win.scrollTop() - lastTop) > 15)) {
					// up
					body.removeClass('hidd');
				} else if ((lastTop <= win.scrollTop()) && (Math.abs(win.scrollTop() - lastTop) > 70)) {
					// down
					closePanehandler();
					body.addClass('hidd');
				}
				lastTop = win.scrollTop();
			} else if (body.hasClass('hidd') && (win.scrollTop() < 150)) {
				body.removeClass('hidd');
				lastTop = win.scrollTop();
			}

			// Fixed Header
			if(!body.hasClass('fixed') && (win.scrollTop() > showHeaderAt)) {
				body.addClass('fixed');
				lastTop = win.scrollTop();
			}
			else if(body.hasClass('fixed') && (win.scrollTop() <= showHeaderAt)){
				body.removeClass('fixed');
				lastTop = win.scrollTop();
			}
		});
	});