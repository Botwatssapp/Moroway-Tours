# Nettoyage MOROWAY TOURS — 25 octobre 2026

> Rapport historique : l’ancienne version HTML et certains fichiers mentionnés ont depuis été retirés ou déplacés. État actuel et restauration : [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md).


## Inventaire et sauvegarde préalable

Le dossier ne contient pas de dépôt Git. Avant la suppression, un inventaire de **12 853 fichiers / 483 425 603 octets** a été établi, y compris les dépendances, builds, caches et fichiers MySQL. Les fichiers de données et de dépendances n’ont pas été ouverts pour analyse de leur contenu.

**269 fichiers de sources, configurations et documents** ont été sauvegardés dans une archive locale privée. Chaque entrée a été relue et comparée à son SHA-256 ; le contrôle CRC de l’archive a également réussi.

- Archive : `backend/storage/app/private/maintenance/sources-before-cleanup-20261025-154758.zip` (131 889 867 octets).
- Inventaire : `backend/storage/app/private/maintenance/inventory-20261025-154758.json`.
- SHA-256 de l’archive : `e6770ad72d983e624f1132663972c8830bb6ffb93813a06847a4bc1369623584`.

Cette sauvegarde de sources comprend les fichiers d’environnement et reste privée. Ce n’est pas une sauvegarde des données MySQL. L’instance `.local-mysql/`, les médias uploadés, les sauvegardes SQL et les données personnelles sont restés en place. Les répertoires de dépendances, caches et production régénérables sont inventoriés mais exclus du ZIP de sources.

## Fichiers supprimés

| Fichier | Taille | Preuve et raison |
| --- | ---: | --- |
| `backend/resources/views/welcome.blade.php` | 82 540 octets | Page promotionnelle Laravel livrée avec le squelette. Recherche des appels `view`, des références à `welcome`, examen des routes, du bootstrap et des providers : aucun appel. La racine Laravel renvoie un JSON ; React possède son propre point d’entrée. Archive et SHA-256 du fichier vérifiés avant suppression. Les 46 routes et les tests passent après suppression. |

**Aucun autre fichier source n’a été supprimé.** Vite a régénéré `frontend/dist/` avec les nouveaux bundles ; l’ancien `dist/` à la racine est intact. Aucune suppression manuelle de dépendance, cache actif, média ou donnée client.

Le retrait porte sur 82 540 octets de contenu de démonstration. Il ne représente pas une baisse équivalente de l’espace disque total : la sauvegarde privée et les documents de contrôle ajoutent de l’espace. Les éléments volumineux protégés ont été conservés.

## Fichiers optimisés

| Fichier | Changement |
| --- | --- |
| `frontend/src/components/Common.jsx` | Retrait du paramètre d’index inutilisé dans les cartes. |
| `frontend/src/pages/OtherPages.jsx` | Retrait de deux valeurs inutilisées (`destinations`, `tr`) de la déstructuration du contexte. |
| `frontend/tests/migration.spec.js` | Retrait de l’import inutilisé `node:path`, syntaxe contrôlée ; aucun scénario supprimé. |
| `tools/capture-qa.mjs` | Retrait de l’import inutilisé `node:fs`, syntaxe contrôlée. |
| `backend/tests/Feature/TourismTest.php` | Deux attentes obsolètes corrigées : la galerie contient désormais trois photos ; le test de conservation crée explicitement sa destination historique dans SQLite, au lieu de supposer qu’un seeder d’installation neuve la crée. L’assertion de conservation reste active. |
| `.gitignore` | Exclusion des archives et inventaires privés de maintenance. |
| `README.md` | Accès au présent rapport et commandes des audits. |

Outils de maintenance ajoutés :

- `tools/inventory-cleanup.py` : inventaire et archive vérifiée des sources, sans suppression.
- `tools/audit-frontend.mjs` : analyse Babel des imports, liaisons inutilisées et graphe accessible depuis `main.jsx`, sans suppression automatique.
- `tools/audit-media.php` : lecture des chemins des médias SQL et vérification des variantes, sans lecture de données clients ni écriture SQL.
- `docs/frontend-cleanup-audit.json`, `docs/media-cleanup-audit.json` : résultats techniques.

