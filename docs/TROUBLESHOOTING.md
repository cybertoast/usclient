## Installing PHPUnit

    pear config-show | grep -i ini
    # Look at the bottom of the list for the conf files
    pear config-set php_ini /etc/php.ini
    sudo cp /private/etc/pear.conf ~/.pearrc
    sudo chown sundar ~/.pearrc 
    pear upgrade-all
    pear uninstall phpunit
    pear uninstall phpunit/PHPUnit
    pear uninstall symfony2/Yaml
    pear install --alldeps phpunit/PHPUnit

