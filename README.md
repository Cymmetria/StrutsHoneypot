# StrutsHoneypot
Cymmetria Research, 2017.

https://www.cymmetria.com/

Written by: Nir Krakowski (@nirkrakowksi), Imri Goldberg (@lorgandon)

Contact: research@cymmetria.com Contact: research@cymmetria.com

StrutsHoneypot is an Apache 2 based honeypot that includes a seperate detection module (apache mod) for Apache 2 servers that detects and/or blocks the sturts CVE 2017-5638 
exploit. It is released under the MIT license for the use of the community.  


Please consider trying out the MazeRunner Community Edition, the free version of our cyber deception platform.  
https://community.cymmetria.com/

# Honeypot Installation, Running and Monitoring

Installation (Ubuntu)
----------------
- apt-get update
- apt-get install docker.io
- docker build -t struts_honeypot strutspot_docker/

Running the Honeypot
--------------------
docker run -p 80:80 --name "mystrutspot_docker" -d struts_honeypot

Accessing the logs
------------------
Run 'docker ps' to validate the docker name: "mystrutspot_docker"

Then run 'docker exec -t -i mystrutspot_docker cat /var/log/apache2/error.log'

# Testing
Prerequisites
-------------
- apt-get install python2.7 python-pip
- pip install requests

Then use test-struts2_S2-045.py like below:

Usage: 

./test-struts2_S2-045.py <url>

e.g: ./test-struts2_S2-045.py http://localhost/

Detailed Info
------------
The Honeypot uses mod_rewrite (see strutspot_docker/src/.htaccess) RewriteRule directive to redirect all requests to the same url.
To avoid redirection for cover.css, apache.png, and struts.svg it has seperate rule for it.
The Honeypot uses error_log() to send a JSON comment containing the connection info and other data to the apache default error log file.

Editing the Honeypot Website
----------------------------
Edit strutspot_docker/src/index.php and related ehtml files to add your own flavor to the honeypot itself.
Inside the index.php as <?php code. This code must remain intact, anything else is completely independent and can be modified.



# mod_contentrap Installation, and Running

### This is a very simple Apache module implementation filter intended for a linux server with a running Apache2 server.


Installing
---------
- apt-get install apache2-dev
- cd mod_contentrap/
- make
- make install

Running
-------
- server apache2 restart

Testing
-------
One can use the same python test script as before.

Important note
--------------
This has currently only been tested on Amazon's Ubuntu 16.04 LTS image, but should work on many other platforms (including previous versions of linux/ubuntu).


See also
--------
Check out our Mirai honeypot (MTPot):
- https://github.com/Cymmetria/MTPot/
