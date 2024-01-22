# Projet JOB BOARD

## Description
Dans ce projet, nous avons créé un site web de type job board. Ce site permet aux utilisateurs de consulter des offres d'emploi et de postuler à ces offres. Les utilisateurs peuvent également créer un compte et publier des offres d'emploi.

## Instructions
Dans ce projet, nous allons développer le site fonctionnalité par fonctionnalité. Chaque fonctionnalité sera développée dans une branche séparée. Une fois la fonctionnalité terminée, nous fusionnerons la branche avec la branche principale.

## Fonctionnalités
- [x] En tant qu'utilisateur, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et postuler à des offres d'emploi.
- [x] En tant qu'entreprise, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et publier des offres d'emploi et consulter les candidatures.
- [x] Les offres doivent être publiques par ordre décroissant de date de publication.
- [x] Les utilisateurs peuvent filtrer les offres par catégorie et par type de contrat.
- [x] L'administrateur peut créer des catégories et des types de contrat.Il peut également supprimer des offres d'emploi et desactivé des utilisateurs et des entreprises. 


## Partie 1
- [x] Dans un premier temps , nous allons faire la partie front-end du site web.
  - Mise en place de la structure HTML et CSS du site web.
  - Mise en place de la page d'accueil(Home page).Avec la navbar et le footer.
  - Mise en place de la page de connexion et d'inscription.
  - Affichage des offres d'emploi sur le page d'accueil.
  - Affichage des entreprises sur le page d'accueil.

- [x] Dans un deuxième temps, nous allons créer le controller du compte utilisateur.
    - Création du controller `accountController` 
        `symfony console make:controller AccountController`


- [x] Dans un troisième temps, nous allons faire la partie inscription et connexion

    - Création du formulaire d'inscription et de connexion.
    `symfony console make:user`

- [x] Mise à jour de l'entité User
    `symfony console make:entity`
    -  User
      - id (int)
      - email (string)
      - password (string)
      - role (string)
        `J'ai ajouté les champs suivants dans l'entité User`
      - status (string)=>(user,entreprise)
      - username (string)
      - createdAt (date)
  `symfony console doctrine:databse:create` 
    `symfony console make:migration`
    `symfony console doctrine:migrations:migrate`

- [x] Création du formulaire d'inscription.
    `symfony console make:registration-form`

- [x] Création du formulaire de connexion.
    `symfony console make:auth`

    - Sécurisation des routes. Les utilisateurs non connectés ne peuvent pas accéder à la page account.

- [x] Dans un quatrième temps, nous allons gérer l'envoi de mail de confirmation de creation de compte. Nous allons utiliser mailtrap.io


## Partie 2
Dans cette partie, nous allons créer la partie back-end du site web.

- [x] Dans un premier temps, nous allons installer le bundle `easyadmin` pour créer un back-office.
    `composer require easycorp/easyadmin-bundle`
    `symfony console make:admin:dashboard`
    `symfony console make:admin:crud`

- [x] Dans un deuxième temps, nous allons créer une entité `HomeSetting` pour gérer les paramètres de la page d'accueil(l'image, le message et le call to action)
    - Création de l'entité `HomeSetting` qui contient les champs suivants:
        - id (int)
        - message (string)
        - image (string)
        - callToAction (string)
        - createdAt (datetimeimmutable) 
   `symfony console make:entity HomeSetting`

- [x] Dans un troisième temps, nous allons faire le admin crud sur l'entity `HomeSetting` afin de pouvoir modifier les paramètres de la page d'accueil. et rendre la page d'accueil dynamique dans le `homeController`.
    - Création du admin crud sur l'entity `HomeSetting`
        `symfony console make:admin:crud`
    - Modification du `homeController` pour rendre la page d'accueil dynamique à partir des paramètres de la base de données.

- [x] Dans un quatrième temps, nous allons créer une entité `ContractType` pour gérer les types de contrat.Les différents types de contrat sont: CDI, CDD, Freelance, Stage, Alternance, Intérim,Autre.
    - Création de l'entité `ContractType` qui contient les champs suivants:
        - id (int)
        - name (string)
        - slug (string)
        - createdAt (datetimeimmutable) 
    `symfony console make:entity ContractType`

    - Creation du crud admin sur l'entity `ContractType`
        `symfony console make:admin:crud` 
        Ajout des ContratTypes dans la base de données par l'admin.


## Partie 3

- [x] Dans un premier temps, nous allons créer une entité `Tag` qui sera un ensemble de mots clés qui permettront de décrire les offres d'emploi.
    - Création de l'entité `Tag` qui contient les champs suivants:
        - id (int)
        - name (string)
        - slug (string)
    `symfony console make:entity Tag`

    - Installer cocur/slugify pour générer le slug.
        `composer require cocur/slugify`

    - Créatin du formulaire d'ajout de tag par l'employeur.
        `symfony console make:form`

    - Création du crud admin sur l'entity `Tag`
        `symfony console make:admin:crud`

