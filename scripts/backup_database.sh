script_name=$(basename "$0")
script_path=$(readlink -f "$0")
dir=$(echo "$script_path" | sed "s;$script_name;;")
source $dir../.env

# if [ $DB_SERVER == 'local' ]
# then
#     echo "Você está localemente, não será realizado um backup, entre em um servidor ssh"
#     exit 127;
# fi

echo "Realizando o backup" $DB_DATABASE
date=$(date '+%Y-%m-%d_%Hh-%Mmin-%Sseg')
echo $date

$URL_MYSQL"dump" $DB_DATABASE -u $DB_USERNAME -p"$DB_PASSWORD" > $URL_BACKUPS/"$DB_SERVER"_tables_adm_$date.sql

cd $URL_BACKUPS
zip "$DB_SERVER"_tables_adm_$date.sql.zip "$DB_SERVER"_tables_adm_$date.sql 
rm "$DB_SERVER"_tables_adm_$date.sql

