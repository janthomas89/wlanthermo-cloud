#
# Cookbook Name:: webdev
# Recipe:: apt_packages
#

include_recipe "apt"

# Setup nodejs
bash "setup nodejs" do
  code <<-EOH
  curl -sL https://deb.nodesource.com/setup | bash -
  EOH
end

# Install apt packages
node['webdev']['apt_packages'].each do |a_package|
  package a_package
end

# Install forever via npm
bash "Install forever via npm" do
  code <<-EOH
  npm install forever -g
  EOH
end