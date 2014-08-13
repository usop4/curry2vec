#!/bin/sh

cp ~/www/curry/corpus.db ~/www/curry/corpus.db.bak

sort ~/www/curry/bin/Others.csv > ~/www/curry/bin/temp
cp ~/www/curry/bin/temp ~/www/curry/bin/Others.csv
cp ~/www/curry/bin/Others.csv ~/src/mecab-ipadic-2.7.0-20070801
/usr/local/libexec/mecab/mecab-dict-index -d ~/src/mecab-ipadic-2.7.0-20070801 -o ~/src/newDict/
#/usr/local/libexec/mecab/mecab-dict-index -d /usr/local/lib/mecab/dic/ipadic -u curry.dic -f utf-8 -t utf-8 curry.csv

curl -o ~/www/curry/bin/curry.txt http://barcelona-prototype.com/curry/db.php?mode=raw
wc -l ~/www/curry/bin/curry.txt
python ~/www/curry/bin/replace_pre.py ~/www/curry/bin/curry.txt > ~/www/curry/bin/curry_replace.txt
mecab -Owakati -b 5242880 ~/www/curry/bin/curry_replace.txt > ~/www/curry/bin/curry_wakati.txt
python ~/www/curry/bin/replace.py ~/www/curry/bin/curry_wakati.txt > ~/www/curry/bin/curry_wakati_replace.txt
~/www/curry/bin/word2vec -train ~/www/curry/bin/curry_wakati_replace.txt -output ~/www/curry/bin/curry.bin -cbow 0 -size 200 -window 5 -negative 0 -hs 1 -sample 1e-3 -threads 12 -binary 1

#time ./word2phrase -train curry_wakati_replace.txt -output curry-phrase -threshold 500 -debug 2 -min-count 3
#time ./word2vec -train curry-phrase -output curry-phrase.bin -cbow 0 -size 300 -window 10 -negative 0 -hs 1 -sample 1e-3 -threads 12 -binary 1 -min-count 3
#./compute-accuracy curry-phrase.bin <curry-phrases.txt
