## Boostday Setup

Welcome to the Shopware Boostday.
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





## 3. GIT Management

Your dockware container comes with 2 repositories of Shopware 6.
The "development template" along with a symlinked "platform" folder.

Time to add that to your favourite GIT client if you use one.

Before we continue, we have to switch to our `Boostday` repository.

Just add a new remote in the platform repository for this one:
https://github.com/shopwareBoostDay/platform

Afterwards please checkout the `master` of the Boostday repository.



## 4. Pull Repositories

Now that our GIT repositories are ready, we want to ensure that we are up to date. 
This is also recommended before starting new tasks.

Either use your GIT client or use the prepared make command to update both 
repositories while having a clean working directory.

```ruby 
make pull
```



## 5. Upload / Run

> SFTP Version

If you use the SFTP version it could be that your local GIT repositories have changed either due to pulling or switching of branches. That means you have to update your container files.
There is a simple command to do this:

```ruby 
make upload
```



Please note, that depending on the changes from others, it could be that the database has changed or any other more important parts. Thus, we provide a simple `run` command that uploads everything, installs
Shopware for you and opens the browser after its completed.

If you want to grab a cup of coffee and just let it do everything for you, then use this command:

```ruby 
make run
```



## 6. SSH
We're not a big fan of wrapping things you normally need to know, but if you want a quick option to connect into your container, then just run this:

```ruby 
make ssh
```



## 7. Testing

Shopware has lots of PHPUnit tests...great, right!
But what if you want to run it? 

Then you can simply use this command and either run all tests or the one from your provided group

```ruby 
make test
make test group=promotions
```



## 8. PR
> SFTP Version or Bind-Mount without "docker cp"

If you are done developing you could already create a pull request.
The Shopware static analyzers make sure to verify your code when creating pull requests.
If you want to be prepared for the first "problems", you can already run this locally.

```ruby 
make pr
```

This command will run everything for you and also download the source code again with all applied modifications. If you commit before this, you should see what has been fixed after running this command - then you can just amend that.

Please note, if you are not using the SFTP variant, you might want to start it with this parameter to avoid your files being downloaded - because they are already mounted.

```ruby 
make pr mounted=1
```