- [x] Dans un deuxième temps, nous allons mettre à jour le `accountController` avec un sidebar qui contient un menu qui sera différent selon le status de l'utilisateur.
    - Un candidat aura un menu avec les liens suivants:
        - Mes candidatures
        - Mes informations
        - Profil
    - Un recruiteur aura un menu avec les liens suivants:
        - Mes offres d'emploi
        - Mes informations
        - Les Mot clés
        - Profil



- [x] Dans un troisième temps, nous allons créer une  `UserProfil` 
    qui va permettre à l'utilisateur de modifier ses informations personnelles.Cette entité aura les champs suivants:
        - id (int)
        - firstName (string)
        - lastName (string)
        - slug (string)
        - address (string)
        - city (string)
        - zipCode (string)
        - country (string)
        - phoneNumber (string)
        - jobSought(string) => poste recherché ==>nullable
        - presentation (text)
        - availability (string) => disponibilité
        - website (string)
        - picture (string)
        - user (User) => OneToOne
    `symfony console make:entity UserProfil`

- [x] Création du controller et du formulaire d'ajout de `UserProfil` par l'utilisateur.
    `symfony console make:form`

- [x] Créer la route `user/profil/{slug}` qui affiche le profil de l'utilisateur.
- [x] Créer la route `user/profil/{slug}/edit` qui permet à l'utilisateur de modifier son profil.

- [x] Dans un quatrième temps, nous allons créer une entité `EntrepriseProfil` qui contient les informations de l'entreprise.
    - Création de l'entité `EntrepriseProfil` qui contient les champs suivants:
        - id (int)
        - name (string)
        - slug (string)
        - address (string)
        - city (string)
        - zipCode (string)
        - country (string)
        - phoneNumber (string)
        - activityArea (string) => secteur d'activité
        - email (string)
        - description (text)
        - logo (string)
        - website (string)
        - user (User) => OneToOne
    `symfony console make:entity EntrepriseProfil`

- Créer la route `/entreprise/profil/{slug}` qui affiche le profil de l'entreprise.

