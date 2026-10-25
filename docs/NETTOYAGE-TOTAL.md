# Nettoyage total MOROWAY TOURS — 25 octobre 2026

Ce rapport remplace les décisions de conservation du premier passage. Le code React et Laravel, les données SQL et les photos publiques restent en place ; les sources de l’ancienne version statique sont sorties du dossier actif.

## Point de restauration créé avant suppression

Une nouvelle archive privée a été construite et contrôlée avant toute suppression. Ses 333 entrées initiales ont été relues, vérifiées par SHA-256 et contrôlées par CRC. Elle contient les sources, configurations, originaux photo, documents et build présents avant intervention. Les versions différentes contenues dans l’ancienne sauvegarde ont été intégrées sous `_previous_snapshot/`, y compris la page de bienvenue supprimée au premier passage. Chaque contenu de l’ancienne sauvegarde est donc présent soit à son chemin original, soit dans ce sous-dossier historique.

- Archive : `backend/storage/app/private/maintenance/restore-project-20261025-160401.zip`.
- Taille : 153,296,873 octets.
- SHA-256 : `cc3288a49f9c0fa3539d5ee5d1638b77782c27397dcb426e7de19addb212f9be`.
- Manifeste : `backend/storage/app/private/maintenance/restore-manifest-20261025-160401.json`.
- Référence courante : `backend/storage/app/private/maintenance/restore-point.json`.

Les dépendances installées, données MySQL, sessions/logs actifs et sauvegardes SQL ne sont pas inclus dans cette archive de sources : ils sont restés intacts. L’archive contient les variables d’environnement et ne doit pas être publiée. La sauvegarde SQL privée antérieure reste conservée séparément.

Pour restaurer un fichier, extraire uniquement son entrée vers son ancien chemin. Ne pas écraser globalement les environnements actifs. Les anciennes versions spécifiques sont dans `_previous_snapshot/`. `node tools/verify-original.mjs` vérifie directement les 14 fichiers historiques dans le ZIP, sans réinstaller l’ancien site.

## Suppressions réellement effectuées

**56 fichiers supprimés**, représentant 154,933,392 octets retirés de leurs emplacements antérieurs. Ce total inclut l’ancien ZIP de sauvegarde ; ce n’est pas un gain net d’espace disque, car le nouveau point de restauration est conservé.

