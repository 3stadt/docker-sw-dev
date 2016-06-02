> __Attention__
> This docker setup was put together on a linux system, so on other systems there may be some uncovered scenarios. Feel free to contribute!

# Prerequisites

- [Docker](https://docs.docker.com/) including docker-compose

Check if your system is meeting the prerequisites (versions may vary):

```bash
$ docker --version                                                                                                                                                                                           2.2.3
Docker version 1.10.3, build 20f81dd
```

```bash
$ docker-compose --version                                                                                                                                                                                   2.2.3
docker-compose version 1.6.2, build 4d72027
```

# Configuration

First, a quick overview of the project files.

```bash
.
├── build-* #multiple build folders for all containers
├── _delete_all_images_and_containers.sh #Only use if you understand the source
├── docker-base.yml.dist #customize and rename to *.yml
├── docker-compose-testing.yml.dist #customize and rename to *.yml
├── docker-compose.yml #Entry point for production containers
├── mysql
│   ├── conf.d #Holds optional mysql ini files
│   └── data #will contain mysql data files
└── README.md #The file your are reading right now

```

- copy the file `docker-base.yml.dist` to `docker-base.yml`
  - If you want to, change all entries regarding `ports`
    - The format is localport:containerport, only change the localport
  - Change the `volumes` entry that reads `- ~/Code:/var/www/html`
    - You have to change the part before `:`, it has to point to the folder your shopware root foolder resides in. Note: Not the shopware folder itself!
  - change `MYSQL_ROOT_PASSWORD`and `PMA_PASSWORD` to the same value

# Usage

## Starting and stopping, and other commands

To start, stop and maintain your containers, you use simple `docker-compose` and `docker` commands.

Dev environment start: `docker-compose up -d --force-recreate`
Testing environment start (no xdebug, but with selenium): `docker-compose up -f docker-compose-testing.yml -d --force-recreate`

Dev environment stop: `docker-compose stop`
Testing environment stop: `docker-compose -f docker-compose-testing.yml stop`

Please note: Testing and dev environment shouldn't be started at the same time

There are other useful commands listed below. Please note there is a GO tool `swdc` which simplifies the usage of these commands. Refer to n.dzoesch@shopware.com for further questions.

```bash
#Perform ant-configure on a project
docker-compose run -eANT_OPTS=-D"file.encoding=UTF-8"-u1000 swag_cli ant -f /var/www/html/<PROJECTFOLDER>/build/build.xml configure

#Perform 'ant build-unit' on a project
docker-compose run -eANT_OPTS=-D"file.encoding=UTF-8" -u1000 swag_cli ant -f /var/www/html/<PROJECTFOLDER>/build/build.xml build-unit

#Clear the chaches of a project
docker-compose run -u1000 swag_cli /var/www/html/<PROJECTFOLDER>/var/cache/clear_cache.sh

```

Replace `<PROJECTFOLDER>` with the folder name of a shopware installation.

## SW Tools

At the time being the sw tools are not included and have to be installed outside the containers.

## Serving content

The apache container is configured in a way that it uses the domain name to look up the actual folder it should serve files from. Example: You've set the entry `- ~/Code:/var/www/html` (see above) to `- /my/folder:/var/www/html` and then create `/my/folder/foobar` on your system. Then you edit your hosts file to point `foobar.localhost` to `127.0.0.1` and visit `http://foobar.localhost` in your browser. Apache now dynamically serves the content of `/my/folder/foobar` to your browser.

## Xdebug

Xdebug is installed inside the swag_apache container, but not active by default in order to save performance.
To use it, you need to set up your IDE. In this readme, only PHPStorm will be covered, if you are using a different IDE you are welcome to adapt the instructions and make a pull request to update this readme.

First, you need to setup a server. Within PHPStorm, navigate to `File` > `Settings` > `Language & Frameworks` > `PHP` > `Servers` and add a new one via the `+` icon or use your existing one.
Make sure `Host` and `Port` are set up correctly, these values depend on your system setup. `Debugger` has to be set to `Xdebug`.
For your `Project Files`, set the `Absolute Path on the server` to `/var/www/html`. Apply/Save.

Next, switch to `File` > `Settings` > `Languages & Frameworks` > `PHP` > `Debug` and make sure `Ignore external connections through unregistered server configurations` is _not_ selected while `Xdebug – Can accept external connections` must be selected. Apply/Save.

Now close the settings menu and navigate to `Run` > `Edit configurations`. Add a new `PHP Remote Debug` configuration via the green `+` sign on the top left.
Name it however you want, then select your server from the last step. You can freely choose the `Ide key(session id)`, e.g. _XDEBUG_PHPSTORM_. Apply/Save.

### Usage

To start a session, first set breakpoints. Then, activate the debug configuration, either via the run menu or by choosing and starting a configuration at the top right in PHPStorm. A Debugger window shoul pop up inside PHPStorm.

As final step load your site in the browser and add your IDE key. Example: `http://shopware.local/?XDEBUG_SESSION_START=XDEBUG_PHPSTORM` where `XDEBUG_PHPSTORM` is the IDE key you've choosen during configuration.