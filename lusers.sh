mysql brankia -e "select UserID,name,Photo,sucursal from GDN_User ORDER BY UserID DESC limit 10;" | more | grep -v "UserID" > lusers.tmp
numero=`cat lusers.tmp | wc -l`

while [ $numero != 0 ]
do

	nombre=``

done
rm -rf lusers.tmp
