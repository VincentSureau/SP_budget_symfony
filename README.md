# SP_budget_symfony

### Installation du projet
+ Cloner le projet avec `git clone https://github.com/VincentSureau/SP_budget_symfony.git`
+ Installer les dépendances PHP avec `composer install`
+ Copier le fichier `.env` en `env.local` et mettre à jour les variables d'environnement (attention, le fichier .env est suivi par le repository git, ne pas y insérer de données sensibles)
+ Créer la base de donnée avec `php bin/console doctrine:database:create`
+ Mettre à jour le schema de la base de donnée avec la commande `php bin/console doctrine:migrations:migrate`
+ Optionel: Remplir la base de données avec des fausses données: `php bin/console doctrine:fixtures:load`

---

### Demo
Il est possible de se connecter avec un utilisateur admin:
- username: admin@admin.com
- mot de passe: password

---

### Structure de la base de donnée MCD (fait avec Mocodo)
![MCD](doc/MCD.svg)

Voir le diagramme UML complet --> [ici](doc/UML.md)

---

### Routing

| Méthode | URL | Nom de la route | Paramètres | Description |
| --- | --- | --- | --- | --- |
| GET | /mon-compte | account | - | Page récapitulative des dépenses par catégories |
| GET | /bilan | bamance | - | Page récapitulative des dépenses des 12 derniers mois |
| GET | /admin/categories | admin_category_index | - | Liste des catégories |
| GET,POST | /admin/categories/new | admin_category_new | - | Créer une catétorie |
| GET,POST | /admin/categories/:id/edit | admin_category_edit | id de la catégorie | Modifier une catégorie |
| DELETE | /admin/categories/:id | admin_category_delete | id de la catégorie | Supprimer une catégorie |
| GET | /admin/moyens-de-paiement | admin_payment_method_index | - | Liste des moyens de paiement |
| GET,POST | /admin/moyens-de-paiement/new | admin_payment_method_new | - | Créer un moyen de paiement |
| GET,POST | /admin/moyens-de-paiement/:id/edit | admin_payment_method_edit | id du moyen de paiement | Modifier un moyen de paiement |
| DELETE | /admin/moyens-de-paiement/:id | admin_payment_method_delete | id du moyen de paiement | Supprimer un moyen de paiement |
| GET | /operations | operation_index | - | Liste des opérations |
| GET,POST | /operations/new | operation_new | - | Créer une catétorie |
| GET,POST | /operations/:id/edit | operation_edit | id de l'opération | Modifier une opération |
| DELETE | /operations/:id | operation_delete | id de l'opération | Supprimer une opération |
| GET | / | app_home | - | Page d'accueil du site |
| GET,POST | /inscription | app_register | - | Page d'inscription |
| GET,POST | /login | app_login | - | Page de connexion |
| GET | /logout | app_logout | - | Page de déconnexion |
| GET,POST | /reset-password | app_forgot_password_request | - | Formulaire mot de passe oublié |
| GET,POST | /check-email | app_check_email | - | Page de confirmation envoie mot de passe |
| GET | /reset/{token} | app_reset_password | token de réinitialisation | Page de modification du mot de passe oublié |

---

### Librairies PHP utilisées
- `fzaninotto/faker` pour les fixtures
- `gedmo/doctrine-extensions` et `stof/doctrine-extensions-bundle` pour automatiser la création des slug et des champs created_at et updated_at dans les entity
- `knplabs/knp-paginator-bundle` pour la pagination des opérations

---

### Librairies CSS / Javascript utilisées
- `MaterializeCss` pour le framework css
- `Datatable` pour l'affichage des opérations
- `chart.js` pour l'affichage des graphiques
- `Jquery` pour la manipulation du DOM
