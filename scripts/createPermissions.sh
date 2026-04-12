#!/bin/bash

# Verificar se o arquivo de rotas foi modificado recentemente
ROUTES_FILE="routes/web.php"
if [[ $(find "$ROUTES_FILE" -mtime -1) ]]; then
    echo "Rotas modificadas recentemente. Gerando permissões a partir das rotas..."
    php artisan permission:create-route-permissions || { echo "Erro ao gerar permissões"; exit 1; }
else
    echo "Nenhuma modificação recente nas rotas. Pulando geração de permissões."
fi

echo "Executando seeder para criar usuário administrador..."
php artisan db:seed --class=CreateAdminUserSeeder --force || { echo "Erro ao executar seeder"; exit 1; }

echo "Limpando caches..."
bash "$(pwd)/scripts/clearCaches.sh" || { echo "Erro ao limpar caches"; exit 1; }

echo "Processo concluído!"