- Créer la route `/Tag` qui affiche la liste des tags disponibles sur le site sur le profil de l'entreprise et qui inclut un formulaire d'ajout de tag.(Seul l'admin peut supprimer des tags)

## Partie 4
- [x] Dans un premier temps, nous allons créer un controller `OfferController` qui va gérer les offres d'emploi.
    - Création du controller `OfferController` 
        `symfony console make:controller OfferController`


- [x] Dans un deuxième temps, nous allons créer une entité `Offer` qui contient les offres d'emploi.
    - Création de l'entité `Offer` qui contient les champs suivants:
        - id (int)
        - title (string)
        - slug (string)
        - shortDescription (string)
        - content (text)
        - createdAt (datetimeimmutable)
        - salary (int)
        - location (string)
        - contractType (ContractType) => ManyToOne
        - entreprise (Entreprise) => ManyToOne
        - tags (Tag) => ManyToMany
        - isActive (boolean)
    `symfony console make:entity Offer`

    - Création du formulaire d'ajout d'offre d'emploi par l'employeur.
        `symfony console make:form`
    
    - Création de la route `/entreprise/offer/new` qui permet à l'employeur de créer une offre d'emploi.
    - Création de la route `/entreprise/offer/` qui permet à l'employeur de voir ses offres d'emploi.

    - Création du crud admin sur l'entity `Offer`. L'admin peut desactiver une offre d'emploi.
        `symfony console make:admin:crud` 


        

### REFACTORING

Ici nous allons faire du refactoring sur le code existant.
On va ajouter `ck editor`, `select2` et `knplabs/knp-time-bundlebootstrap` pour gérer les dates et les heures.

Les LifeCycle Callbacks permettent d'exécuter du code à des moments précis du cycle de vie d'une entité. Par exemple, nous pouvons utiliser les LifeCycle Callbacks pour générer le slug d'une entité avant de la persister en base de données ou pour mettre à jour la date de création ou de modification d'une entité.
Pour cela, nous allons utiliser les annotations `@ORM\HasLifecycleCallbacks` et `@ORM\PrePersist` et `@ORM\PreUpdate`.
Cette annotation permet d'indiquer à Doctrine que l'entité contient des LifeCycle Callbacks qui seront exécutés à des moments précis du cycle de vie de l'entité.
Ceci va nous permettre de générer le slug et le createdAt et updatedAt automatiquement.
Pour cela nous allons ajouter une méthode ` public function initalizeSlug()` dans l'entité `Offer` qui va générer le slug à partir du titre de l'offre d'emploi.
Nous allons également ajouter une méthode `public function initalizeCreatedAt()` qui va générer la date de création de l'offre d'emploi.
Mettre ceci en place sur toutes les entités qui ont besoin de ces fonctionnalités.


## Partie 5

- [x] Installation de `orm-fixtures` pour générer des données de test.
    `composer require orm-fixtures --dev`

- [x] Création des fixtures pour les entités `ContractType`, `Tag`, `User`, , `UserProfil`, `EntrepriseProfil`, `Offer`.

- [x] Nous allons, nous allons afficher les 6 dernières offres d'emploi sur la page d'accueil et les 4 dernières entreprises.
  
- [x] Création de la route `/offre-emploi` qui affiche la liste des offres d'emploi sur la page Offres.Pour cela, nous allons créer une méthode `public function getOffers()` dans le `HomeController` qui va récupérer les offres d'emploi et les passer à la vue `home/offer_list.html.twig`.

- [x] Création de la pagination sur la page `/offre-emploi`. Nous allons le faire sans utiliser de bundle externe.Vous pouvez utiliser le bundle `knplabs/knp-paginator-bundle` si vous le souhaitez.


- [x] Création de la route `/offre-emploi/{id}` qui affiche le détail d'une offre d'emploi sur la page Offre. Pour cela, nous allons créer une méthode `public function getOneOffer()` dans le `HomeController` qui va récupérer l'offre d'emploi et la passer à la vue `home/offer_detail.html.twig`.
- [x] Permettre à l'utilisateur de postuler à une offre d'emploi.

- [x] Création de  l'entité `application` qui contient les champs suivants:
        - id (int)
        - status (string) => (pending,accepted,refused)
        - createdAt (datetimeimmutable)
        - User (User) => ManyToOne car un utilisateur peut postuler à plusieurs offres d'emploi.
        - Offer (Offer) => ManyToOne car une offre d'emploi peut avoir plusieurs candidatures.
        - Entreprise (Entreprise) => ManyToOne car une entreprise peut avoir plusieurs candidatures.
        - message (text) nullable

    `symfony console make:entity Application`

- [x] Création du formulaire d'application à une offre d'emploi.
- [x] Création de la route `/apply/{id}` qui permet à l'utilisateur de postuler à une offre d'emploi.L'utilisateur doit être connecté pour pouvoir postuler à une offre d'emploi.
- [x] Création de la route `/account/application` qui permet à l'utilisateur de voir ses candidatures.
- [x] Création de la route `/entreprise/application` qui permet à l'entreprise de voir les candidatures à ses offres d'emploi.


## Partie 6
- [x] Dans un premier temps, nous allons créer une entité `PasswordUpdate` qui va permettre à l'utilisateur de modifier son mot de passe.Cette entité ne sera pas persistée en base de données.Donc on va supprimer toutes les annotations ORM.
    - Création de l'entité `PasswordUpdate` qui contient les champs suivants:
        - id (int)
        - oldPassword (string)
        - newPassword (string)
        - confirmNewPassword (string)
    `symfony console make:entity PasswordUpdate`

- [x] Création du formulaire de modification de mot de passe.
    `symfony console make:form UserPasswordUpdate` Ce formulaire va permettre à l'utilisateur de modifier son mot de passe. **Attention** : Ce formulaire ne sera pas lié à une entité car l'entité `PasswordUpdate` ne sera pas persistée en base de données. Le formulaire doit contenir les champs suivants:
        - oldPassword (password)
        - newPassword (password)
        - confirmNewPassword (password)

- [x] Créer la route `/user/profil/{id}/update-password` qui permet à l'utilisateur connecté de modifier son mot de passe depuis son compte.


## Partie 7

- [x] Dans `userProfilController` nous allons créer une route `/user/profil/{id}/applications` qui permet à l'utilisateur de voir ses candidatures.

- [x] Dans la vue qui affiche la liste des candidatures, nous allons ajouter le nombre de candidatures pour chaque offre d'emploi.

- [x] Dans `'/offer/{id}/show` du `OfferController` nous allons ajouter le nombre de candidatures pour chaque offre d'emploi.

- [x] Dans la vue qui affiche une offre dans le profil de l'entreprise, nous allons ajouter dans un tableau html qui va afficher les candidatures à cette offre d'emploi(avec le nom du candidat, son email, status,la date , une partie du message, et un bouton qui va permettre de gérer la candidature).
  
- [x] Dans `OfferController` nous allons créer une route `//offer/{id}/application/{applicationId}/status` qui va permettre de voir le détail d'une candidature et de changer son status.

## Gestions des erreurs PRE PRODUCTION

Dans cette partie, nous allons voir comment gérer les erreurs avec le `twig bundle` avant la mise en production. Nous allons donc créer des pages d'erreurs personnalisées en fonction du type d'erreur. cf: `https://symfony.com/doc/current/controller/error_pages.html`
Pouur cela nous allons créer les dossier et fichiers suivants:

templates/ ==> `Dossier qui contient les templates du site web`
└─ bundles/ ==> `Dossier qui contient les templates des bundles`
   └─ TwigBundle/ ==> `Dossier qui contient les templates du bundle Twig`
      └─ Exception/ ==> `Dossier qui contient les templates des exceptions`
         ├─ error404.html.twig ==> `Template de la page 404`
         ├─ error403.html.twig ==> `Template de la page 403`
         └─ error.html.twig     ==> `Template pour les autres erreurs`


- [x] Création d'une page 404 qui s'affiche lorsque l'utilisateur essaie d'accéder à une page qui n'existe pas.
- [x] Création d'une page 403 qui s'affiche lorsque l'utilisateur essaie d'accéder à une page sans avoir les droits nécessaires.
- [x] Création d'une page d'erreur qui s'affiche pour les autres erreurs.

Pour tester les pages d'erreurs,en mode développement, on va taper une url 

`https://localhost:8000/_error/404`  pour tester la page 404
`https://localhost:8000/_error/403`  pour tester la page 403


## GESTION DES ASSETS AVEC ASSETMAPPER
Avant la mise en prod , nous allons gérer les assets avec assetmapper.
AssetMapper est un outil qui permet de gérer les assets de votre site web. Il permet de concaténer et de minifier les fichiers CSS et javascript. Pour cela nous allons taper la commande suivante: 
    `php bin/console asset-map:compile`


## Partie 7 Déploiement de l'application

Dans cette partie, nous allons déployer l'application sur Heroku.Pour cela nous allons premièrement créer un utiilisateur admin sur notre application et sécuriser les routes du back-office.

- [x] mettre le .env en mode production
- [x] Créer le fichier .htaccess `composer require symfony/apache-pack`. Cette commande va créer le fichier .htaccess dans le dossier public qui va permettre de rediriger toutes les requêtes vers le fichier index.php.

- [x] Création d'un compte sur Heroku.
- [x] Installation de Heroku CLI.
- [x] Création d'une application sur Heroku.


### FAIRE LE DEPLOIEMENT IONOS

- Création de la base de données sur IONOS et récupération des informations de connexion.
    $host_name = `db5015045444.hosting-data.io`;
    $database = `dbs12498200`;
    $user_name = `dbu2779058`;
    $user_pass= `u115202564`;

- Création du fichier .env.local sur le serveur IONOS 

- Connexion en ssh sur le serveur IONOS
 `ssh u115202564@access993297496.webspace-data.io` puis je tape mon mot de passe

- Création du dossier `jobboard` dans le dossier `www` qui contient les fichiers du site web.
  `mkdir jobboard`

- Zipper le dossier `jobboard` et le télécharger sur le serveur IONOS.
   `scp sym_job_board.zip u115202564@access993297496.webspace-data.io:/joboard` puis je tape mon mot de passe Ici je copie le fichier monprojet.zip dans le dossier jobboard sur le serveur IONOS.

- Dézipper le fichier `sym_job_board.zip` qui se trouve dans le dossier `jobboard` sur le serveur IONOS.
  `cd jobboard` puis
  `unzip sym_job_board.zip`

- Supprimer le fichier `sym_job_board.zip` qui se trouve dans le dossier `jobboard` sur le serveur IONOS.
  `rm sym_job_board.zip`

## HEROKU V2

- Installation de Heroku CLI.
- Se connecter à Heroku CLI avec la commande `heroku login`
- Création d'une application sur Heroku avec la commande `heroku create`
- faire `git remote -v` pour vérifier que le remote heroku a été créé.
- Renommer le remote heroku avec la commande `heroku rename sym-job-board`
- Création d'un fichier Procfile à la racine du projet avec le contenu suivant:
    `echo 'web: heroku-php-apache2 public/' > Procfile`

### TODO 

- AFFICHER LES ENTREPRISES 
- LES FILTRES
- LES PAGES ABOUT ET CONTACT
- CREATIONS DU SERVICE MAIL AVEC MAILJET
- ENVOI DE MAIL DE CONFIRMATION DE CREATION DE COMPTE
- ENVOI DE MAIL DE CONFIRMATION DE CANIDATURE A UNE OFFRE D'EMPLOI
- ENVOI DE CONFIRMATION DE CHANGEMENT DE STATUS DE CANDIDATURE A UNE OFFRE D'EMPLOI
- MOT DE PASSE OUBLIE
- SYSTEME DE LIKE POUR LES OFFRES D'EMPLOI(WISHLIST)
- SYSTEME DE NOTE POUR LES ENTREPRISES ET LES OFFRES D'EMPLOI
- SYSTEME DE COMMENTAIRE POUR LES ENTREPRISES ET LES OFFRES D'EMPLOI