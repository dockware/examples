## Requirements
 - installed docker-engine
 - installed docker-compose 

## How to use it?
Please read our instructions in our docs:
https://docs.dockware.io/tips-and-tricks/how-to-use-bind-mounting

And then just put this docker-compose in a folder where you placed your project, 
open terminal and type the command:
```
docker-compsoe up -d
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