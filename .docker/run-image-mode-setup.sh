#!/bin/bash
set -e

# https://stackoverflow.com/a/28938235
# Reset
Reset='\033[0m'          # Text Reset

# Bold High Intensity
BIRed='\033[1;91m'        # Red

###
## image-mode-setup final steps
##

if [ "$IMAGE_MODE" != "setup" ]; then
    read -p "\$IMAGE_MODE ENVVAR is not =\"setup\", Do you want to continue? ([Y]/n): " confirm && confirm=${confirm:-Y} && [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]] || exit 1
fi

# Install Laravel
cd /workspace

echo " "
echo " "
echo -e "${BIRed}****************************************************************************${Reset}"
echo -e "${BIRed}NOTE:${Reset} Do not install ${BIRed}Boost${Reset} - this will be done in a subsequent step."
echo -e "${BIRed}****************************************************************************${Reset}"
echo " "
laravel new app

# Copy over host .docker files and run build script
cd app
cp -r /host/. .docker

# Point image Apache document root to app document root
./.docker/apache.sh

echo " "
echo -e "${BIRed}***********************${Reset}"

if read -p "Install Boost? ([Y]/n): " confirm && confirm=${confirm:-Y} && [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]]; then
    # Install Boost
    composer require laravel/boost --dev
    php artisan boost:install
fi

echo " "
echo -e "${BIRed}************************************************************************${Reset}"
read -rp "Enter name of remote REPOSITORY: git@github.com:$GITHUB_USERNAME/<REPOSITORY>.git: " REPOSITORY

# Init git repo
if [ ! -z ${REPOSITORY} ]; then
    git init -b main
    git add .
    git commit -m "Project setup"

fi

# Update the .env file
sed -i "/CONTAINER_NAME=/c\CONTAINER_NAME="$REPOSITORY .docker/.env
sed -i '/IMAGE_MODE=/c\IMAGE_MODE=baked' .docker/.env
sed -i "\|#APP_KEY=|c\APP_KEY=$(awk -F= '$1 == "APP_KEY" {print $2}' .env)" .docker/.env
sed -i "\|APP_REPO=|c\APP_REPO=$(git config --get remote.origin.url)" .docker/.env

echo " "
echo -e "${BIRed}*************************${Reset}"

if read -p "Push $REPOSITORY to GitHub? ([Y]/n): " confirm && confirm=${confirm:-Y} && [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]]; then

    git remote add origin git@github.com:$GITHUB_USERNAME/$REPOSITORY.git
    #Error? try diagnosing with: git remote -v
    git push -u origin main

    echo " "
    echo -e "${BIRed}**************************${Reset}"
    echo -e "${BIRed}Pushed to remote repository:${Reset} $(git config --get remote.origin.url)"
    echo -e "${BIRed}**************************${Reset}"
fi

echo "🏁 Image Mode setup complete"
echo "🏁 .docker/.env has been setup to build a baked docker image."
echo "🏁 Copy this over your host .env file and re-build the image."
