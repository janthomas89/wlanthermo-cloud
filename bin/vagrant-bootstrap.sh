#!/usr/bin/env bash

# Install chef solo, if it is not installed yet
command -v chef-solo >/dev/null 2>&1 || { 
	curl -L https://www.opscode.com/chef/install.sh | bash
}
