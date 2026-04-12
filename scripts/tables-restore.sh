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

database=$DB_DATABASE
database_user=$DB_USERNAME
user_password=$DB_PASSWORD

if [ -z "$1" ]
then
    echo "Qual arquivo a restaurar?"
    exit 127;
else
    echo "Ok vou restaurar as tabelas para "$1".";
fi

table="adm_$table"

# Pedir ao usuário para inserir os nomes das tabelas separados por espaço
read -p "Digite os nomes das tabelas (separados por espaço): " tables_input

# Converter a entrada em uma matriz
IFS=' ' read -ra tables <<< "$tables_input"

    echo "Descompactando Sqls ..."
    diretorio=$(dirname "$1")
    mkdir -p $diretorio/dirSqls
    unzip "$1" -d $diretorio/dirSqls
    file=$(basename "$1")
    SQL=$(echo $file | sed "s/.zip//")
    cd $diretorio/dirSqls

for table in "${tables[@]}"; do
    table="adm_$table"

    mkdir -p $table
    echo "Extraindo tabela $table ..." &&

    # for f in $SQL; do
        if grep -q "$table" $SQL; then
            echo "A tabela $table foi encontrada."
        else
            RED='\033[0;31m'
            NC='\033[0m'
            echo -e "${RED}A tabela $table não foi encontrada, verifique se o nome está correto.${NC}"
            exit 1
        fi

        # Desativar verificações de chave estrangeira
        # /opt/lampp/bin/mysql -u $database_user -p$user_password $database -e "SET FOREIGN_KEY_CHECKS=0;"

        sed -n -e "/DROP TABLE.*\`$table\`/,/UNLOCK TABLES/p" $SQL > $table/$table.sql &&

        sed -i '/DROP TABLE IF EXISTS `[^`]*`/i SET FOREIGN_KEY_CHECKS=0;' $table/$table.sql
        sed -i '/DROP TABLE IF EXISTS `[^`]*`/a SET FOREIGN_KEY_CHECKS=1;' $table/$table.sql
        
        echo "Restaurando a tabela $table ..."

        hora_atual=$(date +"%H:%M:%S")
        echo "Início: $hora_atual"

        /opt/lampp/bin/mysql -u $database_user -p$user_password $database -e "
        SET FOREIGN_KEY_CHECKS=0;
        SOURCE $table/$table.sql;
        SET FOREIGN_KEY_CHECKS=1;
        "
        # /opt/lampp/bin/mysql -u $database_user -p$user_password $database < $table/$table.sql

        hora_atual=$(date +"%H:%M:%S")
        echo "Fim: $hora_atual"

    # done
done

rm $SQL
rm -r $diretorio/dirSqls



