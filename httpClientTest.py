#!/usr/bin/python
# Http Client
import httplib

conn = httplib.HTTPConnection("localhost")
#headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}

# Head method, no return data
#conn.request("HEAD","/xoops/Ecross-Hack/sendmail.php")
conn.request("HEAD","/xoops/Ecross-Hack/sendmailTemp.php")
res = conn.getresponse()
print res.status, res.reason

# Get method
#conn.request("GET","/xoops/htdocs/0Cron/test.php", "", headers)
#res = conn.getresponse()
#print res.status, res.reason
#data1 = res.read();
#print data1
