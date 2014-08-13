#!/usr/bin/python
# coding: UTF-8

import sys
import re

f = open(sys.argv[1])
line = f.readline().rstrip()

while line:

  dict = [
    'https?://[\w/:%#\$&\?\(\)~\.=\+\-]+'
  ]
  for pattern in dict:
    p = re.compile(pattern)
    line = p.sub('',line)

  dict = [
    'results','result','recipeId','recipeTitle','recipeUrl','categoryName','recipePublishday',
    'foodImageUrl','pickup','shop','nickname','recipeMaterial','recipeDescription','categoryUrl','recipeCost',
    'recipeIndication',
    'google','Google','ISBN','rakuten','Facebook','Twitter','Tweet',
    '￫ܫ￩','>_<','◑∀◑','･ω･','´▽`',
    '≧∇≦','^^','^.^','´艸｀','￣ー￣',
    '❤','♡','♬','✧','♥',
    '▽','∇','∀',
    '↑','↓','←','→',
    '⇒','♪',
    '－','＃',
    'ブログ','はてなブックマーク','マイナビニュース','Retty',
    '曖昧さ回避','カレーを食べに行こう','に関する','に関して','指定なし',
    '店舗情報を見る','クーポンを見る'
  ]

  for word in dict:
    line = line.replace(word,'')

  print line
  line = f.readline()
