## Install on local environment
1. `composer install`
2. `cp ./.env.example ./.env `
3. `php artisan key:generate`
4. Fill credentials for smtp, sendgrid, recaptcha
5. `php artisan migrate`

Start local environment = `./vendor/bin/sail up -d`
Stop local environment = `./vendor/bin/sail down`

## Install on remote environment
1. Prepare remote environment using cookbooks - https://bitbucket.org/Trainee_abz/test2023_backend_ivan_t_cookbooks/src/master/
2. Enable pipelines in Repository settings > Pipelines > Settings
3. Add ssh key in Repository settings > Pipelines > SSH keys
4. Set variables in Repository settings > Pipelines > Deployments for Staging 
   - `SERVER_IP`
   - `DOMAIN_NAME`
   - `REMOTE_USER`
5. Run pipeline
