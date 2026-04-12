# Delete branch desenvolvimento

current_branch=$(git rev-parse --abbrev-ref HEAD)

if [[ $current_branch == "dev-"* ]]; then
    echo "Você está na branch de desenvolvimento, não será possível destruir o branch."
else
    echo "Branch locais:"
    git branch --no-color | awk '/^  dev-/{print $1}'
    echo "Qual branch você deseja excluir:"
    read nome_branch
    git push origin --delete dev-$nome_branch
    git branch -d dev-$nome_branch
fi

git remote prune origin

#Forçar Deletar branch de qualquer jeito
#git branch -D nome-do-branch