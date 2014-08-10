#!/usr/bin/env python
# -*- coding: utf-8 -*-

import cgi
import string
import sys
import time

from subprocess import Popen, PIPE

print '''Content-Type: application/json

[''',

form = cgi.FieldStorage()

if form.has_key("key"):
  key = form["key"].value
  key = key.replace('　',' ') #全角空白→半角空白
  key = key.replace('  ',' ') #半角空白２個→半角空白１個
  key = key.lstrip().rstrip()
else:
  key = ""

if form.has_key("cmd"):
  cmd = "/home/barcelona/www/curry/bin/"+form["cmd"].value
else:
  cmd = "/home/barcelona/www/curry/bin/distance2"

if form.has_key("bin"):
  bin = "/home/barcelona/www/curry/bin/"+form["bin"].value
else:
  bin = "/home/barcelona/www/curry/bin/curry.bin"

with open(cmd+'.out','w') as fd:
    Popen([cmd,bin,key],stdout=fd)

time.sleep(0.1);

with open(cmd+'.mecab.out','w') as fm:
    Popen(["mecab",cmd+".out","-d","/home/barcelona/src/newDict"],stdout=fm)

time.sleep(0.1);

for line in open(cmd+'.mecab.out','r'):
    if line.find("EOS") != 0:
        word = line.split('\t')
        part = word[1].split(',')
        print '{"name":"'+word[0]+'","part":"'+part[0]+'","part1":"'+part[1]+'"}'
        print ","

print '''{}]
'''