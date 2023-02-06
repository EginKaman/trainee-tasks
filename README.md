## Install on local environment
1. `composer install`
2. `cp ./.env.example ./.env `
3. Set in `.env` variable `PLATFORM` with your platform `amd64` or `arm64`
3. Start local environment = `./vendor/bin/sail up -d`
4. `./vendor/bin/sail artisan key:generate`
5. Fill credentials for smtp, sendgrid, recaptcha
6. Run migrations `./vendor/bin/sail artisan migrate`
7. Run the Vite development server `./vendor/bin/sail sail npm run dev`

Useful commands:

Start local environment = `./vendor/bin/sail up -d`

Stop local environment = `./vendor/bin/sail down`

Run the Vite development server `./vendor/bin/sail sail npm run dev`

Build and version the assets for production `./vendor/bin/sail sail npm run build`

## Install on remote environment
1. Prepare remote environment using cookbooks - https://bitbucket.org/Trainee_abz/test2023_backend_ivan_t_cookbooks/src/master/
2. Enable pipelines in Repository settings > Pipelines > Settings
3. Add ssh key in Repository settings > Pipelines > SSH keys
4. Set variables in Repository settings > Pipelines > Deployments for Staging 
   - `SERVER_IP`
   - `DOMAIN_NAME`
   - `REMOTE_USER`
5. Run pipeline
