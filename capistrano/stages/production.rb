server '163.172.137.80', user: fetch(:ssh_user), port: 22, roles: %w{web}

set :branch, 'master'
set :symfony_env, 'prod'
set :ssh_options, {
    forward_agent: true,
    compression: false
}
