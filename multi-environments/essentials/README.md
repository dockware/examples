# Multi-Environments with dockware/essentials

This is a sample for environments with multiple Shopware versions.
The example is based on dockware/essentials and just creates a network of fully installed and prepared Shopware versions.
The initialization includes custom scripts to fully install a Shopware 6 version during the setup.


Please make sure to have these entries in your /etc/hosts file.

```ruby 
127.0.0.1    shop1.shopware.com
127.0.0.1    shop2.shopware.com
```


Now just run the init command in the makefile to prepare
the domains for both Shopware shops.

```ruby 
make init
```

That's it, both your shops are now available at https://shop1.shopware.com and https://shop2.shopware.com


### Development

This sample is based on the SFTP approach (it works for everyone), but feel free to change it to bind-mounting, Docker-Sync or whatever you want to use.
You can find the ports to both SFTP and MySQL in the docker-compose.yml file.


### Watchers

You can also run the watchers with these commands for both shops at the same time.

> Please note, for the storefront watchers, you need a http://localhost domain in the sales channels. These need to be created manually

```ruby 
make watch-storefront shop=shop1

make watch-admin shop=shop1
```

URL for Administration watcher: https://shop1.shopware.com:8888

URL for Storefront watcher: https://shop1.shopware.com:9998