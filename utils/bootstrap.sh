#!/bin/bash
cd `dirname $0`
source runner.sh

cd ..

NPM_GLOBAL_PACKAGES="gulp bower node-gyp"

task_php() {
    if ! [[ -e "composer.phar" ]]; then
        wget http://getcomposer.org/composer.phar || return
    fi

    php composer.phar install
}

task_node() {
    if [[ -e "$HOME/.nvm/nvm.sh" ]]; then
        if ! runner_is_defined ${NPM_GLOBAL_PACKAGES}; then
            npm install -g ${NPM_GLOBAL_PACKAGES} || return
        fi
    fi

    runner_parallel node_{npm,bower}
}

task_node_npm() {
    if [[ "${1}" == "--vagrant" ]]; then
        VAGRANT_OPTIONS="--no-bin-links"
        runner_log_warning "Using npm options: ${VAGRANT_OPTIONS}"
    fi
    npm install ${VAGRANT_OPTIONS}
}

task_node_bower() {
    bower install
}

task_perm() {
    ## Lazer flat file DB writes to this folder
    chmod 777 app/storage
}

task_default() {
    runner_parallel php node perm

    if ! runner_is_defined ${NPM_GLOBAL_PACKAGES}; then
        runner_log_warning "Couldn't install some global npm packages."
        runner_log "Please install them manually using 'npm install -g ${NPM_GLOBAL_PACKAGES}'."
        exit 1
    fi
}

runner_bootstrap
runner_log_success "Bootstrap successful!"
