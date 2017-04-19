#!/bin/sh

BASE_URL="./files/sched"
PDF_URL="https://www.raf.edu.rs/Rasporedi/Kolokvijumske%20nedelje/2016-2017/kol3.pdf"

curl -s $PDF_URL > $BASE_URL".pdf" ;
/usr/local/bin/pdftotext -enc UTF-8 -layout $BASE_URL".pdf" ;