Les captures des tests, le cache de résultats PHPUnit et les bundles de production ont été actualisés par leurs outils habituels.

## Références et dépendances contrôlées

- Tous les fichiers de `frontend/src/` sont accessibles depuis le point d’entrée : aucun composant orphelin détecté. Après nettoyage, aucune liaison inutilisée détectée dans ces sources.
- Les cinq dépendances React d’exécution sont toutes importées : Axios, html-react-parser, React, React DOM, React Router. Vite et son plugin React servent à compiler ; Playwright sert aux tests. Aucun package ou lockfile retiré ni mis à jour.
- L’analyse statique a été complétée par les pages issues de `editorial.json`, les galeries API, les chemins CSS, `srcSet`, crédits, images de repli, liens historiques, routes Laravel, seeders et lanceurs.
- Les 21 références SQL distinctes des champs `destinations.image` et `tour_images.path` comprennent 12 chemins locaux publics présents, 8 chemins conservés dans l’archive historique et une URL distante historique. Les 35 variantes WebP vérifiées sont présentes. Aucun chemin SQL local non retrouvé dans le public ou l’archive.
- Les médias des villes archivées n’ont pas été remis dans le site public. La base n’a pas été modifiée.
- Les 68 fichiers contrôlés d’environnement, configuration, migrations, CSS et assets publics sont identiques à la sauvegarde. Les styles, images et logos ne sont pas modifiés par ce nettoyage.
- Le contrôle final élargi aux manifestes, lockfiles, seeders et routes confirme **80 fichiers protégés inchangés**. Le SHA-256 de l’archive privée a été revérifié et la seule disparition parmi les sources sauvegardées est la page de bienvenue listée ci-dessus.

## Fichiers conservés malgré une utilité indirecte ou incertaine

| Ensemble | Motif de conservation |
| --- | --- |
| `dist/` à la racine, `legacy-original/`, `start.py`, `DEMARRER-WINDOWS.bat` | Sauvegardes originales protégées ; le lanceur statique, les outils d’extraction, le contrôle des empreintes et un test d’upload utilisent encore l’archive. La condition d’une archive entièrement inutilisée n’est pas remplie. |
| Originaux Commons et variantes écartées dans `legacy-original/verified-photo-sources/` | Traçabilité, licences et régénération. Leur absence du site public ne justifie pas de supprimer une sauvegarde originale. |
| Huit anciennes photos présentes à la fois dans `dist/assets/` et `legacy-original/public-assets/` | Doublons confirmés par SHA-256, mais copies d’archives protégées et médias référencés historiquement dans SQL. |
| Logos présents dans l’archive et `frontend/public/assets/` | Racines distinctes : archive et site React. Une seule copie ne servirait pas les deux chemins actuels. |
| `frontend/src/assets/photos.json` et `backend/database/data/verified-photos.json` | Contenu identique nécessaire aux deux applications déployées séparément ; généré depuis la même chaîne d’optimisation. |
| Trois JPEG de destination | Replis explicites, SEO et données historiques SQL ; leur retrait casserait le mode dégradé. |
| `backend/resources/js/`, `backend/resources/css/`, `backend/vite.config.js`, `backend/package.json` | Point d’entrée Vite secondaire déclaré dans la configuration et les scripts Composer. Inactif pour le site React actuel, mais retrait incertain et configurations explicitement protégées. |
| Tests `ExampleTest.php`, factories, helpers de tests | Tests existants protégés ; aucune suppression fondée seulement sur leur nom ou caractère générique. |
| Seeders, migrations, `legacy.json`, schéma SQL et outils de réparation | Installation, historique, restauration et contrôle d’exclusion du catalogue public. |
| `backend/database/database.sqlite` | Fichier de base de données conservé ; ce nettoyage ne décide pas de sa valeur ni ne touche son contenu. |
| `.local-mysql/`, sauvegardes SQL privées | Données et sauvegardes protégées, même si l’instance active utilise le port 3306. |
| `frontend/node_modules/`, `backend/vendor/` | Dépendances installées nécessaires aux serveurs et tests ; suppression non utile et réinstallation potentiellement dépendante du réseau. |
| `frontend/dist/` | Production régénérable, reconstruite avec succès et conservée pour le déploiement. |
| Logs, caches et sessions Laravel, fichiers d’état des tests | État technique potentiellement actif ; pas de suppression opportuniste. |
| Captures et anciens rapports `docs/` | Preuves de validation et documentation ; aucune preuve suffisante de leur inutilité. |

