name "vagrant-webdev-box"

run_list(
    "recipe[apt]",
    "recipe[build-essential]",
    "recipe[openssl]",
    "recipe[apache2]",
    "recipe[apache2::mod_php5]",
    "recipe[apache2::mod_ssl]",
    "recipe[php]",
    "recipe[php::module_mysql]",
    "recipe[php::module_sqlite3]",
    "recipe[php::module_gd]",
    "recipe[php::module_fpdf]",
    "recipe[php::module_curl]",
    "recipe[webdev::apt_packages]",
    "recipe[webdev::vhost]",
    "recipe[webdev::php]",
    "recipe[webdev::mysql]"
)
