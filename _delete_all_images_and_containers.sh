#!/bin/bash

printf "\n\nThis will destroy EVERY SINGLE IMAGE AND CONTAINER ON THE WHOLE SYSTEM!!! \n\n Are you sure? Then type YES and press enter: "
read DOIT

if [ $DOIT == "YES" ]; then
    # Delete all containers
    docker rm $(docker ps -a -q)
    # Delete all images
    docker rmi $(docker images -q)
else
	printf "Not sure? Fine, exiting...\n"
fi