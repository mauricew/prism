/*
 * Pretty Robust Internet Station Management
 * JavaScript functions
 * Created by Maurice Wahba (c)2013
 */

function relativeTime(t) {
	var d = new Date();
	d.setHours(parseInt(t.match(/^\d{1,2}/g).toString()));
	d.setMinutes(parseInt(t.match(/:[0-5][0-9]/g).toString().split(':')[1]));
	d.setSeconds(0);
	t.match(/[AaPp][Mm]/g).toString().toLowerCase() == "pm" ? d.setHours(d.getHours() + 12) : $.noop(); 
	var rel = new Date() - d;
	var future;
	rel < 0 ? future = true : future = false;
	rel = Math.abs(rel / 1000);
	
	var diff, str;
	
	if(rel < 60) {
		str = "less than a minute";
	}	
	
	else if(rel < 3600) {
		diff = Math.floor(rel / 60);
		str =  diff + " m";// + (diff != 1 ? "s" : "");
	}
	
	else if(rel < 86400) {
		diff = Math.floor(rel / 3600);
		var pdiff = Math.floor(rel / 60 - (Math.floor(rel / 3600) * 60));
		str = diff + " h " + pdiff + " m";// + (diff != 1 ? "s" : "");
	}
	
	else {
		diff = Math.floor(rel / 86400);
		str = diff + " d";// + (diff != 1 ? "s" : "");
	}
		
	return str;
	
}

$(document).ready(function() {
	$("#lolzor").text("Nothing");
	/*setInterval(function() { $("#home .relative-now").text(
		"done in " + relativeTime($("#home .relative-now").text().match(/\d{1,2}:\d{2}( ?)[ap]m/g).toString())
	)}, 60000);
	setInterval(function() { $("#home .relative-next").text(
		"starts in " + relativeTime($("#home .relative-next").text().match(/\d{1,2}:\d{2}( ?)[ap]m/g).toString())
	)}, 60000);
*/
	$(".relative").each(function () {
		$(this).text(relativeTime(
			$(this).text().match(/\d{1,2}:\d{2}( ?)[ap]m/g).toString()
		));
		if($(this).attr('id').indexOf("now") !== -1)
			$(this).append(" left");
		else if($(this).attr('id').indexOf("next") !== -1)
			$(this).prepend("in ");
	});
	$("#day-of-week button").on("click", function(e) {
		$('input[name=day-of-week]').val(e.target.value);
	});
	$("input[name$='minute']").on('change', function(e) {
		if(e.target.value < 10) {
			e.target.value = "0" + e.target.value;
		}
	});
	$("div[id$='-ampm'] a").on('click', function(e) {
		$('input[name=' + $(this).parent().attr('id') + ']').val($(this).text());
	});
	$("#show-form button[name=submit]").on('click', function(e) {
		$("input[type=hidden]").each(function() {
			if(this.value == "") {
				e.preventDefault();
				var group = this.name;
				$("#" + group).popover({ content: "You forgot something...." });
				$("#" + group).popover('show');
				$("#" + group).one("click", function() {
					$("#" + group).popover('destroy'); 
				});
			}
		});
	});
});
