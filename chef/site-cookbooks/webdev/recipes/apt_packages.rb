#
# Cookbook Name:: webdev
# Recipe:: apt_packages
#

include_recipe "apt"

# Install apt packages
node['webdev']['apt_packages'].each do |a_package|
  package a_package
end