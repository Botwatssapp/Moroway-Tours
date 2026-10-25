# MOROWAY TOURS — React + Laravel

Migration locale du site existant : React 19, Vite 7, React Router 7, Axios, Laravel 12, Sanctum 4 et pilote MySQL. Les versions exactes installées sont figées dans `frontend/package-lock.json` et `backend/composer.lock`.

Le site public React propose exclusivement **Fès, Tanger et Casablanca**. Voir `docs/CITY-SCOPE.md` pour les contrôles et l’archivage.

Le site actif utilise `frontend/` et `backend/`. L’ancienne version HTML et ses lanceurs ont été retirés après sauvegarde vérifiée. Le point de restauration privé et la liste complète des suppressions sont documentés dans `docs/NETTOYAGE-TOTAL.md`.

Photos : neuf clichés vérifiés, 35 variantes WebP, galeries et solutions de repli. Sources, licences, poids, fichiers modifiés et contrôles : `docs/IMAGES.md`.

Nettoyage et inventaire : `docs/NETTOYAGE-TOTAL.md` détaille la sauvegarde préalable, les suppressions justifiées, les fichiers conservés et les tests. Audits sans suppression : `node tools/audit-frontend.mjs` et `php tools/audit-media.php`. L’outil `python tools/inventory-cleanup.py` crée une nouvelle sauvegarde privée vérifiée des sources.

## Installation Windows

