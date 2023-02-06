#!/bin/bash

set -e

##################################################################################################
# Variables
##################################################################################################
MAX_RELEASES=3
DEPLOY_PATH="/var/www/$DOMAIN_NAME"
RELEASES_PATH="$DEPLOY_PATH/releases"
RELEASE_PATH="$DEPLOY_PATH/release"
CURRENT_PATH="$DEPLOY_PATH/current"
SHARED_PATH="$DEPLOY_PATH/shared"
WEB_GROUP="www-data"
WEB_USER="www-data"
RSYNC_EXCLUDE=(
  ".env"
  ".git"
  ".idea"
  "storage"
  "deploy.sh"
)
SHARED_DIRS=(
  "storage"
)
SHARED_FILES=(
  ".env"
)
CUSTOM_COMMANDS=(
  "php artisan migrate"
  "php artisan optimize"
  "php artisan storage:link"
)
AFTER_LINK_COMMANDS=(
  "sudo systemctl restart supervisord"
  "sudo supervisorctl restart all"
)
WRITABLE_DIRS=(
  ".next"
  "build"
  "node_modules"
  "storybook-static"
  "public"
)
PERMISSION_DIRS=(
  "$CURRENT_PATH"
  "$CURRENT_PATH/*"
  "$SHARED_PATH"
)

##################################################################################################
# Runtime variables
##################################################################################################
TIMESTAMP=$(date +%Y.%m.%d_%H:%M:%S)

##################################################################################################
# Helper functions
##################################################################################################
CI_COLOR="\033[0;32m"
NO_COLOR="\033[0m"

print_title() {
  echo ""
  print_row "$@"
}

print_row() {
  echo -e "${CI_COLOR}$@${NO_COLOR}"
}

##################################################################################################
# Ssh functions
##################################################################################################
execute_via_ssh() {
  ssh "$REMOTE_USER@$SERVER_IP" "$1"
}

##################################################################################################
# Deploy functions
##################################################################################################
check_directories() {
  print_row "Checking base directories on the remote server"
  execute_via_ssh "sudo mkdir -p $DEPLOY_PATH"
  execute_via_ssh "sudo mkdir -p $RELEASES_PATH"
  execute_via_ssh "sudo mkdir -p $SHARED_PATH"
}

prepare_release() {
  print_row "Create directory for the new release $TIMESTAMP"
  execute_via_ssh "sudo mkdir -p $RELEASES_PATH/$TIMESTAMP"
  execute_via_ssh "sudo ln -sfn $RELEASES_PATH/$TIMESTAMP $RELEASE_PATH"
}

symlink() {
  print_row "Create symlink for $RELEASES_PATH/$TIMESTAMP to $CURRENT_PATH"
  execute_via_ssh "sudo ln -sfn $RELEASES_PATH/$TIMESTAMP $CURRENT_PATH"
}

clear_old_releases() {
  print_row "Delete old releases"

  REMOTE_RELEASES=$(execute_via_ssh "ls -l $RELEASES_PATH | grep -c ^d")
  if [ "$REMOTE_RELEASES" -gt "$MAX_RELEASES" ]; then
    # shellcheck disable=SC2046
    while [ $(execute_via_ssh "ls -l $RELEASES_PATH | grep -c ^d") -gt $MAX_RELEASES ]; do
      DEPRECATED_RELEASE=$(execute_via_ssh "ls -t $RELEASES_PATH | tail -n 1")
      execute_via_ssh "sudo rm -rf $RELEASES_PATH/$DEPRECATED_RELEASE"
      print_row "Deleted release $DEPRECATED_RELEASE"
    done
  fi
}

deploy() {
  for idx in ${!RSYNC_EXCLUDE[*]}; do
    RSYNC_EXCLUDE[idx]="--exclude=${RSYNC_EXCLUDE[idx]}"
  done

  print_row "Deploying application..."
  rsync -raz --stats --rsync-path="sudo rsync" --delete "${RSYNC_EXCLUDE[@]}" './' "$REMOTE_USER@$SERVER_IP:$RELEASE_PATH"

  for idx in ${!SHARED_DIRS[*]}; do
    print_row "Setting shared dir ${SHARED_DIRS[idx]}"
    execute_via_ssh "sudo mkdir -p $SHARED_PATH/${SHARED_DIRS[idx]}"
    # shellcheck disable=SC2046
    if [ $(execute_via_ssh "[ -d $RELEASE_PATH/${SHARED_DIRS[idx]} ]") ]; then
      {
        execute_via_ssh "sudo cp -r $RELEASE_PATH/${SHARED_DIRS[idx]} $SHARED_PATH/${SHARED_DIRS[idx]}"
      }
    fi
    execute_via_ssh "sudo rm -rf $RELEASE_PATH/${SHARED_DIRS[idx]}"
    execute_via_ssh "sudo ln -svfn $SHARED_PATH/${SHARED_DIRS[idx]} $RELEASE_PATH/${SHARED_DIRS[idx]}"
  done

  for idx in ${!WRITABLE_DIRS[*]}; do
    print_row "Prepare writable dir ${WRITABLE_DIRS[idx]}"
    execute_via_ssh "sudo mkdir -p $RELEASE_PATH/${WRITABLE_DIRS[idx]}"
    execute_via_ssh "sudo chmod -R 777 $RELEASE_PATH/${WRITABLE_DIRS[idx]}"
  done

  for idx in ${!SHARED_FILES[*]}; do
    print_row "Setting shared files ${SHARED_FILES[idx]}"
    execute_via_ssh "sudo ln -svfn $SHARED_PATH/${SHARED_FILES[idx]} $RELEASE_PATH/${SHARED_FILES[idx]}"
  done

  for idx in ${!CUSTOM_COMMANDS[*]}; do
    print_row "Executing command ${CUSTOM_COMMANDS[idx]}"
    execute_via_ssh "cd $RELEASE_PATH && ${CUSTOM_COMMANDS[idx]}"
  done

  print_row "Application is deployed"
}

permissions() {
  for idx in ${!PERMISSION_DIRS[*]}; do
    print_row "Setting dir ownership ${PERMISSION_DIRS[idx]}"
    execute_via_ssh "sudo chown -R ${WEB_GROUP}:${WEB_USER} ${PERMISSION_DIRS[idx]}"
  done
}

cleanup() {
  print_row "Remove release symlink"
  execute_via_ssh "sudo rm $RELEASE_PATH"
}

restart_services() {
  for idx in ${!AFTER_LINK_COMMANDS[*]}; do
    print_row "Executing command ${AFTER_LINK_COMMANDS[idx]}"
    execute_via_ssh "cd $CURRENT_PATH && ${AFTER_LINK_COMMANDS[idx]}"
  done
}
##################################################################################################
# Deploy flow
##################################################################################################
print_title "Deploy has started."
check_directories
prepare_release
deploy
symlink
restart_services
permissions
clear_old_releases
cleanup
