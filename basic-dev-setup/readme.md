## Basic Dev Setup

Welcome to the basic dockware DEV sample.
This setup helps you to get started as easy as possible.

Please use the matching makefiles for your operating system and continue with the steps described below.

> Please note, this setup uses an isolated image without bind mounting.
> This SFTP approach is an option that should work in all scenarios.
> You can of course change that to be bind mounting if its still fast and good for you.
> In that case, some steps might not be necessary.



## 1. Start Containers
Start the docker containers with the Docker default command in detached mode:

```ruby
docker-compose up -d
```



## 2. Download Source / IDE

> SFTP Version

Let's get that dockware source code on your host to work with it and have some smooth auto completion in your IDE.

This command will download the whole source code into a separate "src" on your host.
You can then just open that one with your preferred IDE.

```ruby
make download
```



Now just create your SFTP Deployment in your IDE with these settings:

| Setting | Value |
| - | - |
| Host | localhost |
| Port | 22 |
| User | dockware |
| Password | dockware |
| Remote Path | /var/www/html |

More about default credentials can be found here: https://dockware.io/docs#default-credentials
