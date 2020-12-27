## Requirements
 - installed docker-engine
 - installed docker-compose 

## How to use it?
Just copy this `docker-compose.yml` in a folder where you placed your project, 
open terminal and type the command:
```
docker-compose up -d
```

## How to debug?
Simply type in your terminal:
```
docker logs MYCONTAINERNAME
```
For Example: 
```
docker logs dw__myshop_shopware
```
and you will see the output of the Shopware Container ...

## How to use it for different Projets?
That's quit easy, just replace all "myshop" names with your project Shortcode or name, and you will have different volumes etc. for each project, that you don't get in trouble with db Names etc.