| Fichier supprimé | Octets | Motif vérifié |
| --- | ---: | --- |
| `dist/assets/agafay.jpg` | 148978 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/ait-benhaddou.jpg` | 365339 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/atlas.jpg` | 364980 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/chefchaouen.jpg` | 418606 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/dades.jpg` | 528709 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/essaouira.jpg` | 241058 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/merzouga-caravan.jpg` | 126014 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/moroway-camel-logo.png` | 1687538 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/moroway-tours-logo.png` | 2625435 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/assets/sahara-sunset.jpg` | 295293 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/collection.js` | 26792 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/index.html` | 41899 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/tour-details.css` | 19931 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `dist/tour-details.js` | 46302 | Ancienne version HTML remplacee par React; copie restauree possible depuis le ZIP verifie. |
| `start.py` | 638 | Lance uniquement ancien HTML retire. |
| `DEMARRER-WINDOWS.bat` | 314 | Ancien lanceur statique; lanceur React/Laravel conserve. |
| `legacy-original/start.py` | 638 | Doublon du lanceur obsolete. |
| `legacy-original/DEMARRER-WINDOWS.bat` | 314 | Doublon du lanceur obsolete. |
| `legacy-original/README.md` | 3150 | Documentation de la version HTML archivee. |
| `tools/extract-legacy.mjs` | 3142 | Migration ponctuelle achevee; ecrirait des donnees historiques devenues obsoletes. |
| `backend/resources/css/app.css` | 413 | Entree Tailwind du squelette, aucune vue backend; React utilise ses CSS propres. |
| `backend/resources/js/app.js` | 22 | Entree JS du squelette, aucune vue backend. |
| `backend/resources/js/bootstrap.js` | 127 | Axios global du squelette uniquement importe par son entree JS retiree. |
| `backend/vite.config.js` | 331 | Build secondaire sans vue utilisatrice; scripts backend rediriges vers le frontend actif. |
| `backend/tests/Unit/ExampleTest.php` | 243 | Demonstration assertTrue(true), aucun comportement applicatif teste; tests fonctionnels conserves. |
| `backend/README.md` | 4109 | README generique Laravel; documentation operationnelle a la racine. |
| `docs/scope-design-manifest.json` | 46061 | Ancienne copie inline du code anterieure aux photos; disponible dans la restauration, aucune utilisation par les outils actuels. |
| `legacy-original/verified-photo-sources/casablanca-mosque.jpg` | 2165520 | Candidat photo rejete, absent du registre approuve et du site. |
| `legacy-original/verified-photo-sources/casablanca-mosque.json` | 920 | Candidat photo rejete, absent du registre approuve et du site. |
| `legacy-original/verified-photo-sources/tanger-market.jpg` | 10360032 | Candidat photo rejete, absent du registre approuve et du site. |
| `legacy-original/verified-photo-sources/tanger-market.json` | 683 | Candidat photo rejete, absent du registre approuve et du site. |
| `legacy-original/verified-photo-sources/unused-webp/casablanca-mosque-1600.webp` | 106074 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/casablanca-mosque-2200.webp` | 166044 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/casablanca-mosque-480.webp` | 14268 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/casablanca-mosque-960.webp` | 46664 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/tanger-market-1600.webp` | 168238 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/tanger-market-2200.webp` | 421606 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/tanger-market-480.webp` | 22226 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `legacy-original/verified-photo-sources/unused-webp/tanger-market-960.webp` | 64332 | Variante generee dun candidat rejete; aucune reference publique ni SQL. |
| `backend/storage/app/private/maintenance/sources-before-cleanup-20261025-154758.zip` | 131889867 | Sauvegarde obsolete consolidee; chaque contenu distinct verifie dans le nouveau point de restauration. |
| `backend/storage/app/private/maintenance/inventory-20261025-154758.json` | 2430433 | Ancien inventaire conserve dans le point de restauration consolide. |
| `tools/.test-results/tmp/cv_debug.log` | 123 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/3f/3f4bc47590_88d58ce_destinationsspec.js` | 7707 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/3f/3f4bc47590_88d58ce_destinationsspec.map` | 4380 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/82/8216a627fd_f63ab25_photosspec.js` | 18210 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/82/8216a627fd_f63ab25_photosspec.map` | 10344 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/ce/ce7b382f3a_2712c46_cityscopespec.js` | 17408 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/ce/ce7b382f3a_2712c46_cityscopespec.map` | 9899 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/e1/e1fe414af0_3354096_databaserepairspec.js` | 6029 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/e1/e1fe414af0_3354096_databaserepairspec.map` | 3442 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/ef/ef471794e1_d730e04_playwrightconfig.js` | 1597 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `tools/.test-results/tmp/playwright-transform-cache/ef/ef471794e1_d730e04_playwrightconfig.map` | 848 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `backend/storage/framework/testing/tmp/sf_proc_00.err` | 122 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `backend/storage/framework/testing/tmp/sf_proc_00.err.lock` | 0 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `backend/storage/framework/testing/tmp/sf_proc_00.out` | 0 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |
| `backend/storage/framework/testing/tmp/sf_proc_00.out.lock` | 0 | Artefact temporaire cree par les tests de cette intervention, archive apres validation ; regenerable. |

Chaque fichier source supprimé correspondait à une entrée vérifiée du ZIP. Pour l’ancienne archive de sauvegarde, chaque contenu a été comparé à l’entrée conservée dans le nouveau point de restauration avant suppression. Aucun fichier n’a été supprimé sur la seule base de son nom.

Les vérifications ont porté sur les imports, le routeur React, les contenus éditoriaux parsés dynamiquement, les CSS et leurs URLs, les API, les colonnes média SQL, les routes et providers Laravel, les scripts Composer/npm, les outils et la documentation. Les anciennes références opérationnelles ont été corrigées avant les tests. Les rapports historiques conservent leurs constats datés avec un avertissement renvoyant ici.

## Médias déplacés, sans suppression de leur contenu

| Ancien dossier | Emplacement actuel | Motif |
| --- | --- | --- |
| `legacy-original/public-assets/` | `backend/storage/app/private/archived-media/assets/` | Huit médias encore référencés historiquement dans SQL ; conservation privée sans réintroduire d’autres villes dans le public. |
| `legacy-original/verified-photo-sources/` | `backend/storage/app/private/media-sources/` | Neuf originaux approuvés et leurs métadonnées, utiles aux licences et à la régénération WebP. Les deux candidats rejetés et leurs huit WebP ont été retirés après archivage. |

Les neuf SHA-256 des originaux correspondent toujours aux registres approuvés ; les huit médias historiques sont identiques aux sources sauvegardées. Aucun chemin SQL ni aucune donnée client n’a été modifié. Les 35 WebP publics, trois JPEG de repli et logos sont inchangés.

## Dossiers vides supprimés

- `dist/assets`
- `dist`
- `legacy-original`
- `backend/resources/css`
- `backend/resources/js`
- `backend/resources/views`
- `backend/resources`
- `backend/tests/Unit`
- `backend/storage/app/private/media-sources/unused-webp`
- `tools/.test-results/tmp/playwright-transform-cache/3f`
- `tools/.test-results/tmp/playwright-transform-cache/82`
- `tools/.test-results/tmp/playwright-transform-cache/ce`
- `tools/.test-results/tmp/playwright-transform-cache/e1`
- `tools/.test-results/tmp/playwright-transform-cache/ef`
- `tools/.test-results/tmp/playwright-transform-cache`
- `tools/.test-results/tmp`
- `backend/storage/framework/testing/tmp`

Les emplacements de travail de Laravel et ceux attendus par les dépendances sont conservés, même lorsqu’ils sont temporairement vides. Ils ne constituent pas des anciens dossiers de projet.

## Références et dépendances corrigées

- Le test d’upload utilise le logo identique de `frontend/public/assets/`, au lieu de dépendre de l’archive HTML.
- Les configurations PHPUnit SQLite et MySQL ne déclarent plus le répertoire Unit supprimé. Le seul scénario retiré vérifiait littéralement `true === true` ; tous les tests fonctionnels sont conservés.
- Le `package.json` backend délègue maintenant `build` et `dev` à `../frontend/`. `concurrently` reste déclaré, car `composer dev` l’utilise réellement pour ses processus. Les cinq déclarations inutiles du second build ont été retirées : `@tailwindcss/vite`, `axios`, `laravel-vite-plugin`, `tailwindcss`, `vite`. Aucun paquet installé utilisé par React n’a été désinstallé ou mis à jour.
- Le Vite secondaire Laravel et les fichiers Tailwind/bootstrap associés n’avaient plus de vue consommatrice. La seule application visuelle est le frontend React. Le script de build délégué a été exécuté avec succès.
- Les deux registres photo, les caches de métadonnées et les scripts d’acquisition/optimisation utilisent le stockage privé actuel.
- L’audit média trouve les anciens médias dans le stockage privé ; l’audit des originaux utilise le ZIP de restauration.
- L’outil d’inventaire inclut désormais aussi les sources photo et médias historiques privés dans ses prochaines sauvegardes de sources.
- README, rapport photo et références opérationnelles actualisés. Les anciens rapports sont signalés comme historiques.

## Éléments conservés par nécessité ou précaution

| Ensemble conservé | Raison |
| --- | --- |
| `.local-mysql/` et `DEMARRER-BASE-LOCALE.bat` | Instance alternative contenant des données SQL et un lanceur encore valable ; pas de preuve permettant de supprimer ses données. |
| `backend/storage/app/private/backups/` | Sauvegarde SQL ; non remplacée par une simple archive de sources. |
| `frontend/node_modules/`, `backend/vendor/` | Dépendances utiles aux serveurs, à la compilation et aux tests ; pas de purge en masse. |
| `frontend/dist/` | Build de production actuel, régénéré et conservé pour le déploiement. Les duplications avec les sources publiques sont nécessaires à cet artefact autonome. |
| Trois JPEG de repli, neuf originaux, licences et huit médias historiques | Références directes, fallback, SQL ou traçabilité. |
| Deux registres photo JSON | Nécessaires à deux applications distinctes ; même chaîne de génération. |
| Migrations, seeders, schéma SQL, `legacy.json`, outils de réparation | Installation, archivage sans perte, contrôle d’exclusion et maintenance. |
| Fichiers d’environnement et lockfiles utilisés | Configuration et reproductibilité. |
| `backend/database/database.sqlite` | Base potentielle ; son contenu n’est pas supprimé au motif qu’elle n’est pas la connexion active. |
| Tests fonctionnels, factories et helpers | Réservations, sécurité, catalogue, API, upload et tests navigateur. |
| Laravel Sail, Tinker et Pint | Outils CLI de développement/maintenance dont l’usage ponctuel ne se déduit pas des imports ; conservés par précaution. |
| Logs, caches et sessions actifs | État technique ; aucune purge susceptible de déconnecter un utilisateur ou perdre une preuve. |
| Rapports et captures utiles | Historique de validation et maintenance. Les copies de code obsolètes ont été retirées, pas les preuves des tests. |

Aucun composant React orphelin ni import inutilisé n’a été détecté par l’audit Babel. Aucune dépendance d’exécution React n’est superflue. Les dossiers React existants sont déjà organisés par composants, pages, styles, services et contexte : aucun déplacement cosmétique n’a été ajouté.

## Vérifications

- `php artisan test` : **21 tests / 126 assertions réussis**, sur SQLite en mémoire. Réservations, capacités, contacts, administration, sécurité et upload sont couverts. Le total baisse d’une unité uniquement par retrait du test `assertTrue(true)`.
- `php artisan route:list --json` : **46 routes chargées**.
- `npm.cmd run build --prefix backend` : **réussi**, délégation au frontend ; Vite compile 151 modules, CSS 34,50 kB et JavaScript 442,84 kB.
- `php tools/check-database.php` : MySQL sur `127.0.0.1:3306`, base `moroway_tours`, utf8mb4 et 18 tables disponibles.
- `php tools/verify-city-scope.php` : **66 contrôles réussis**, réservations inchangées pendant le contrôle.
- `php tools/audit-media.php` : 12 chemins publics SQL locaux et 8 médias historiques privés retrouvés ; 35 variantes approuvées présentes, aucun chemin local non résolu.
- `node tools/audit-frontend.mjs` : aucun fichier source inaccessible, aucune liaison inutilisée, imports résolus.
- `node tools/verify-original.mjs` : **14 originaux archivés validés**, archive de restauration vérifiée.
- `composer validate --no-check-publish --working-dir=backend` : valide.
- Contrôle SHA-256 : **65 fichiers protégés inchangés**, dont environnements, photos publiques, styles, migrations, seeders, routes et lockfiles ; neuf originaux photo et huit anciens médias également contrôlés.

**Playwright : 11 scénarios Edge réussis en 3,1 minutes**, sur ordinateur 1440 px et mobile 390 px, en FR/EN. Contrôle du périmètre public, filtres, destinations, 15 routes photographiques, galeries, API et variantes, fallback WebP/JPEG et absence de débordement horizontal. Les formulaires de réservation sont affichés sans soumission dans MySQL. Les cinq scénarios de `migration.spec.js` ne sont pas exécutés, car leur fixture crée un utilisateur et certaines épreuves écrivent dans la base MySQL active. Le test des écritures métier reste isolé dans SQLite. Aucune migration n’a été exécutée contre MySQL. Les tests utilisent `RefreshDatabase` uniquement pour initialiser SQLite en mémoire.

## Limites et incident corrigé

Les sessions de développement déjà actives sont conservées. `composer dev` n’a pas été relancé en parallèle pour éviter de doubler les serveurs ; le build qu’il utilise et la syntaxe des scripts ont été vérifiés. Aucun test de charge ou déploiement distant. L’URL distante d’une ancienne photo SQL n’est pas retestée sur Internet ; elle reste hors du catalogue public approuvé.

Une commande de compilation a initialement calculé son chemin temporaire dans le dossier parent, créant uniquement un cache npm/Node. L’erreur a été signalée puis annulée : le chemin exact, la date de chaque entrée et l’absence de fichiers antérieurs ont été vérifiés avant retrait de ce seul cache. Aucun fichier extérieur préexistant n’a été supprimé ou modifié. Les tests suivants utilisent des chemins temporaires absolus dans Moroway-Tours. Les sauvegardes et suppressions du projet restent dans le dossier demandé.

Aucune erreur bloquante détectée dans les contrôles exécutés. La conservation intégrale des comportements non testés n’est pas affirmée. La liste machine complète des suppressions et déplacements est dans `cleanup-actions.json`.

Nettoyage final des tests : 15 artefacts temporaires ajoutés sous `_validation_artifacts/` dans le point de restauration, contrôlés puis retirés des dossiers de travail. Le ZIP final contient 348 entrées vérifiées. La liste JSON inclut également les répertoires temporaires vides supprimés.
