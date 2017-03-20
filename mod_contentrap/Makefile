mod_contentrap.lo: mod_contentrap.c
	apxs -c mod_contentrap.c


all: mod_contentrap.lo

install: mod_contentrap.lo
	 apxs -i -a -c mod_contentrap.c

clean:
	rm mod_contentrap.la mod_contentrap.lo mod_contentrap.slo
