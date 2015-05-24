#
# Cookbook Name:: webdev
# Recipe:: mysql
#

include_recipe "apt"

# Install MySQL client and server
mysql_service 'default' do
  port '3306'
  version '5.5'
  initial_root_password "#{node['mysql']['server_root_password']}"
  action [:create, :start]
end

#mysql_config 'webdev' do
#  source 'mysql_settings.erb'
#  notifies :restart, 'mysql_service[webdev]'
#  action :create
#end

mysql_client 'default' do
  action :create
end

# Create vhosts database
ruby_block "create_#{node['webdev']['vhost_name']}_db" do
    block do
        %x[mysql -h 127.0.0.1 -uroot -p#{node['mysql']['server_root_password']} -e "CREATE DATABASE #{node['webdev']['mysql_db_name']};"]
    end 
    not_if "mysql -h 127.0.0.1 -uroot -p#{node['mysql']['server_root_password']} -e \"SHOW DATABASES LIKE '#{node['webdev']['mysql_db_name']}'\" | grep #{node['webdev']['mysql_db_name']}";
    action :create
end

# Load database skeleton if skeleton file exists and database is empty
if File.exist?("#{node['webdev']['mysql_db_skeleton']}")
   ruby_block "seed #{node['webdev']['vhost_name']} database" do
       block do
           %x[mysql -h 127.0.0.1 -u root -p#{node['mysql']['server_root_password']} #{node['webdev']['mysql_db_name']} < #{node['webdev']['mysql_db_skeleton']}]
       end
       not_if "mysql -h 127.0.0.1 -u root -p#{node['mysql']['server_root_password']} -e \"SHOW TABLES FROM #{node['webdev']['mysql_db_name']}\" | \
           grep 1"
       action :create
   end
end

# Install and enable phpMyAdmin
package "phpmyadmin"

bash "enable phpMyAdmin" do
  code <<-EOH
  ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-enabled/phpmyadmin.conf
  if ! grep -q "# chefenablepma" "/etc/apache2/conf-enabled/phpmyadmin.conf"; then
    sed -i '/<Directory \\/usr\\/share\\/phpmyadmin>/a  Allow from all' /etc/apache2/conf-enabled/phpmyadmin.conf 
    sed -i '/<Directory \\/usr\\/share\\/phpmyadmin>/a  Order allow,deny' /etc/apache2/conf-enabled/phpmyadmin.conf 
    sed -i '/<Directory \\/usr\\/share\\/phpmyadmin>/a  # chefenablepma' /etc/apache2/conf-enabled/phpmyadmin.conf 
    /etc/init.d/apache2 reload
  fi
  sed -i "s/'localhost';/'127.0.0.1';/" /etc/phpmyadmin/config.inc.php
  EOH
end
