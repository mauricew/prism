Prism 
==========
#####A Pretty Robust Internet [Radio] Station Management Tool

Prism is the culmination of the need for a separate radio station management panel without having to resort to complicated interfaces and features with software like Airtime. The plan is to have schedule management, realtime logging, and a hope for configuring the output server and audio streamer without having to touch configuration files.

#### Current version: **0.2.1**
* Create/Retrieve/Update/Delete shows and streams, as before
* Import shows from CSV
* Safer OOP practices

Requirements
---------
* LAMP stack with PHP 5.3 or higher
* One or more Icecast2 servers, local or remote
* (TBA) Liquidsoap
* (Optional) Access to cron and local email

Installation
---------
If no install currently exists, you will be pointed to run install.php. You will need to have a running MySQL server, and the database you create must not currently exist. (This will change before beta!)

Why?
---------
Before 2011, our live broadcasted shows over at [Knightcast](http://knightcast.org/) were done with a SHOUTcast server that required the show host to manually hit a button to start a show, and was locally recorded. That year brought us a cool guy that switched us to Icecast2 and showed me (and everyone else) the wonders of liquidsoap, which provided close to full automation for our station and recording on our central server. I became the studio director the next year, and rolled with it.

With my departure, it gives the challenge of new, non-technically inclined people to deal with Drupal, which is how our liquidsoap server labels and records shows. Since the rest of the school had a 2013 plan to switch to Wordpress, this app helps simplify the automation of show metadata, recording, and logging (a homebrew system with flat files is currently what I have in place).

I work towards giving simple, free, and a non-overwhelming way of managing an online radio station with Prism.