Ce projet est lié à mon projet de front-end NEXT!

Des recommandations pour démarrer le projet :

Changez la variable DATABASE_URL avec votre configuration de base de données.
Lancez la commande php bin/console doctrine:database:create pour créer la base de données !
Lancez la commande php bin/console doctrine:schema:update --force pour créer les tables !
Ajoutez un fichier config/jwt et puis lancer ces deux  comandes : openssl genrsa -out config/jwt/private.pem 2048 et   openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

Lancez la commande php -S localhost:8000 -t public/ pour lancer le serveur.

J'ai utilisé trois entités principales, qui sont :

User :

id
username
roles
password
phone_number
address
email

Theme :

id
name
description
price
number_of_users


UserTheme :

id
user_id
theme_id
subscription_date




L'utilisateur peut utiliser plusieurs thèmes, et un thème peut être utilisé par plusieurs utilisateurs.
L'entité UserTheme est une table de jointure entre User et Theme.

Pour gérer les utilisateurs, j'ai utilisé JWT. Un utilisateur peut avoir deux rôles : Admin et User.

Les routes pour l'admin commencent par /api/admin, et celles pour l'utilisateur commencent par /api.

Les routes
POST : /api/register
Un exemple de corps de requête (request body) :

{
  "username": "omargaz58",
  "email": "gazzahomar2001@outlook.fr",
  "phonenumber": "51520520",
  "address": "28 rue Alfred de Musset",
  "password": "omar5471"
}
POST : /api/login
Un exemple de corps de requête :

{
  "username": "omargaz58",
  "password": "omar5471"
}

POST : /api/add-admin-role : Cette route permet d'ajouter le rôle Admin à l'utilisateur connecté.

Routes pour l'admin : 

POST : /api/admin/create : Pour ajouter un thème.
Un exemple de corps de requête :

{
  "name": "Bootstrap",
  "price": 44,
  "numberOfUsers": 0,
  "description": "un bon modèle"
}


PUT : /api/admin/update/{id} : Pour modifier un thème.
Un exemple de corps de requête :

json
Copier le code
{
  "name": "Bootstrap1",
  "price": 4554,
  "numberOfUsers": 99,
  "description": "un bon modèle 2"
}
DELETE : /api/admin/delete/{id}
Pour supprimer un thème.

Routes pour l'utilisateur
POST : /api/user/addtheme/{themeId}
Pour ajouter un thème à l'utilisateur connecté.

GET : /api/user/getthemes
Pour récupérer tous les thèmes de l'utilisateur connecté.

Remarque
Les routes pour register, create theme, et update theme incluent une gestion appropriée de la validation des champs.