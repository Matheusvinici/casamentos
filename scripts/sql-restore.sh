# loop
clear

dir="$PWD"
source $dir/.env
echo $DB_DATABASE
database_used="$DB_DATABASE"
database_user="$DB_USERNAME"
user_password="$DB_PASSWORD"

if [ -z "$1" ]
then
    echo "Qual arquivo a restaurar?"
    exit 127;
else
    echo "Ok vou restaurar as tabelas para "$1".";
fi

    read -p "Digite o nome da tabela: " table
    table="adm_$table"

    diretorio=$(dirname "$1")
    file=$(basename "$1")
    mkdir -p $diretorio/dirSqls
    cp $1 $diretorio/dirSqls/
    cd $diretorio/dirSqls

# Pedir ao usuário para inserir os nomes das tabelas separados por espaço
# read -p "Digite o nome da tabela: " tables_input

# Converter a entrada em uma matriz
        # /opt/lampp/bin/mysql -u $database_user -p$user_password $database_used -e "SET FOREIGN_KEY_CHECKS=0;"

        # sed -n -e "/DROP TABLE.*\`$table\`/,/UNLOCK TABLES/p" $file > $table/$table.sql &&
        # sed -i '/DROP TABLE IF EXISTS `[^`]*`/i SET FOREIGN_KEY_CHECKS=0;' $table/$table.sql
        # sed -i '/DROP TABLE IF EXISTS `[^`]*`/a SET FOREIGN_KEY_CHECKS=1;' $table/$table.sql

        echo "Restaurando o arquivo $1 ..."

        hora_atual=$(date +"%H:%M:%S")
        echo "Início: $hora_atual"
        # /opt/lampp/bin/mysql -u $database_user -p$user_password $database_used < $diretorio/dirSqls/$file
        # /opt/lampp/bin/mysql -u $database_user -p$user_password $database_used -e "SET FOREIGN_KEY_CHECKS=1;"
        
        /opt/lampp/bin/mysql -u $database_user -p$user_password $database_used -e "
        SET FOREIGN_KEY_CHECKS=0;
        SOURCE $diretorio/dirSqls/$file;
        SET FOREIGN_KEY_CHECKS=1;
        "

        hora_atual=$(date +"%H:%M:%S")
        echo "Fim: $hora_atual"

# done




