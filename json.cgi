#!/usr/bin/env python
# -*- coding: utf-8 -*-

import cgi
import sys

from subprocess import Popen, PIPE

print '''Content-Type: application/json

[{"name":"''',

form = cgi.FieldStorage()

if form.has_key("key"):
  key = form["key"].value
  key = key.replace('　',' ') #全角空白→半角空白
else:
  key = "カレー"

if form.has_key("cmd"):
  cmd = "/home/barcelona/www/curry/bin/"+form["cmd"].value
else:
  cmd = "/home/barcelona/www/curry/bin/distance3"

if form.has_key("bin"):
  bin = "/home/barcelona/www/curry/bin/"+form["bin"].value
else:
  bin = "/home/barcelona/www/curry/bin/curry.bin"

p = Popen([cmd,bin,key], stdout=PIPE)
while 1:
  c = p.stdout.read(1)
  if not c:
    break
  if c == bytes("\x0a"):
    print '"},{"name":"',
  else:
    sys.stdout.write(c)

p.wait()

print '''"}]
'''