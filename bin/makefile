CC = gcc
#The -Ofast might not work with older versions of gcc; in that case, use -O2
#CFLAGS = -lm -pthread -Ofast -march=native -Wall -funroll-loops -Wno-unused-result
CFLAGS = -lm -pthread -O2 -march=native -Wall -funroll-loops

all: word2vec word2phrase distance distance2 distance3 word-analogy word-analogy2 compute-accuracy

test: test.c
	$(CC) test.c -o test $(CFLAGS)
word2vec : word2vec.c
	$(CC) word2vec.c -o word2vec $(CFLAGS)
word2phrase : word2phrase.c
	$(CC) word2phrase.c -o word2phrase $(CFLAGS)
distance : distance.c
	$(CC) distance.c -o distance $(CFLAGS)
distance2 : distance2.c
	$(CC) distance2.c -o distance2 $(CFLAGS)
distance3 : distance3.c
	$(CC) distance3.c -o distance3 $(CFLAGS)
word-analogy : word-analogy.c
	$(CC) word-analogy.c -o word-analogy $(CFLAGS)
word-analogy2 : word-analogy2.c
	$(CC) word-analogy2.c -o word-analogy2 $(CFLAGS)
compute-accuracy : compute-accuracy.c
	$(CC) compute-accuracy.c -o compute-accuracy $(CFLAGS)
	chmod +x *.sh

clean:
	rm -rf word2vec word2phrase distance word-analogy compute-accuracy
