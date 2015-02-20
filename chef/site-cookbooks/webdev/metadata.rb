name              "webdev"
maintainer        "Jan Thomas"
maintainer_email  "jan.thomas@rwth-aachen.de"
description       "Cookbook for a common LAMP stack"
version           "0.1.0"

recipe "app", "Cookbook for a common LAMP stack"

depends "apt"
depends "apache2"
depends "php"
depends "phpmyadmin"

%w{ debian ubuntu }.each do |os|
  supports os
end