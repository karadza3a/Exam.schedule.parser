#!/bin/sh

BASE_URL="./files/sched"
PDF_URL="http://www.raf.edu.rs/Rasporedi/Ispitni%20rokovi/2016-2017/januarskirok.pdf"

curl -s $PDF_URL > $BASE_URL".pdf" ;
/usr/local/bin/pdftotext -enc UTF-8 -layout $BASE_URL".pdf" ;