L’organisation React (`components`, `pages`, `layouts`, `hooks`, `services`, `assets`, `styles`) et Laravel est déjà cohérente. Aucun déplacement cosmétique susceptible de casser un chemin indirect n’a été effectué.

## Tests exécutés et résultats

| Vérification | Résultat |
| --- | --- |
| `npm.cmd run build` dans `frontend/` | Réussi, 151 modules ; CSS 34,50 kB et JS 442,81 kB. |
| `php artisan route:list` dans `backend/` | Réussi : 46 routes chargées. |
| `php artisan test` dans `backend/` | **22 tests / 127 assertions réussis** après correction des deux attentes obsolètes. SQLite en mémoire, configuration forcée par `phpunit.xml`. |
| Playwright : `photos.spec.js`, `city-scope.spec.js`, `destinations.spec.js`, `database-repair.spec.js` | **11 scénarios Edge réussis**, lecture seule des données métier MySQL ; desktop 1440 px, mobile 390 px, FR/EN, pages et formulaires de réservation, photos et replis, filtres, liens et absence de débordement horizontal. |
| `php tools/check-database.php` | Connexion à `127.0.0.1:3306`, base `moroway_tours` en utf8mb4, 18 tables présentes. |
| `php tools/verify-city-scope.php` | **66 contrôles réussis**, trois villes publiques exclusivement, réservations inchangées pendant le contrôle. |
| `php tools/audit-media.php` | 35 WebP présents ; médias SQL locaux retrouvés dans le public ou l’archive. |
| `node tools/audit-frontend.mjs` | Imports résolus, aucun fichier source inaccessible ni liaison inutilisée détectée. |
| `node tools/verify-original.mjs` | 14 fichiers originaux identiques, SHA-256. |
| `npm.cmd ls --depth=0 --prefix frontend` | Les huit dépendances directes installées sont résolues. |
| `composer validate --no-check-publish --working-dir=backend` | `composer.json` valide. |
| `node --check` des deux fichiers JS dont un import a été retiré ; `php -l tools/audit-media.php` | Réussis. |

Les réservations, messages, capacités, permissions et opérations admin ont été testés dans **SQLite en mémoire**, sans toucher la base MySQL active. Les cinq scénarios de `migration.spec.js` n’ont **pas** été rejoués : leur `beforeAll` crée un utilisateur dans MySQL et deux scénarios y écrivent des réservations/messages. Ils sont conservés. La soumission réelle d’une nouvelle réservation depuis le navigateur et le parcours admin navigateur ne sont donc pas revendiqués comme retestés pendant ce nettoyage.

## Erreurs et limites restantes

Aucune erreur bloquante détectée par les contrôles exécutés. Les deux échecs PHP du premier passage étaient des attentes obsolètes et ont été corrigés ; le passage complet suivant réussit.

L’URL distante d’un média historique n’a pas été vérifiée sur Internet pendant ce nettoyage ; elle est conservée et ne fait pas partie des photographies publiques approuvées. Aucun test de déploiement distant, test de charge ou audit exhaustif d’accessibilité n’a été exécuté. Aucun gain de performance ou maintien de « 100 % » des comportements non testés n’est affirmé.

Pour restaurer le seul fichier supprimé, extraire l’entrée `backend/resources/views/welcome.blade.php` du ZIP privé vers le même chemin. Ne pas restaurer tout le ZIP par-dessus les fichiers d’environnement actifs.
