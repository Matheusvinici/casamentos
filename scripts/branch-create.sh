# criar Branch de desenvolvimento
current_branch=$(git rev-parse --abbrev-ref HEAD)

if [[ $current_branch == "dev-"* ]]; then
    echo "Você está em branch de desenvolvimento, não será criado novo branch."
else
        echo "Nome do Novo Branch:"
        read nome_branch
        git checkout -b dev-$nome_branch &&
        git add .
        git commit -m "Criação do Branch dev-$nome_branch"
        git branch -M dev-$nome_branch
        git push -u origin dev-$nome_branch

fi
