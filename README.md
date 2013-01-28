Prism, A Pretty Robust Internet [Radio] Station Management Tool
==========

Prism is the culmination of the need for a separate radio station management panel without having to resort to complicated interfaces and features with software like Airtime. The plan is to have schedule management, realtime logging, and a hope for configuring the output server and audio streamer without having to touch configuration files.
At the time of this writing the app is **not** operational.

Why?
---------
Before 2011, our live broadcasted shows over at [Knightcast](http://knightcast.org/) were done with a SHOUTcast server that required the show host to manually hit a button to start a show, and was locally recorded. That year brought us a cool guy that switched us to Icecast2 and showed me (and everyone else) the wonders of liquidsoap, which provided close to full automation for our station and recording on our central server. I became the studio director the next year, and rolled with it.

But come 2013, the director of the umbrella organization with covered this and the other biggest, most funded groups on campus gave me a nod that all sites were switching to a Wordpress base. I've had to deal with a slightly convoluted procedure to manage shows; described in one sentence: we used a show content type in Drupal that was checked every minute by liquidsoap to see if one was happening, and if there was one along with the audio in the studio it would kick in with the proper metadata and archive recording. The dependency of a specific software platform that probably less than 1% of the student body (of a 60k person school, mind you) can understand is a bit of a pain in case my replacement 2 years down the road wanted to know what the hell to do.

So go forth into the world, my little prism, and preach the wonders of **free** and **simple** internet radio automation.

Requirements
---------
* LAMP stack (I wouldn't recommend using Windows)
* One or more Icecast2 servers, local or remote
* Liquidsoap (this is why you shouldn't use Windows)
* (Optional) Access to cron and local email
