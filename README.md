> __Attention__
> This docker setup was put together on a linux system, so on other systems there may be some uncovered scenarios. Feel free to contribute!

# Prerequisites

- [Docker](https://docs.docker.com/) including [docker-compose](https://docs.docker.com/compose/install/)

Check if your system is meeting the prerequisites (versions may vary):

```bash
$ docker --version
Docker version 1.12.1, build 23cf638
```

```bash
$ docker-compose --version
docker-compose version 1.8.0-rc2, build c72c966
```

# Configuration

First, a quick overview of the project files.

```bash
.
.
├── assetgenerator                       # Script like lorempixel, can be used in Mink Tests
├── datagrip-data                        # Data directory for jetbrains datagrip container
├── dnsmasq.d                            # Config directory for dnsmasq container
├── elasticsearch                        # Config directory for elasticsearch
├── images
│   └── build*                           # multiple image build directories
├── nginx                                # Config directory for nginx container
├── sw-cli-tools                         # config directory for sw tools, mounted in swag_cli
├── _delete_all_images_and_containers.sh # Deletes all docker containers and images on your system (beta)
├── docker-base.yml.dist                 # base image definitions, used for extending
├── docker-compose-nginx.yml.dist        # standalone ngnix stack definition
├── docker-compose-testing.yml.dist      # base extension, includes selenium
├── docker-compose.yml                   # default base extension for shopware
├── README.md                            # The file your are currently reading
└── _update_all_docker_images.sh         # updates all docker images globally (beta)

```

- **copy** the `*.yml.dist` files to `*.yml`
  - Change the content of the `.yml` files according to your needs
  - **If you don't customize these files, errors will occur**, mostly because of incorrect paths
- run `docker-compose build` in the root directory. This will take some time, but is only required on first run, changes to a `Dockerfile` and when switching PHP versions.


## Changing PHP Versions, usage of XDebug and Ioncube

The changing of versions can be done via environment variables (default) or directly inside the yaml files.

To change directly inside the files, edit `docker-compose.yml` line 4 in a text editor: 

``` yaml
version: '2'
services:
  swag_apache:
    build: ./images/build-apache-php<image name part>
```

Replace the part which reads `build-apache-php<image name part>` (at least in the example above) to the name of any of the other Apache/PHP build folders, e.g. `build-apache-php7`. Then run `docker-compose build` in the directory where the `docker-compose.yml` file is located.

When switching to a PHP version, the first time you do this the build process will take some time.

# Usage

## Starting and stopping, and other commands

To start, stop and maintain your containers, you use simple `docker-compose` and `docker` commands.

The difference between testing and dev environments is: The testing environments are staring a selenium instance for running Mink tests and for performance reasons they do not contain XDebug.

Dev environment start: `docker-compose up -d --force-recreate`
Testing environment start: `docker-compose up -f docker-compose-testing.yml -d --force-recreate`

Dev environment stop: `docker-compose stop`
Testing environment stop: `docker-compose -f docker-compose-testing.yml stop`

Please note: Testing and dev environment must not be started at the same time.

There are other useful commands listed below. Please note there is a cli tool `swdc` which simplifies the usage of these commands. Refer to user xenomorph in #shopware-offtopic on freenode for further questions.

```bash
#Perform ant-configure on a project
docker-compose run -eANT_OPTS=-D"file.encoding=UTF-8"-u1000 \
swag_cli ant -f /var/www/html/<PROJECTFOLDER>/build/build.xml configure

#Perform 'ant build-unit' on a project
docker-compose run -eANT_OPTS=-D"file.encoding=UTF-8" -u1000 \
swag_cli ant -f /var/www/html/<PROJECTFOLDER>/build/build.xml build-unit

#Clear the chaches of a project
docker-compose run -u1000 \
swag_cli /var/www/html/<PROJECTFOLDER>/var/cache/clear_cache.sh

```

Replace `<PROJECTFOLDER>` with the folder name of a shopware installation.

*Please note the -u1000 part:* This guide assumes you are using linux. The user id 1000 is usually your user id. If this is not the case, you have to change this value in the commands and the Dockerfiles!

## SW Tools

The sw tools are included in swag_cli and can be used like in the above examples.

## Serving content

The apache container is configured in a way that it uses the domain name to look up the actual folder it should serve files from.

Example: You've set the entry `- ~/Code:/var/www/html` (see above) to `- /my/folder:/var/www/html` and then create `/my/folder/foobar` on your system. Then you edit your hosts file to point `foobar.localhost` to `127.0.0.1` and visit `http://foobar.localhost` in your browser.

Apache now automagically serves the content of `/my/folder/foobar` to your browser.

## Xdebug

Xdebug is available using one of the `-xdebug` images (see images folder), but not active by default in order to save performance.
To use it, you need to set up your IDE. In this readme, only PHPStorm will be covered, if you are using a different IDE you are welcome to adapt the instructions and make a pull request to update this readme.

First, you need to setup a server. Within PHPStorm, navigate to `File` > `Settings` > `Language & Frameworks` > `PHP` > `Servers` and add a new one via the `+` icon or use your existing one.
Make sure `Host` and `Port` are set up correctly, these values depend on your system setup. `Debugger` has to be set to `Xdebug`.
For your `Project Files`, set the `Absolute Path on the server` to `/var/www/html`. Apply/Save.

Next, switch to `File` > `Settings` > `Languages & Frameworks` > `PHP` > `Debug` and make sure `Ignore external connections through unregistered server configurations` is _not_ selected while `Xdebug – Can accept external connections` must be selected. Apply/Save.

Now close the settings menu and navigate to `Run` > `Edit configurations`. Add a new `PHP Remote Debug` configuration via the green `+` sign on the top left.
Name it however you want, then select your server from the last step. You can freely choose the `Ide key(session id)`, e.g. _XDEBUG_PHPSTORM_. Apply/Save.

### Usage

To start a session, first set breakpoints. Then, activate the debug configuration, either via the run menu or by choosing and starting a configuration at the top right in PHPStorm. A Debugger window shoul pop up inside PHPStorm.

As final step load your site in the browser and add your IDE key. Example: `http://shopware.localhost/?XDEBUG_SESSION_START=XDEBUG_PHPSTORM` where `XDEBUG_PHPSTORM` is the IDE key you've choosen during configuration.