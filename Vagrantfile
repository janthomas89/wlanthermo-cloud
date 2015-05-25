# -*- mode: ruby -*-
# vi: set ft=ruby :

# The VHOSTs / projects settings
vhost_name        = "www.wlanthermo-cloud.de"
vhost_user        = "root"
vhost_password    = "root"
mysql_db_name     = "wlanthermo"
mysql_db_skeleton = ""
apt_packages      = %w{ screen curl subversion nodejs }
php_packages      = %w{ php5-memcached php5-xdebug }


# Vagrantfile API/syntax version
VAGRANTFILE_API_VERSION = "2"


Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # Define VM box
  config.vm.box = "chef/debian-7.8"
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", 1024]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
  end

  # Configure Network
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 443, host: 4443
  config.vm.network :forwarded_port, guest: 3306, host: 8006
  #config.vm.network :private_network, ip: "192.168.0.25"
  # config.vm.network :public_network


  # Configure synced folders
  config.vm.synced_folder "./symfony-app" , "/var/www/sites/" + vhost_name + "/", type: "rsync", rsync__exclude: [".git/","app/cache/","app/logs/","vendor/","app/bootstrap.php.cache"], owner: "www-data", group: "vagrant"


  # Configure Provisioning
  config.vm.provision :shell, path: "bin/vagrant-bootstrap"
  config.vm.provision :chef_solo do |chef|
    chef.roles_path     = "./chef/roles"
    chef.cookbooks_path = ["./chef/site-cookbooks", "./chef/cookbooks"]
    chef.add_role       "vagrant-webdev-box"

    # Configuration params
    chef.json = {
      :webdev => {
        :vhost_name         => vhost_name,
        :vhost_root         => "/var/www/sites/" + vhost_name,
        :mysql_db_name      => mysql_db_name,
        :mysql_db_skeleton  => mysql_db_skeleton,
        :apt_packages       => apt_packages,
        :php_packages       => php_packages
      },

      :mysql => {
        :server_root_password   => vhost_password,
        :server_repl_password   => vhost_password,
        :server_debian_password => vhost_password,
        :allow_remote_root      => true
      },

      :phpmyadmin => {
        :home => "/usr/share/phpmyadmin",
        :user => "pma",
        :group => "pma",
        :fpm => false
      }
    }
  end

end
