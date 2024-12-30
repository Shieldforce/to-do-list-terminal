#!/bin/bash

CYAN='\033[0;36m'
LIGHT_CYAN='\033[1;36m'
BOLD='\033[1m'
NC='\033[0m'

response() {
    local color_bg="\033[48;5;250m"
    local black_text="\033[30m"
    local white_text="\033[97m"
    local reset="\033[0m"
    local width_total=100
    local label="RESPOSTA: "
    local label_length=${#label}
    local message="$1"
    local message_length=${#message}
    local spaces=$((width_total - label_length - message_length - 3))
    local padding=$(printf '%*s' "$spaces")
    printf "${color_bg}${white_text} ${label} ${reset}${color_bg}${black_text} ${message}${padding} ${reset}\n"
}

info() {
    local color_bg="\033[44m"
    local black_text="\033[30m"
    local white_text="\033[97m"
    local reset="\033[0m"
    local width_total=100
    local label="INFORMAÇÃO: "
    local label_length=${#label}
    local message="$1"
    local message_length=${#message}
    local spaces=$((width_total - label_length - message_length - 3))
    local padding=$(printf '%*s' "$spaces")
    printf "${color_bg}${white_text} ${label} ${reset}${color_bg}${black_text} ${message}${padding} ${reset}\n"
}

success() {
    local color_bg="\033[42m"
    local black_text="\033[30m"
    local white_text="\033[97m"
    local reset="\033[0m"
    local width_total=100
    local label="SUCCESS: "
    local label_length=${#label}
    local message="$1"
    local message_length=${#message}
    local spaces=$((width_total - label_length - message_length - 3))
    local padding=$(printf '%*s' "$spaces")
    printf "${color_bg}${white_text} ${label} ${reset}${color_bg}${black_text} ${message}${padding} ${reset}\n"
}

error() {
    local color_bg="\033[41m"
    local black_text="\033[30m"
    local white_text="\033[97m"
    local reset="\033[0m"
    local width_total=100
    local label="ERRO: "
    local label_length=${#label}
    local message="$1"
    local message_length=${#message}
    local spaces=$((width_total - label_length - message_length - 3))
    local padding=$(printf '%*s' "$spaces")
    printf "${color_bg}${white_text} ${label} ${reset}${color_bg}${black_text} ${message}${padding} ${reset}\n"
}

question() {
    local color_bg="\033[48;5;208m"
    local black_text="\033[30m"
    local white_text="\033[97m"
    local reset="\033[0m"
    local width_total=100
    local label="PERGUNTA: "
    local label_length=${#label}
    local message="$1"
    local message_length=${#message}
    local spaces=$((width_total - label_length - message_length - 3))
    local padding=$(printf '%*s' "$spaces")
    printf "${color_bg}${white_text} ${label} ${reset}${color_bg}${black_text} ${message}${padding} ${reset}\n"
}

echo ""

question "Clonar aplicação To Do List Terminal? (s/n)"
read -r resposta_1
echo ""

current_dir=$(pwd)
expected_dir="to-do-list-terminal"

mkdir $expected_dir;

cd $expected_dir;
exit 0;

if [[ "$resposta_1" =~ ^[Ss]$ ]]; then
    echo ""
    if [[ "$(basename "$current_dir")" == "$expected_dir" ]]; then
        info "Você já está dentro do diretório '$expected_dir'. Pulando clonagem."
        git reset --hard
        if ! git pull --allow-unrelated-histories; then
            error "Conflitos detectados. Sobrescrevendo com as alterações do repositório remoto."
            git merge --abort || true
            git reset --hard origin/main
        fi
    elif [[ -d "$expected_dir" && "$(ls -A "$expected_dir")" ]]; then
        info "O diretório '$expected_dir' já existe e não está vazio. Pulando clonagem."
        cd "$expected_dir"
        git reset --hard
        if ! git pull --allow-unrelated-histories; then
            error "Conflitos detectados. Sobrescrevendo com as alterações do repositório remoto."
            git merge --abort || true
            git reset --hard origin/main
        fi
    else
        info "Clonando o projeto..."
        echo ""
        git clone https://github.com/Shieldforce/to-do-list-terminal.git
        cd "$expected_dir"
        success "Projeto Clonado && Você está na pasta do projeto ($expected_dir)"
    fi
elif [[ "$(basename "$current_dir")" == "$expected_dir" ]] || [[ -d "$expected_dir" && "$(ls -A "$expected_dir")" ]]; then
    if [[ "$(basename "$current_dir")" == "$expected_dir" ]]; then
        info "Você já está dentro do diretório '$expected_dir'. Continuando o processo."
        git reset --hard
        if ! git pull --allow-unrelated-histories; then
            error "Conflitos detectados. Sobrescrevendo com as alterações do repositório remoto."
            git merge --abort || true
            git reset --hard origin/main
        fi
    else
        info "O diretório '$expected_dir' já existe. Continuando o processo."
        cd "$expected_dir"
        git reset --hard
        if ! git pull --allow-unrelated-histories; then
            error "Conflitos detectados. Sobrescrevendo com as alterações do repositório remoto."
            git merge --abort || true
            git reset --hard origin/main
        fi
    fi
else
    echo ""
    error "Clone da aplicação cancelada e o diretório não existe! Saindo..."
    exit 1
fi

echo ""
question "Criar alias no bashrc? (s/n)"

read -r resposta_2

current_dir=$(pwd)

if [[ "$resposta_2" =~ ^[Ss]$ ]]; then
    question "bashrc ou zshrc? (b - para bashrc / z - zshrc)"
    read -r resposta_3

    # Função para verificar e adicionar aliases no arquivo
    add_aliases_if_not_exist() {
        local file="$1"

        # Define os aliases
        local aliases=(
            "# To Do List Terminal"
            "alias task.go='cd $current_dir && sail down && sail up -d'"
            "alias task.list.all='cd $current_dir && sail artisan tdlt:list-task-command all=yes'"
            "alias task.list='cd $current_dir && sail artisan tdlt:list-task-command all=no'"
            "alias task.remove='cd $current_dir && sail artisan tdlt:remove-task-command'"
            "alias task.add='cd $current_dir && sail artisan tdlt:add-task-command'"
            "alias task.check='cd $current_dir && sail artisan tdlt:check-task-command'"
        )

        local alias_exists=false

        # Adiciona os aliases somente se eles não existirem no arquivo
        for alias in "${aliases[@]}"; do
            if grep -qF "$alias" "$file"; then
                alias_exists=true
            else
                echo "$alias" >> "$file"
            fi
        done

        if $alias_exists; then
            info "Alguns ou todos os aliases já existiam. Novos aliases adicionados, se necessário."
        else
            success "Todos os aliases foram adicionados com sucesso ao arquivo $file!"
        fi
    }

    # Verifica se é bashrc ou zshrc
    if [[ "$resposta_3" =~ ^[Bb]$ ]]; then
        add_aliases_if_not_exist ~/.bashrc
    elif [[ "$resposta_3" =~ ^[Zz]$ ]]; then
        add_aliases_if_not_exist ~/.zshrc
    else
        error "Entrada inválida! Escolha 'b' para bashrc ou 'z' para zshrc."
    fi
fi

echo ""
info "Baixando pacotes composer..."

cp .env.example .env

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# ./vendor/bin/sail down
#
# ./vendor/bin/sail up -d
#
# ./vendor/bin/sail artisan migrate:fresh --seed

bash ./vendor/shieldforce/scoob/scoob --type docker-laravel \
        --version 8.4 \
        --port 8094 \
        --redis-port 6394 \
        --mysql-port 3394

echo ""
info "Preparando para criar banco de dados!"
sleep 30

docker exec -it laravel-php-fpm-8.4-8094 mysql -e "create database to_do_list_terminal"

if [[ "$resposta_3" =~ ^[Bb]$ ]]; then
    info "Rode o comando [source ~/.bashrc] no seu terminal!"
elif [[ "$resposta_3" =~ ^[Zz]$ ]]; then
    info "Rode o comando [source ~/.zshrc] no seu terminal!"
fi
