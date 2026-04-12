# loop
clear

# Variaveis necessária para encontrar o arquvo .env
script_name=$(basename "$0")
echo "Nome do arquivo: $script_name"
script_path=$(readlink -f "$0")
dir=$(echo "$script_path" | sed "s;$script_name;;")
echo $dir

# incluidno o arquivo .env
source $dir../.env


if [ -z "$1" ]
then
    echo "Qual arquivo a restaurar?"
    exit 127;
else
    echo "Ok vou restaurar as tabelas para "$1".";
fi

hora_atual=$(date +"%H:%M:%S")
echo "Início: $hora_atual"

database_tmp="tmp_$DB_DATABASE"
database_used=$DB_DATABASE
database_user=$DB_USERNAME
user_password=$DB_PASSWORD

    echo "Descompactando Sqls ..."
    diretorio=$(dirname "$1")
    mkdir -p $diretorio/dirSqls
    unzip "$1" -d $diretorio/dirSqls

    cd $diretorio/dirSqls

    file=$(basename "$1")
    SQL=$(echo $file | sed "s/.zip//")

    echo "Restaurando as tabelas ..."

/opt/lampp/bin/mysql -u $database_user -p$user_password -e "
DROP DATABASE IF EXISTS $database_tmp;
CREATE DATABASE $database_tmp;
"

/opt/lampp/bin/mysql -u $database_user -p$user_password $database_tmp < "$SQL"

tables=$(/opt/lampp/bin/mysql -u $database_user -p$user_password $database_tmp -e "
DROP DATABASE IF EXISTS $database_used;
CREATE DATABASE IF NOT EXISTS $database_used;
SHOW TABLES;" | grep -v Tables_in)

# Iterate over the tables and rename
for table in $tables; do
  echo "Renaming table $database_tmp.$table to $database_used.$table"
  
  # Add quotes around database and table names
  /opt/lampp/bin/mysql -u $database_user -p$user_password -e "
    USE $database_tmp;
    RENAME TABLE $table TO $database_used.$table;
  "
done

/opt/lampp/bin/mysql -u $database_user -p$user_password -e "
    DROP DATABASE IF EXISTS $database_tmp;
"

hora_atual=$(date +"%H:%M:%S")
echo "Fim: $hora_atual"
rm -r $diretorio/dirSqls