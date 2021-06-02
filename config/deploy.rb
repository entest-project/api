set :application, "dentest_api"
set :repo_url, "git@github.com:entest-project/api.git"
set :deploy_to, ENV['DEPLOY_DIR']
append :linked_files, ".env.prod"
append :linked_dirs, "var/logs", "var/log", "config/jwt"
set :keep_releases, 5

server ENV['DEPLOY_TO'], user: "deployer-agent"

task :setup_composer do
  on roles(:all) do |h|
    execute "cd #{current_path} && php -r \"copy('https://getcomposer.org/installer', 'composer-setup.php');\""
    execute "cd #{current_path} && php composer-setup.php"
    execute "cd #{current_path} && php -r \"unlink('composer-setup.php');\""
  end
end

task :install do
  on roles(:all) do |h|
     execute "cd #{current_path} && APP_ENV=prod ./composer.phar install"
     execute "cd #{current_path} && APP_ENV=prod ./bin/console doctrine:migrations:migrate --no-interaction"
  end
end

task :readable_dirs do
  on roles(:all) do |h|
     execute "cd #{current_path} && chmod -R 777 var/cache/"
  end
end

after "deploy:publishing", :setup_composer
after :setup_composer, :install
after :install, :readable_dirs
