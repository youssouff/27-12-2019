# ProjetWeb
Projet Web CESI 2019

Création du site du bde dans le cadre du projet de fin de bloc WEB.
Utilisation des framework php symfony et css bootstrap.

Pour importer le projet :

-git pull

executer : <br>
~ composer update <br>
~ php bin/console doctrine:database:create <br>
~ php bin/console migrate <br>

Repository de l'api node.js :<br>
https://github.com/lucienklein/API-REST-Node.JS/tree/dev

Note : 
Lors de vos tests, merci de ne pas absuer des fonctionalités suivantes dans le cas où vous ne changeriez pas l'email de destination : le report d'évènement, de photos et de commentaires ainsi que la commande de produit. 

Budjetisation de l'hebergement :

Nous avons choisi l'hebergement TopHebergement cPanel L (https://www.tophebergement.com/hebergement-site-web/?rsource=adwords&rcampagne=hebergementEU-search&rkeyword=hebergement&gclid=CjwKCAiA_MPuBRB5EiwAHTTvMQhtACLmz30JDeVye1SS4WYU2BWaO57MUfghTnfiV2rr8pNRn5vp_RoCcMkQAvD_BwE)

Il nous permettra grace à sa formule d'héberger nos deux base de données ainsi que le site du Bde et l'api pour la somme totale de 4.99€ par mois (6.99€ hors réduction)
