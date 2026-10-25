# Réparation de la base moroway_tours

## Diagnostic et correction réalisés

La configuration Laravel active était correcte : pilote `mysql`, hôte `127.0.0.1`, port `3306`, base `moroway_tours`, sans cache de configuration. La connexion au serveur SQL fonctionnait, mais la base n’existait pas. Le `.env` n’a donc pas été modifié et les identifiants n’ont pas été affichés.

La base a été créée avec le jeu de caractères `utf8mb4` et la collation `utf8mb4_unicode_ci`. Les 18 tables nécessaires ont été créées à partir d’un schéma SQL conforme aux définitions du projet, avec leurs index et relations. Aucun gestionnaire de migrations n’a été exécuté. Aucun effacement, remplacement de table ou réinitialisation des données n’a été effectué.

Le catalogue étant vide, les seeders existants ont été exécutés : 29 circuits publiés, dix destinations conservées en base (Fès, Tanger et Casablanca seules sont publiques), 100 photos et 87 packs. Les avis de démonstration restent identifiés. Les tables `users` et `bookings` existent et sont vides, ce qui est normal pour cette nouvelle base. Aucun compte administrateur prédéfini n’a été ajouté.

## Outils ajoutés

Depuis la racine du projet :

```powershell
php tools/check-database.php
php tools/repair-database.php --apply
```

Le premier outil contrôle la connexion et la présence de la base et des tables sans afficher les identifiants. Le second crée la base si nécessaire et uniquement les tables absentes. Il importe le catalogue uniquement si `tours` est vide et vérifie que les nombres de lignes précédemment présentes n’ont pas diminué. Une seconde exécution sur la base réparée a laissé les nombres de lignes inchangés, sans réimportation ni doublon.

Le schéma de référence est `backend/database/schema/mysql-bootstrap.sql`. Le registre de migrations n’est pas modifié artificiellement. Cette réparation utilise un provisionnement SQL direct ; ne pas lancer ensuite un gestionnaire de migrations sur cette base en supposant que son historique a été enregistré. Pour de futures évolutions du schéma, prévoir une procédure additive adaptée à cet état. L’outil n’essaie pas de remplacer une table existante dont les colonnes différeraient du schéma attendu.

## Contrôles réussis

- Connexion SQL sur `127.0.0.1:3306` et encodage `utf8mb4` confirmés.
- Tables `tours`, `destinations`, `bookings`, `users` et leurs tables associées présentes.
- `GET /api/tours` : HTTP 200, 29 circuits, tous publiés.
- Contrôle Edge sans écriture de données : accueil, liste React de 29 circuits, titre et image du premier circuit, navigation vers son détail ; aucune erreur JavaScript relevée.
- Capture inspectée : `docs/screenshots/database-repair-tours.png`.
- Les 14 fichiers du site HTML original sont inchangés (SHA-256). Aucun fichier de design React/CSS n’a été modifié.

Pour reproduire le contrôle navigateur avec Laravel et Vite actifs : `npx playwright test database-repair.spec.js` dans `frontend/`. Ce scénario ne crée ni utilisateur ni réservation et ne réinitialise aucune base.

## Démarrage et étapes manuelles

Le catalogue ne nécessite aucune autre correction manuelle. Garder le serveur SQL habituel actif sur le port 3306, puis lancer `DEMARRER-REACT-LARAVEL.bat` ou les deux serveurs selon le README. Si une instance Laravel était déjà lancée avant correction, la redémarrer et actualiser React.

Pour accéder à l’administration, créer volontairement un compte depuis `backend/` avec `php artisan moroway:create-admin votre-adresse@exemple.com`. Le nom et le mot de passe sont demandés de façon interactive ; aucun secret n’est fourni dans le projet.
