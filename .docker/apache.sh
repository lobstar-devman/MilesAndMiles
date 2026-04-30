#!/bin/bash
set -e

# Point image Apache document root to app document root
sudo rm -rf /var/www/html
chown $USER:www-data $REPO_ROOT/public
sudo ln -s $REPO_ROOT/public /var/www/html