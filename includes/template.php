<?php
/*	Pretty Robust Internet Station Management
 *	HTML Templates
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */

	global $title;
	
	function printHeader() {
		echo("<!DOCTYPE html>\n");
		echo("<html>\n");
		echo("<head>\n");
		echo("<meta charset=\"utf8\">\n");
		echo("<title></title>\n");
		echo("<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/bootstrap.min.css\" />\n");
		echo("<script type=\"text/javascript\" src=\"assets/jquery.min.js\"></script>\n");
		echo("<script type=\"text/javascript\" src=\"assets/bootstrap.min.js\"></script>\n");
		echo("</head>\n");
		echo("<body>\n");
	}
	
	function printNav() {
		echo("<div class=\"navbar navbar-fixed-top\">\n");
		echo("<div class=\"navbar-inner\">\n");
		echo("<div class=\"container\">\n");
		echo("<a class=\"brand\" href=\"./\">prism</a>");
		echo("</div>\n</div>\n</div>");
	}
	
	function printFooter() {
		echo("</body>\n");
		echo("</html>");
	}
?>