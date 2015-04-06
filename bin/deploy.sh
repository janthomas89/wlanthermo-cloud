#!/usr/bin/env bash

# Constants needed during deployment
BASEPATH=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
ERRORSTRING="[Error] Make shure to call deploy script like ./deploy.sh live [push]"

# Live environtmenr
LIVE_SSH_USER="root"
LIVE_SSH_HOST="217.160.126.183"
LIVE_SSH_DEPLOY_DIR="/var/www/sites/wlanthermo.janthomas-software.de"

echo "[start] deploy script"

# Deploy via rsync
if [ $# -eq 0 ]
then
		echo "1"
		echo $ERRORSTRING
elif [ $1 == "live" ]
then
	if [[ -z $2 ]]
	then
		echo "[start] rsync dry-run"
		rsync --dry-run -avp -r --delete --progress --exclude-from=$BASEPATH/deploy-rsync-exclude -e ssh $BASEPATH/../symfony-app/ $LIVE_SSH_USER@$LIVE_SSH_HOST:$LIVE_SSH_DEPLOY_DIR
		echo "[done] rsync dry-run"
	elif [ $2 == "push" ]
	then
		echo "[start] rsync deployment"
		rsync -avp -r --delete --progress --exclude-from=$BASEPATH/deploy-rsync-exclude -e ssh $BASEPATH/../symfony-app/ $LIVE_SSH_USER@$LIVE_SSH_HOST:$LIVE_SSH_DEPLOY_DIR
		echo "[done] rsync deployment"

		# Call postdeployment.sh via SSH
		echo "[start] post deployment"
		ssh $LIVE_SSH_USER@$LIVE_SSH_HOST "chmod +x $LIVE_SSH_DEPLOY_DIR/bin/post-deploy"
		ssh $LIVE_SSH_USER@$LIVE_SSH_HOST "$LIVE_SSH_DEPLOY_DIR/bin/post-deploy"
		echo "[end] post deployment"
	else
		echo "2"
		echo $ERRORSTRING
	fi
else
	echo "3"
	echo $ERRORSTRING
fi

echo "[end] deploy script"