#!/bin/bash
docker images | awk '{print $1}' | xargs -L1 docker pull