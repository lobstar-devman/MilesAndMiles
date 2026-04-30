
# Create new Laravel app and push to GitHub

## Build Modes

The .env file is initially set to `IMAGE_MODE=setup`.
When `docker compose build` is run this will create the final image with a Laravel installer.

Once in the image terminal run:
```bash
/host/run-image-mode-setup.sh
```

This will run the remaining steps which will :
- Install Laravel
- Install Boost
- Copy the host files used in the build to the /workspace/app/.docker folder
- Ask for a new GitHub repo name and then *init*, *add* and *push* it
- Update the .env file in /workspace/app/.docker/.env to run any future `docker compose build` commands in _baked mode_.

### What is _baked_ mode?

When the .env file is in `IMAGE_MODE=baked` mode it will have the `APP_REPO` envvar set.
When `docker compose build` is next run the Docker file will not follow the *setup* image path above and the Laravel installer will not be included in the image.

Instead, the Git `APP_REPO` will be cloned. When the container is next run, the `entrypoint` script will pull down the latest version of the repo, switching to the `APP_BRANCH`.

## Making changes to the host files

Docker build steps are cached, changes to external files do no force a rebuild.

To force a rebuild of this step increment the `ARG image-mode-setup_BUMP=` command in the docker file