#!/bin/sh

sort ~/www/curry/bin/Others.csv > ~/www/curry/bin/temp
cp ~/www/curry/bin/temp ~/www/curry/bin/Others.csv
cp ~/www/curry/bin/Others.csv ~/src/mecab-ipadic-2.7.0-20070801
/usr/local/libexec/mecab/mecab-dict-index -d ~/src/mecab-ipadic-2.7.0-20070801 -o ~/src/newDict/
