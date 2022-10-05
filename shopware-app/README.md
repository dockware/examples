# Product Reviews (Sample App)

This is an easy sample for the Shopware 6 app system in combination with dockware (or any other Docker image).

It demonstrates the implementation of an external product review application.
All Shopware orders will be transferred to this external system.

Afterwards users can write product reviews in our external system, which are then
automatically transferred back to Shopware, where they can be seen on the product detail page.

Our goal is to offer a simple plug'n'play setup of a Shopware App with dockware containers.
You can use this for testing, as a dev-playground or just to see how it would work.

The backend service has been written in PHP from scratch. So no framework or anything else is used in there.
It's definitely no perfect integration, and no one of us thinks it is ;)
So please keep this in mind.

## Installation

Just start the Docker containers.

```ruby
docker-compose up -d
```

After the containers are started, go to `http://localhost/admin` and install the App like any other plugin. Don't forget to activate it afterwards!

That's it!

## Using the App

Before we write a review, we have to place an order in Shopware. Just proceed as usual.

Now open the Mailcatcher at `http://localhost/mailcatcher`.
Our product review system has sent an email with a link to review the products of your recent order.
Click on that link, or manually navigate to the order at `http://localhost:1000`.

Select your order in the overview list.
This brings up a screen with all products of that order.
Just select a product, write a review and submit the form.

If everything worked, the review should now be visible on the product detail page in Shopware.

## Features

### Hooks

When you place an order, a hook is automatically called to our backend service.
This contains the order and our backend service processes it for you.

### App Scripts - Storefront

The detail page of a PDP has been modified to show the "Dockware Product Review" count in the buy-box.
This is coming from an App Script.

### App Scripts - Custom Endpoint

We have created a custom Storefront route that returns the reviews of a product using JSON.

Just use this URL and provide your product number

```bash
http://localhost/storefront/script/dockware-reviews?productNumber=xyz 
```

### App Configuration

You can open the configuration in the Admin and turn off the count display on the PDP page.

### Admin Extension SDK

The Admin SDK has been configured automatically for you.
The backend service contains the URL http://localhost:1000/app/admin that contains the whole server side administration extension.

In addition to this, we've created an action button in the details of an order.
This action button "Add Review" acts as deep link button that automatically redirects you to the page to submit a new review for this order.

## Architecture

### Shopware App

The Shopware app sends a webhook to our backend service, as soon as a new order is placed.
There's no other magic in there ;)

### Backend Service

Our backend service, has a few important things.
Again, please keep in mind, this is by far no perfect implementation.
It should only demonstrate how it is done.

#### Data Storage

Instead of a MySQL database, a simple JSON file storage has been used.
So all shops as well as received orders are saved in the "_storage" folder and can easily be reviewed.

#### Logs

All interesting requests to our app system are automatically logged.
So here you can see what Shopware sends to our backend service, and what is happening under the hood.

#### Product Review

The frontend of our application can be opened with `http://localhost:1000`.
It allows you to search for orders, and write reviews for products of those orders.

### Connection between containers

To allow a real plug and play experience for you, we've adjusted 2 simple things.

Our Shopware needs to be able to talk to our backend service, and also a communication from that
service to the Shopware API needs to work.

#### Shopware -> Backend Service

This is simply done by using the `manifest.xml` file within the Shopware app.
Normally you would add your real domain for that backend system in it.
In our case, we just use the host name of our Docker container.
That's it

#### Backend Service -> Shopware

This is a bit more tricky, but still very easy.
During the registration handshake, our Shopware App tells the backend service, the URL that should be used when contacting the shop.
This will be taken from the `.env` file of the Shopware container.
All we do is, to create any random domain that might not even exist.
We do this by using bind-mount within the `docker-compose.yml` file.

As soon as that domain is registered within our backend service, we have to ensure
that this service is able to reach our Shopware container with that domain.
This is done by using a `link` with an `alias` in the configuration of our backend service container within the `docker-compose.yml` file.
We just use our non-existing domain from the `.env` file and use it as an alias for the Docker container of Shopware.

And now, your backend service can automatically reach the dynamically registered URL of our Shopware App.

## Troubleshooting

If somehow any errors occur, like having problems with sending reviews to Shopware, then just reset your data and install the App again in Shopware.
You can either manually remove the storage folder, or use our prepared `makefile` command.