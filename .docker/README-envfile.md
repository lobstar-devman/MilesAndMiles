# Docker `compose.yaml` local .env file required settings

ref: [Set environment variables within your container's environment](https://docs.docker.com/compose/how-tos/environment-variables/set-environment-variables/)

```bash
# Docker image mode
CONTAINER_NAME=<docker container name>
IMAGE_MODE=setup

# GitHub SSH Key (it's location on your dev machine)
GITHUB_SSH=~/.ssh/github/<OPENSSH PRIVATE KEY>

# GitHub User Config
GITHUB_USERNAME=<username>
GITHUB_EMAIL=<username>@users.noreply.github.com

#App Repo Settings
APP_REPO= # Leave as empty during setup mode
APP_BRANCH= # Defaults to main

# App settings
APP_URL=thesite.eu.ngrok.io

# ngrok
NGROK_AUTHTOKEN=<ngrok token>

# Postgres user/password
PGUSER=<PGUSER>
PGPWD=<PGPWD>
```

# Additional .env file settings

```bash
#End-to-end test Repo Settings
E2E_REPO=git@github.com:<org>/<e2e-repo>.git
APP_BRANCH=<e2e-branch> #This is the initial branch to use during build

# Heroku
HEROKU_API_KEY=

# E2E test urls
E2EURLS={"default":{"baseURL":"https://thesite.eu.ngrok.io","authURL":"<auth0 login url>"}}
```