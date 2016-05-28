#!/bin/sh

BASE_URL="./files/sched"
PDF_URL="http://www.raf.edu.rs/Rasporedi/Kolokvijumske%20nedelje/2015-2016/IV_kol_nedelja.pdf"

curl -s $PDF_URL > $BASE_URL".pdf" ;
/usr/local/bin/pdftotext -enc UTF-8 -layout $BASE_URL".pdf" ;

