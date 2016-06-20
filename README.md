wlanthermo-cloud
==============

Alternative Web Frontend for the Wlanthermo project <http://wlanthermo.com>.


1.) Dependencies
------------
- PHP 5.4+
    - php5-memcached
    - Curl
- MySQL
- Memcached
- NodeJS
- Composer
- PHP Opcode Cache of your choice (recommended)
- Vagrant (recommended for development)

Take a look at the vagrant and chef configuration for more details.


2.) Installation
------------
```
# First clone or Download this repository and setup the vagrant machine
git clone git@github.com:janthomas89/wlanthermo-cloud.git
vagrant up
vagrant ssh
```

```
# Install dependencies
cd /var/www/sites/www.wlanthermo-cloud.de
composer install
```

```
# Bootstrap the database
php app/console doctrine:schema:update --force
```

```
# Install temperature daemon
sudo cp bin/wlanthermo-daemon /etc/init.d/wlanthermo-daemon
sudo update-rc.d wlanthermo-daemon defaults
sudo /etc/init.d/wlanthermo-daemon start
```

```
# Install the healthcheck cronjob
sudo crontab -e -u www-data

# Add the folling lines to crontab
* * * * * /usr/bin/php /var/www/sites/www.wlanthermo-cloud.de/app/console app:healthcheck
```

Now you should be able to call ```http://127.0.0.1:8080/app.php``` in your local browser and see the wlanthermoCloud login screen.


3.) Google Cloud Messaging Notifications (GCM)
------------
The app uses GCM for temperature notofications (see Symfony service "gcm_service"). You will need a google api key in order to make this work. See https://support.google.com/googleplay/android-developer/answer/2663268?hl=en for more information.


4.) Deployment
------------
For live deployment you can use the provided deployment script (bin/deploy). It uses rsync over ssh. Copy bin/deploy.conf.dist to bin/deploy.conf and provide a valid ssh configuration for your live system. After deployment "bin/post-deploy" is called on the live system to ensure correct file permissions (see bin/post-deploy-fileperm for configuration), deploy assets and clear caches.


5.) Screenshots
------------
![Login](/screenshots/login.jpg?raw=true =450x)
![Devices](/screenshots/devices.jpg?raw=true =450x)
![History](/screenshots/history.jpg?raw=true =450x)
![Measurement](/screenshots/measurement.jpg?raw=true =450x)
![Responsive](/screenshots/responsive.jpg?raw=true =450x)