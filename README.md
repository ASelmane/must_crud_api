# Test technique MustInformatique
## Introduction

Ce projet est un test technique PHP/Symfony. Il s'agit d'une API REST pour un CRUD 

## Prérequis
- PHP 7.4 ou supérieur
- Composer
- Symfony CLI
- Git

## Installation
1. Cloner ce repository : 
`git clone https://github.com/ASelmane/must_crud_api.git`
2. Installer les dépendances : 
`composer install`
3. Configurer les variables d'environnement dans le fichier .env
4. Créer la base de données :
`symfony console doctrine:database:create`
5. Créer les tables :
`symfony console doctrine:migrations:migrate`
6. Ajouter les fixtures :
`symfony console doctrine:fixtures:load`
7. Lancer le serveur :
`symfony server:start`

## Endpoints
L'API expose les endpoints suivants :

##### Produits :
- GET `/product` (liste de tous les produits)
- GET `/product/{id}` (détail d'un produit)
- POST `/product` (création d'un produit)
- PUT `/product/{id}` (modification d'un produit)
- DELETE `/product/{id}` (suppression d'un produit)

##### Marques :
- GET `/brand` (liste de toutes les marques)
- GET `/brand/{id}` (détail d'une marque)
- POST `/brand` (création d'une marque)
- PUT `/brand/{id}` (modification d'une marque)
- DELETE `/brand/{id}` (suppression d'une marque)

##### Catégories :
- GET `/category` (liste de toutes les catégories)
- GET `/category/{id}` (détail d'une catégorie)
- POST `/category` (création d'une catégorie)
- PUT `/category/{id}` (modification d'une catégorie)
- DELETE `/category/{id}` (suppression d'une catégorie)


## Utilisation
Vous pouvez par exemple utiliser [Postman](https://www.postman.com/) pour envoyer des requêtes HTTP.

## Auteur
#### [Adrien SELMANE](https://github.com/ASelmane)