# Description du projet
Bonjour Spaarple :wave:
#### Auteur : **LE JEUNE Maxime & MILLON Louka**
#### Date de création : **11/04/2024**
#### Version : **1.0.0**

## Lancer le projet sur votre machine
Je ne vais pas vous expliquer comment installer un serveur local sur votre machine, je vais juste vous donner 
les commandes à exécuter pour lancer le projet.<br>

Je pars du principe que vous avez déjà clone le projet sur votre machine.

### Etapes a réaliser
| Prérequis avoir **Docker Desktop** d'installer sur votre poste cela simplifiera grandement les étapes à suivres**

- Aller dans le projet, ouvrir un terminal et exectuer la commande suivante
```bash
  docker-compose up -d --build
```
- Ouvrir Docker Desktop et vérifier que les containers sont bien en cours d'exécution
- Ouvrir un navigateur et taper l'url suivante : http://localhost:8080 pour vérifier que le projet tourne correctement

- Aller dans le container **www** en exécutant la commande suivante
```bash
  composer install
```
- Aller dans le container **node** en exécutant la commande suivante
```bash
  yarn && yarn watch
```

Pour finir penser a copier le fichier **.env** et le renommer en **.env.local** et de modifier les informations de 
connexion à la base de données

## Technologies utilisées
- **PHP 8.3**
- **Symfony 7.0.***
- **JavaScript**
- **MySQL**
- **Docker (dev environment only)**
- **Yarn**
- **Twig**
- **Webpack Encore**
- **TailwindCSS**
- **Markdown**

## Mise en production
Pour mettre en production le projet, il vous suffit de suivre les étapes suivantes
- Connectez-vous au serveur où vous souhaitez déployer le projet
- Clonez le projet sur le serveur si ce n'est pas encore fait. Sinon, faites un **pull** pour récupérer les dernières modifications
- Si vous avez ajouté des dépendances, pensez à les installer en exécutant la commande suivante
```bash
  composer install --no-dev
```
- Pensez à compiler les assets en exécutant la commande suivante
```bash
  yarn && yarn build
```
- Enfin si vous avez rajouter des entités, pensez à mettre à jour la base de données en exécutant la commande suivante
```bash
  php bin/console doctrine:migrations:migrate --all-or-nothing
```
- Pensez à vider le cache en exécutant la commande suivante
```bash
  php bin/console cache:clear
```

Vériifer que le projet est bien en production en ouvrant un navigateur et en tapant l'url suivante : http://spaarple.fr
