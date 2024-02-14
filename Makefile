.PHONY: all clean

all: sortidx checksort

sortidx: sortidx.c
	gcc -Wall -O3 sortidx.c -o sortidx

checksort: checksort.c
	gcc -Wall -O3 checksort.c -o checksort

clean:
	rm -f sortidx checksort

