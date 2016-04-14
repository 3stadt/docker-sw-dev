> __Attention__
> This docker setup was put together on a linux system, so on other systems there may be some uncovered scenarios. Feel free to contribute!

## Prerequisites

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

## Configuration

First, a quick overview of the project files.

```bash
.
├── apache-php5.6-ioncube-build # Build folder for php with ioncube
│   ├── php # A php.ini inside here will be integrated in the build
│   └── Dockerfile # Build instructions, extending from php:5.6-apache
├── apache-php7-build # Build folder for latest php, but without ioncube
│   ├── php # A php.ini inside here will be integrated in the build
│   └── Dockerfile # Build instructions, extending from php:7-apache
├── mysql
│   ├── conf.d # Mysql config files in here will be integrated in the build
│   └── data # The place where the mysql container stores it's data
├── mysql-build
│   └── Dockerfile # Build file for the mysql container, extends mariadb
├── antconf # bash script: Configures ant. Use 'mysql' as mysql host!
├── bu # bash script: executes a build unit
├── clear_cache # bash script: executes 'clear_cache.sh'
├── _delete_all_images_and_containers.sh # bash script
├── docker-compose.yml.dist # The main config file. You want to copy & change this!
├── reset # bash script: resets your containers
├── setperms # bash script: Needs to be executed after changes from inside the container (linux only (?))
├── start # bash script: starts all containers
├── stop # bash script: stops all containers
└── variables.cfg.dist # bash script: Optional: copy & change this!

```

- copy the file `docker-compose.yml.dist` to `docker-compose.yml`
  - If you want to, change all entries regarding `ports`
    - The format is localport:containerport, only change the localport
  - Change the `volumes` entry that reads `- ~/Code/shopware:/var/www/html`
    - You have to change the part before `:`, it has to point to your shopware root dir
  - change `MYSQL_ROOT_PASSWORD`and `PMA_PASSWORD` to the same value
- Execute the script `start` or alternatively do `docker-compose up -d`
- Execute the script `antconf` or alternatively do `docker-compose run -eANT_OPTS=-D"file.encoding=UTF-8" swag_apache ant -f /var/www/html/build/build.xml configure`.

Generally speaking, if you can't or don't want to use the provided scripts, take a look inside the files and execute the commands by hand.

## Usage

For starting and stopping use the corresponding scripts. If everything goes wrong, use `_delete_all_images_and_containers` - be aware that this globally affects all docker images and containers.

After switching branches or if you feel like it, use `bu` to do a build unit. To switch between php5.6 and php7, change the line that reads `build: ./apache-php7-build` to `build: ./apache-php5.6-ioncube-build` and execute `docker-compose build` after stopping all containers. Do a `bu` afterwards.