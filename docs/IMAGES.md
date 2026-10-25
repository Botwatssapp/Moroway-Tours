# Images MOROWAY TOURS — livraison et vérifications

## Photos ajoutées, sources et licences

Neuf clichés touristiques réels sont intégrés localement. Chaque lieu a été contrôlé dans la description de la page source, les métadonnées de Wikimedia Commons et la planche de contrôle visuel. Aucun monument ou paysage n’a été généré par IA. Les originaux téléchargés sont conservés hors de la racine publique dans `backend/storage/app/private/media-sources/`.

Les droits indiqués sont ceux de la photographie sur sa page source. Les versions WebP conservent la licence correspondante ; les crédits, liens de source, liens de licence et modifications techniques sont publiés dans `frontend/public/photo-credits.html`, accessible depuis le footer et les galeries.

| Ville et photographie | Auteur | Licence de la photo | Original | Fichier WebP de référence |
| --- | --- | --- | --- | --- |
| [Bab Bou Jeloud, porte de la médina de Fès](https://commons.wikimedia.org/wiki/File:Bab_Bou_Jeloud_(Fes,_Marocco).jpg) | Petar Milošević | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 6000 × 6469 | `fes-gate-1600.webp` |
| [Cour de la médersa Bou Inania à Fès](https://commons.wikimedia.org/wiki/File:Bou_inania_DSCF4610.jpg) | Robert Prazeres | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 2974 × 2210 | `fes-bou-inania-1600.webp` |
| [Cour de la mosquée Al-Qarawiyyin à Fès](https://commons.wikimedia.org/wiki/File:Al-Qarawiyyin_University,_Fes,_Morocco_-_Var_132.jpg) | Khonsali | [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/) | 1600 × 1200 | `fes-qarawiyyin-1600.webp` |
| [Architecture de la Kasbah de Tanger](https://commons.wikimedia.org/wiki/File:Kasbah_of_Tangier_(87649).jpg) | Ideophagous | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 4608 × 3456 | `tanger-kasbah-1600.webp` |
| [Tanger, entre la Kasbah et le front de mer](https://commons.wikimedia.org/wiki/File:Tangier,_near_the_Kasbah_and_the_seaside_(65193).jpg) | Ideophagous | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 4608 × 3456 | `tanger-seaside-1600.webp` |
| [Place du Grand Socco à Tanger](https://commons.wikimedia.org/wiki/File:Tangier_-_Grand_Socco_-_20200906181817.jpg) | Shbbkr | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 6000 × 4000 | `tanger-socco-1600.webp` |
| [Architecture du quartier Habous à Casablanca](https://commons.wikimedia.org/wiki/File:Quartier_Habous_Casablanca.jpg) | Photo2023 | [CC BY 4.0](https://creativecommons.org/licenses/by/4.0) | 3024 × 4032 | `casablanca-habous-1600.webp` |
| [Mosquée Hassan II à Casablanca, vue à travers un cadre tenu par le photographe](https://commons.wikimedia.org/wiki/File:Framing_Heritage_-_Hassan_II_Mosque.jpg) | OussamaHamama | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 5184 × 3456 | `casablanca-hassan-ii-1600.webp` |
| [Arcades du quartier Habous à Casablanca](https://commons.wikimedia.org/wiki/File:Houbous_Casablanca_01.jpg) | Taloubant | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0) | 4284 × 5712 | `casablanca-habous-arches-1600.webp` |

La page Al-Qarawiyyin distingue le statut de l’architecture et celui de la photographie : la photo de Khonsali est utilisée sous **CC BY-SA 3.0**, et non déclarée à tort dans le domaine public.

## Intégration et emplacements

- **Accueil** : fond Hero haute résolution de Bou Inania à Fès, préchargement adapté à la largeur de l’écran, couvertures des trois destinations et excursions, photographie de Tanger dans la section de présentation.
- **Destinations / Tours** : couvertures WebP cohérentes avec chaque ville et chaque excursion, ALT descriptifs FR/EN, variantes responsive et repli sur une photographie vérifiée de la même ville.
- **TourDetails** : trois vues distinctes par excursion, images haute définition dans la lightbox, source et licence consultables ; priorité de chargement pour le Hero.
- **Services** : trois illustrations dans les cartes existantes, sans modifier leur grille. Les hébergements et transports sont illustrés par la ville, sans présenter un établissement ou véhicule comme réservé.
- **Galerie** : neuf photographies distinctes, sans doublons de fichiers au sein de la galerie ; navigation clavier, zoom, crédits et licences.
- **À propos** : vue vérifiée de la cour Al-Qarawiyyin à Fès.
- **Réservation** : photographie correspondant au circuit sélectionné et crédit dans le panneau récapitulatif.
- **Sur mesure / Étudiants** : photographies vérifiées de Fès et du Grand Socco à Tanger.
- **Contact** : photographie du quartier Habous à Casablanca.

La même couverture peut être réutilisée pour identifier un circuit dans sa carte et sa réservation ; les neuf clichés sont distincts et chaque galerie de circuit contient trois fichiers différents. Les trois anciens JPEG des villes sont conservés comme solutions de repli, avec leur attribution initiale.

## Optimisation et remplacement

- **35 variantes WebP**, largeurs 480, 960, 1600 et jusqu’à 2200 px lorsque la source le permet ; aucune image n’est agrandie artificiellement.
- Originaux sélectionnés : **72.87 Mo**. Neuf WebP de référence : **2.78 Mo**, soit **96.2 %** de réduction du poids des fichiers de référence par rapport aux originaux.
- Neuf variantes 480 px : **316 Ko** au total. Ce chiffre décrit les fichiers, pas une mesure globale de chargement d’une page.
- `srcset`, `sizes`, dimensions intrinsèques, `decoding="async"`, lazy loading pour les images secondaires ; préchargement/fetch priority élevés pour les visuels prioritaires.
- `object-fit: cover` et cadrage centré dans les cadres existants ; `contain` dans la lightbox pour montrer la photo entière.
- En cas d’erreur : photographie de remplacement de la même ville, ALT indiquant explicitement le remplacement ; si elle échoue aussi, cadre aux couleurs de la marque, nom de la ville et message lisible.
- Aucun téléchargement distant de photo au moment de la visite : seuls les fichiers locaux optimisés sont utilisés.

## API Laravel et MySQL

Le registre `verified-photos.json` associe chaque photographie à sa ville, sa provenance et sa licence. Le contrôle de périmètre n’accepte que les médias approuvés pour la ville du circuit. L’API des images fournit variantes, dimensions, ALT anglais, auteur, licence et position de cadrage.

Le seeder `VerifiedPhotoSeeder` ajoute ou actualise les associations sans supprimer les photographies historiques, les excursions archivées, les packs ou les réservations. L’API publique sélectionne les trois WebP approuvés de chaque excursion ; les anciennes images de repli restent en base et sur disque. Aucun changement de schéma ni commande de migration n’a été exécuté.

## Vérifications réellement effectuées

- **4 scénarios Edge spécifiques aux photos réussis** : registre/API/variantes, tous les emplacements sur 15 routes en desktop 1440 px et mobile 390 px, et simulation de pannes WebP/JPEG.
- **12 scénarios Edge de non-régression réussis**, en plusieurs passages : parcours FR/EN, périmètre des trois villes, destinations, catalogue SQL/React, filtres, galerie au clavier, réservation et contact enregistrés en SQL, connexion/déconnexion admin, CSRF, menu mobile et 404.
- **66 contrôles PHP/API en lecture seule réussis**, sans modifier les réservations. L’outil de vérification du périmètre est désormais en lecture seule.
- Captures de l’accueil, des services, de la galerie et de la réservation générées et inspectées dans `docs/screenshots/photos-*.png`. Planche des neuf photographies : `docs/screenshots/verified-photo-contact-sheet.jpg`.
- Compilation de production React réussie ; variantes incluses dans `frontend/dist/`.
- Les règles existantes de typographie, couleurs, grilles et animations n’ont pas été modifiées. Les styles ajoutés ciblent les nouveaux cadres photo, les crédits, les replis et les URLs du fond Hero.
- Les logos et les 14 fichiers de l’archive HTML originale restent inchangés (SHA-256).

Un verrou du moteur MariaDB local a interrompu un premier test de réservation. Les deux connexions de test identifiées ont été fermées ; le serveur a été relancé normalement, sans suppression ou remplacement de fichiers de données. InnoDB a annulé les transactions de test. Une sauvegarde SQL privée est conservée dans `backend/storage/app/private/backups/before-media-recovery-20261025-153318.sql` (non publiée et ignorée par Git). La réservation, le contact, l’authentification et l’absence de verrous ont ensuite été revérifiés avec succès.

## Emplacements sans photographie spécifique vérifiée

Aucun cadre photo public prévu ne reste vide. Aucune photographie vérifiée de **l’équipe réelle**, d’un **hôtel précisément contractualisé** ou d’un **véhicule de l’agence** n’a été fournie : aucun faux portrait, faux établissement ou faux véhicule n’a été ajouté. Les Services utilisent des illustrations de destination avec une légende explicite. FAQ, mentions légales et textes fonctionnels conservent leur présentation textuelle et ne comportent pas de cadre photo vide. Les médias des 29 anciennes offres restent archivés hors du site public.

## Fichiers créés ou modifiés

Frontend :

- `frontend/public/assets/photos/` : 35 WebP ; `frontend/public/photo-credits.html`.
- `frontend/src/assets/photos.json`, `frontend/src/assets/editorial.json`, `frontend/index.html`.
- `frontend/src/components/Photo.jsx`, `PhotoCredit.jsx`, `Common.jsx`, `DestinationCards.jsx`, `Editorial.jsx`.
- `frontend/src/pages/TourDetails.jsx`, `Forms.jsx`, `frontend/src/layouts/SiteLayout.jsx`.
- `frontend/src/styles/photos.css`, `frontend/src/main.jsx`.
- `frontend/tests/photos.spec.js` et adaptations des tests de galerie, destination et périmètre aux nouveaux médias.

Backend et outils :

- `backend/app/Support/VerifiedMedia.php`, `PublicCatalogue.php`, `backend/app/Models/TourImage.php`.
- `backend/app/Http/Controllers/Api/PublicController.php`.
- `backend/database/seeders/VerifiedPhotoSeeder.php`, `DatabaseSeeder.php`, `backend/database/data/verified-photos.json`, `public-destinations.json`.
- `tools/acquire-verified-photos.py`, `tools/optimize-verified-photos.py`, `tools/apply-verified-photos.php`, `tools/verify-city-scope.php`.
- `.gitignore` : sauvegardes SQL privées exclues du suivi.

Les assets optimisés sont livrés et ne nécessitent pas Python pour compiler ou lancer le site. Pour régénérer les variantes, le script d’optimisation nécessite Pillow. Les photos source et leurs métadonnées sont conservées pour permettre cette régénération.
