#!/bin/sh

cp Others.csv ~/src/mecab-ipadic-2.7.0-20070801
/usr/local/libexec/mecab/mecab-dict-index -d ~/src/mecab-ipadic-2.7.0-20070801 -o ~/src/newDict/
#/usr/local/libexec/mecab/mecab-dict-index -d /usr/local/lib/mecab/dic/ipadic -u curry.dic -f utf-8 -t utf-8 curry.csv

curl -o curry.txt http://barcelona-prototype.com/curry/db.php?mode=raw
python replace_pre.py curry.txt > curry_replace.txt
mecab -Owakati curry_replace.txt > curry_wakati.txt
python replace.py curry_wakati.txt > curry_wakati_replace.txt
./word2vec -train curry_wakati_replace.txt -output curry.bin -cbow 0 -size 200 -window 5 -negative 0 -hs 1 -sample 1e-3 -threads 12 -binary 1
#./distance2 curry.bin "カレー"

#time ./word2phrase -train curry_wakati_replace.txt -output curry-phrase -threshold 500 -debug 2 -min-count 3
#time ./word2vec -train curry-phrase -output curry-phrase.bin -cbow 0 -size 300 -window 10 -negative 0 -hs 1 -sample 1e-3 -threads 12 -binary 1 -min-count 3
#./compute-accuracy curry-phrase.bin <curry-phrases.txt
