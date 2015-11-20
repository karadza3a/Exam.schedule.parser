# TODO check params

curl https://www.raf.edu.rs/Rasporedi/Kolokvijumske%20nedelje/2015-2016/I_kol_nedelja.pdf > safetyfirstkol.pdf ;
pdftotext -enc UTF-8 -layout safetyfirstkol.pdf ;
for var in "$@" 
do
	sed -n "/^"$var"/p" safetyfirstkol.txt >> safetyfirstkol2.txt ; 
done

python kolok.py safetyfirstkol2.txt
rm safetyfirstk*

# > sh exam-shedule-parser.sh Geom Intel Kodov Kripto
