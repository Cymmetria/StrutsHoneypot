# CymmetriaResearch

StrutsHoneypot is an Apache 2 based honeypot that includes a seperate detection module (apache mod) for Apache 2 servers that detects and/or blocks the sturts CVE 2017-5638 exploit.
It is released under the MIT license for the use of the community.  
Cymmetria Research, 2017.  
http://www.cymmetria.com/  
Please consider trying out the MazeRunner Community Edition, the free version of our cyber deception platform.  
Written by: Nir Krakowski (@nirkrakowksi), Imri Goldberg (@lorgandon)  
Contact: research@cymmetria.com  

# Installation (Ubuntu)
# --------------------
Honeypot Installation
----------------
apt-get install docker.io
docker build -t struts_honepot .

Running the Honeypot
--------------------
docker run -p 80:80 --name "mystrutspot_docker" -d struts_honepot

Accessing the logs
------------------
Run 'docker ps' to validate the docker name: "mystrutspot_docker"
Then run 'docker -t -i mystrutspot_docker cat /var/log/apache2/error.log'

# Testing
# -------
Prerequisites
-------------
apt-get install python2 python-pip
pip install requests

Then use test-struts2_S2-045.py like below:
Usage: ./test-struts2_S2-045.py <url>
        e.g: ./test-struts2_S2-045.py http://localhost/

Important note
--------------
This has currently only been tested on Amazon's Ubuntu 16.04 LTS image, but should work on many other platforms.
