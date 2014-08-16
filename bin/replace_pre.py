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

  # ma888tsu対策
  line = line.replace("ぢゃな","じゃな")
  line = line.replace("おー","お")
  line = line.replace("にー","に")
  line = line.replace("たー","た")
  line = line.replace("いー","い")
  line = line.replace("りー","り")
  line = line.replace("とー","と")
  line = line.replace("るー","る")
  line = line.replace("でー","で")
  line = line.replace("｢","「")
  line = line.replace("｣","」")

  line = line.replace("･","・")

  dict = [
    'results','result','recipeId','recipeTitle','recipeUrl','categoryName','recipePublishday',
    'foodImageUrl','pickup','shop','nickname','recipeMaterial','recipeDescription','categoryUrl','recipeCost',
    'recipeIndication',
    '"',
    '￫ܫ￩','>_<','◑∀◑','･ω･','´▽`','・▽・',
    '≧∇≦','^^','^.^','´艸｀','￣ー￣',
    '❤','♡','♬','✧','♥','‼︎',
    '▽','∇','∀',
    '↑','↓','←','→',
    '⇒','♪',
    '－','＃',
    'google','Google','ISBN','rakuten','Facebook','Twitter','Tweet',
    'ブログ','はてなブックマーク','マイナビニュース','Retty',
    '曖昧さ回避','カレーを食べに行こう','に関する','に関して','指定なし',
    # 食べログ
    '前の口コミへ','口コミ一覧','次の口コミへ',
    'この口コミは、','主観的なご意見・ご感想であり、お店の価値を客観的に評価するものではありません。',
    'あくまでも一つの参考としてご活用ください。',
    'また、この口コミは、','さんが最後に訪問した',
    '当時のものです。内容、金額、メニュー等が現在と異なる場合がありますので、訪問の際は必ず事前に電話等でご確認ください。',
    '詳しくはこちら','携帯電話番号認証済',
    '[   画像表示形式：   一覧   ｜   拡大   ]','いいね！',
    '利用規約に違反している口コミは、右のリンクから報告する事ができます。',
    '問題のある口コミを連絡する',
    '食べログ会員（無料）に登録すると、口コミにいいね！やコメントをしたり、マイレビュアー（お気に入り）として保存する事ができます。',
    '食べログ会員登録/ログイン',
    'この店舗の関係者の方へ','ユーザーから投稿された口コミに対して、お店側からお礼や情報追加などの返信を行ってみませんか？',
    '口コミに返信を行うには？',
    '「みんなで作るグルメサイト」という性質上、店舗情報の正確性は保証されませんので、必ず事前にご確認の上ご利用ください。',
    '詳しくはこちら',
    # その他
    '店舗情報を見る','クーポンを見る'
  ]

  for word in dict:
    line = line.replace(word,'')

  print line
  line = f.readline()
