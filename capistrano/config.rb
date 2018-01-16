# config valid only for current version of Capistrano
lock '3.5.0'

set :application, 'comeback-immo'
set :deploy_to, '/homez.114/comebacknu'
set :repo_url, 'https://github.com/Psyker/ComeBack-Immo.git'
set :ssh_user, 'comebacknu-deploy'

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
set :format_options, command_output: true, log_file: 'var/logs/capistrano.log', color: :auto, truncate: :auto

# Default value for :log_level is :debug
set :log_level, :info

# Default path to PHP-cli
SSHKit.config.command_map[:php] = '/usr/local/php7.0/bin/php'

# Composer
SSHKit.config.command_map[:composer] = '/usr/local/php7.0/bin/php /homez.114/comebacknu/bin/composer'
set :composer_install_flags, '--no-dev --prefer-dist --no-interaction --optimize-autoloader --quiet'

# Default value for :linked_files is []
set :linked_files, %w{app/config/parameters.yml}

# Default value for linked_dirs is []
set :linked_dirs, %w{vendor var/logs}

set :tmp_dir, "/homez.114/comebacknu/tmp"

# Remove app_dev.php & config.php during deployment
set :controllers_to_clear, ["app_*.php", "config.php"]

set :keep_releases, 3