Prérequis : PHP 8.2+ (8.3+ conseillé pour une prochaine migration Laravel 13), Composer 2, Node 22.12+ ou 24, MySQL 8 ou une version MariaDB compatible. Le poste utilisé possède PHP 8.2.12 et Node 24.12. Laravel 12 a été retenu pour ce PHP ; ses correctifs de sécurité sont prévus jusqu’au 24 février 2027. Voir la [politique officielle](https://laravel.com/docs/12.x/releases).

Dans PHP, activer `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `dom`, `xml`, `ctype`, `tokenizer` et `zip` (décompression Composer). Vérifier `php -v`, `php --ini`, `composer --version`, `node -v` et `npm -v`.

### 1. Créer la base

Démarrer MySQL puis exécuter dans phpMyAdmin ou le client SQL :

```sql
CREATE DATABASE moroway_tours CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Créer de préférence un utilisateur SQL dédié disposant des droits sur cette seule base. Ne pas publier un compte root sans mot de passe.

### 2. Installer Laravel

Dans PowerShell, depuis la racine du projet :

```powershell
cd backend
composer install
Copy-Item .env.example .env
```

Renseigner dans `.env` : `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`. Le fichier d’exemple cible `127.0.0.1:3306` et la base `moroway_tours`. Ne pas écraser un `.env` déjà configuré.

```powershell
php artisan key:generate
cd ..
php tools/repair-database.php --apply
cd backend
php artisan storage:link
php artisan moroway:create-admin votre-adresse@exemple.com
php artisan serve --host=127.0.0.1 --port=8000
```

La création admin demande le nom et un mot de passe masqué d’au moins 12 caractères. Aucun compte admin prédéfini n’est livré ou créé par le seeder. Le seeder actif prépare trois propositions de visites urbaines : Fès, Tanger et Casablanca, avec leurs photos et programmes FR/EN. Les 29 anciennes offres sont conservées et dépubliées dans la base existante ; leurs réservations ne sont pas supprimées. Un second passage ne remplace pas les modifications de l’agence.

### 3. Installer React

Dans un deuxième terminal, depuis la racine :

```powershell
cd frontend
npm install
Copy-Item .env.example .env
npm run dev
```

Ouvrir **http://127.0.0.1:5173/**. Administration : **http://127.0.0.1:5173/admin**. Garder les deux terminaux ouverts. Le port 5173 est strict : en cas d’occupation, arrêter l’autre Vite ou adapter ensemble Vite, `FRONTEND_URL` et `SANCTUM_STATEFUL_DOMAINS`.

En local, laisser `VITE_API_URL` vide : Vite transmet `/api`, `/sanctum`, `/login`, `/logout`, `/storage` et `/sitemap.xml` à Laravel. Utiliser toujours le même hôte, sans mélanger `localhost` et `127.0.0.1`. Après installation, `DEMARRER-REACT-LARAVEL.bat` ouvre les deux serveurs.

### Base isolée de vérification sur ce poste

Une instance **MariaDB 10.4.32 fournie par XAMPP**, distincte des données de `C:\xampp\mysql\data`, a servi aux premières vérifications SQL. Ses fichiers résident dans `.local-mysql/`, ignorés par Git, et elle utilise le port **3307**. La configuration active a ensuite été vérifiée et réparée sur **127.0.0.1:3306**, base **moroway_tours** ; `.env.example` conserve également le port standard **3306**. Pour cette configuration active, utiliser le serveur SQL habituel, sans lancer l’instance isolée. Voir `docs/DATABASE-REPAIR.md`.

Si vous conservez cette instance, démarrer `DEMARRER-BASE-LOCALE.bat` avant les deux serveurs. Sinon, utiliser votre MySQL habituel et adapter le `.env`. La base MariaDB isolée contient le catalogue importé ; les données temporaires des tests navigateur sont supprimées automatiquement. Les tests sur Oracle MySQL n’ont pas été exécutés sur ce poste.

## Fonctionnement

- Routes : `/`, `/about`, `/destinations`, `/tours`, `/tours/:slug`, `/services`, `/gallery`, `/contact`, `/booking/:tourId`, `/custom`, `/students`, `/faq`, `/terms`, `/privacy`, `/admin` et 404.
- Les anciennes URL `/#experience/...`, `/#collection/...`, `/#departure/...` et autres fragments sont converties en routes React. Configurer le serveur web pour renvoyer `frontend/dist/index.html` sur les routes frontend. L’ancien dossier racine `dist/` a été archivé dans le point de restauration privé.
- Catalogue, recherche et filtres viennent de Laravel. Aucune copie locale de secours n’est présentée comme une base active.
- Réservations, projets étudiants/sur mesure et messages sont validés et enregistrés en SQL. La référence n’est affichée qu’après création effective. Statut initial `pending`, modifiable uniquement par l’admin. Aucun paiement en ligne.
- **« Prix selon le nombre de personnes et le pack choisi »** : aucun prix fixe public. Le devis final est saisi en administration avec sa devise.
- Les dates explicitement configurées contrôlent les capacités sous transaction et verrou du circuit. Les demandes `pending`, `confirmed` et `completed` consomment des places. Les dates sans ligne de disponibilité restent des demandes à étudier, jamais une promesse de départ.
- Admin : statistiques, CRUD circuits/destinations/packs/disponibilités/avis, photos, réservations/statuts/devis, messages traités, réseaux, coordonnées et titre d’accueil FR/EN. Les programmes détaillés peuvent être édités en JSON structuré dans la fiche circuit.
- Les anciens brouillons locaux ne sont pas envoyés automatiquement à l’agence. Les seules préférences encore locales sont la langue, les recherches et les réactions aux avis.

## Sécurité et API

Sanctum utilise une session cookie HttpOnly, renouvelée à la connexion ; Axios obtient `/sanctum/csrf-cookie` puis transmet `X-XSRF-TOKEN`. Le frontend ne conserve aucun jeton admin dans localStorage. Les routes privées appliquent `auth:sanctum` puis une policy `is_admin` côté serveur. Les validations utilisent des Form Requests et des listes de champs autorisés. Les formulaires publics ont un honeypot et une limite de 5 requêtes/minute/IP ; la connexion est limitée par IP et adresse.

Uploads : photos JPEG/PNG/WebP/AVIF, 5 Mo et 8000×8000 maximum, nom aléatoire, aucun SVG ni fichier exécutable. Les médias d’origine ne sont jamais supprimés physiquement. CORS n’autorise que `FRONTEND_URL`, avec credentials. Aucune clé privée dans React.

Endpoints publics : `GET /api/tours`, `GET /api/tours/{slug}`, `GET /api/destinations`, `GET /api/reviews`, `GET /api/settings`, `POST /api/bookings`, `POST /api/contacts`. Filtres catalogue : `q`, `kind`, `city`, `days`, `destination`. Auth : `POST /login`, `POST /logout`. CRUD privés sous `/api/admin/`, statistiques `/api/admin/stats`, identité `/api/admin/me`, photos `/api/admin/tours/{id}/images`. Les listes admin sont paginées à 100 entrées.

## Tests et compilation

```powershell
cd backend
php vendor/bin/phpunit
```

Cette commande utilise une base SQLite en mémoire indépendante. Pour tester le pilote MySQL, créer **une base réservée aux tests**, nommée `moroway_tours_test`, et renseigner un `.env.testing` avec ses paramètres SQL et une `APP_KEY` locale. Le test refuse de réinitialiser une base MySQL sans suffixe `_test`.

```powershell
php vendor/bin/phpunit --configuration phpunit.mysql.xml
cd ../frontend
npm run build
npx playwright test
```

Les tests navigateur nécessitent Laravel et Vite déjà démarrés, Edge installé (canal `msedge`) et la base locale migrée. Ils créent un compte admin temporaire à mot de passe aléatoire, puis retirent exclusivement leurs enregistrements identifiés. Résultats réels et limites : `docs/TESTS.md`.

## Production et SEO

Compiler avec `npm ci` puis `npm run build` dans `frontend/`. Servir **frontend/dist/**, et diriger les requêtes API, Sanctum, connexion/déconnexion, médias et sitemap vers **backend/public/**. Ne jamais exposer la racine de `backend/`, `.env`, `vendor/`, les outils CLI ou `.local-mysql/`.

Configurer un domaine réel dans `APP_URL`, `FRONTEND_URL`, `VITE_SITE_URL`, et éventuellement `VITE_API_URL`. Pour des sous-domaines, ils doivent partager le même domaine principal : adapter `SESSION_DOMAIN=.votre-domaine.com` et `SANCTUM_STATEFUL_DOMAINS` (sans schéma, avec port si nécessaire). Activer HTTPS, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, puis `php artisan config:cache`. Le serveur des médias doit interdire l’exécution de scripts. Sauvegarder SQL et `storage/app/public`.

Meta title/description et Open Graph sont actualisés par page, canonical propre et données structurées `TouristTrip` sans prix ni faux avis agrégés. `/sitemap.xml` est produit par Laravel depuis les circuits publiés ; ajouter son URL absolue dans `frontend/public/robots.txt` au déploiement. Les anciennes URL à fragments sont conservées via redirection client ; les fragments ne peuvent pas être lus par une redirection HTTP serveur.

La SPA ne fournit pas le contenu touristique complet dans le HTML initial : les robots sans JavaScript et aperçus sociaux peuvent manquer les métadonnées dynamiques. Avant lancement SEO intensif, ajouter un pré-rendu des routes publiques depuis l’API au build, puis le régénérer après les modifications admin ; ou un serveur SSR React/Vite conservant Laravel comme API. Ne jamais pré-rendre l’administration ou des données personnelles. Aucun score Core Web Vitals n’est affirmé sans mesure réelle.

## Erreurs courantes Windows

- `npm.ps1` bloqué : utiliser `npm.cmd`/`npx.cmd` ou l’invite de commandes.
- `could not find driver` : activer `extension=pdo_mysql` dans le `php.ini` indiqué par `php --ini`, puis relancer PHP.
- `SQLSTATE[HY000] [2002]` : démarrer le serveur SQL, vérifier hôte/port (3306 normal, 3307 pour l’instance isolée).
- `Unknown database` : depuis la racine, exécuter `php tools/check-database.php`, puis `php tools/repair-database.php --apply` pour créer uniquement la base et les tables manquantes, sans commande de migration ni suppression. `Access denied` : vérifier compte/mot de passe/droits.
- Erreur 419 : rester sur `127.0.0.1`, vérifier domaines Sanctum, cookies et proxy ; vider les anciens cookies, `php artisan optimize:clear`, puis relancer.
- Erreur 403 admin : le compte doit avoir été créé par `moroway:create-admin`, pas par inscription publique.
- Images uploadées absentes : vérifier `php artisan storage:link`, les droits de `storage` et la transmission de `/storage` à Laravel. Windows peut nécessiter le mode développeur ou une console élevée pour le lien symbolique.
- Composer sans ZIP : activer ZIP ou installer un outil de décompression ; ne pas désactiver TLS ni ignorer les exigences de plateforme.
- Photos/polices externes absentes hors ligne : les médias locaux restent accessibles ; Google Fonts nécessite Internet.

## Documents de livraison et limites

`docs/AUDIT.md` : inventaire et changements ; `docs/original-manifest.json` : intégrité des sources ; `docs/TESTS.md` : validations et limites. Les coordonnées réelles, informations légales, conditions d’annulation, durée de conservation et témoignages authentiques restent à fournir par l’exploitant. Aucun envoi d’e-mail automatique, paiement ou publication en ligne n’est activé.

Le `package.json` backend conserve uniquement `concurrently`, utilisé par `composer dev` après installation des outils npm backend. Ses commandes `build` et `dev` délèguent au frontend React ; aucun second build Tailwind/Vite Laravel n’est nécessaire.
