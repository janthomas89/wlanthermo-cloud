#
# Cookbook Name:: webdev
# Recipe:: vhost
#
include_recipe "apache2"

web_app "#{node['webdev']['vhost_name']}" do
  server_name node['webdev']['vhost_name']
  allow_override "all"
  docroot node['webdev']['vhost_root']
end

web_app "#{node['webdev']['vhost_name']}-ssl" do
  server_name "#{node['webdev']['vhost_name']}"
  server_port 443
  allow_override "all"
  docroot node['webdev']['vhost_root']
end

# Disable default VHOST
apache_site "000-default" do
  enable false
end
