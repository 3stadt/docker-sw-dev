FROM ubuntu:vivid

RUN apt-get update && \
    apt-get install -y software-properties-common wget git curl && \
    curl -sL https://deb.nodesource.com/setup_5.x | bash - && \
    apt-get update && \
    apt-get install -y graphviz \
        openjdk-8-jre libxext-dev libxrender-dev libxtst-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /tmp/* && \
    rm -rf /var/lib/apt/lists/*

RUN useradd -m -s /bin/bash developer \
    && mkdir /home/developer/.DataGrip2016.2 \
    && touch /home/developer/.DataGrip2016.2/.keep \
    && chown -R developer.developer /home/developer \
    && mkdir /opt/datagrip \
    && wget -O - https://download.jetbrains.com/datagrip/datagrip-2016.2.3.tar.gz | tar xzf - --strip-components=1 -C "/opt/datagrip"

USER developer