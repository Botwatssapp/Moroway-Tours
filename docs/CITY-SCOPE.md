# MOROWAY TOURS — périmètre public Fès, Tanger et Casablanca

> Rapport historique : l’ancienne version HTML et certains fichiers mentionnés ont depuis été retirés ou déplacés. État actuel et restauration : [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md).


## Données et archivage

Les 29 offres historiques ont été dépubliées : même celles passant par une ville autorisée contenaient des étapes ou images d’autres destinations. Elles restent en base avec leurs identifiants, programmes, photos, packs, disponibilités et relations. Aucune réservation ancienne n’est supprimée ou réaffectée.

Le catalogue public contient trois propositions de visites urbaines : `fes-decouverte`, `tanger-decouverte`, `casablanca-decouverte`. Chaque programme reste exclusivement dans sa ville, utilise sa photo existante, garde la tarification sur devis et précise que le parcours et les prestations restent à confirmer par l’agence. Ces programmes éditoriaux ne promettent ni départ garanti, ni établissement réservé, ni prestation déjà contractualisée.

État local après intervention : **32 offres conservées, dont 3 publiées**, dix destinations conservées en base et seulement trois exposées publiquement. L’intégrité des réservations a été vérifiée avant et après l’actualisation et les tests.

## Protection du périmètre public

- Le modèle Tour applique un périmètre SQL commun : publication, départ/arrivée autorisés, destination autorisée et marqueur de périmètre dans le contenu JSON.
- Un contrôle complémentaire refuse la publication publique des textes, programmes, photos ou packs hors périmètre. Une offre historique republiée par erreur reste inaccessible publiquement.
- La même règle couvre les listes, recherches, détails, demandes de réservation et le sitemap. Une ancienne fiche répond en 404 et une nouvelle réservation sur une ancienne offre est refusée.
- Les avis liés à une offre archivée ou contenant des références hors périmètre sont exclus de l’API publique. Les contenus de configuration incompatibles sont masqués publiquement, sans effacer leur valeur enregistrée.
- L’administration conserve les données historiques ; le marqueur et le programme des nouvelles offres sont visibles dans l’éditeur de contenu structuré. Les futures photos publiques doivent appartenir au registre des médias validés pour les trois villes.

## Pages et médias

Accueil, destinations, circuits, détails, galerie, services, contact, projets sur mesure, étudiants, réservation, menus, filtres, métadonnées et sitemap ont été alignés sur les trois villes, en FR et EN.

Les photos hors périmètre ont été déplacées de `frontend/public/assets/` vers `legacy-original/public-assets/`. Elles restent aussi dans l’archive HTML originale. Elles ne sont plus copiées dans le build React. Les photos existantes de Fès, Tanger et Casablanca, leurs crédits et les deux logos sont conservés.

Les règles CSS de couleurs, typographies, espacements, responsive, transitions et animations sont inchangées. Seules les URL des images de contenu hors périmètre ont été remplacées. Les composants et classes de cartes, header, footer, formulaires et galeries sont conservés. Les sections promotionnelles de l’accueil relatives aux anciennes destinations ont été retirées et les textes restants adaptés.

## Contrôles effectués

- **44 contrôles d’intégration PHP/API** : catalogue public, destinations, recherches, filtres, fiches archivées, tentative de republication, validation et enregistrement SQL d’une réservation autorisée, refus des offres archivées, avis, configuration et sitemap.
- Les écritures de ce contrôle sont réalisées dans une transaction annulée à la fin ; les réservations et offres historiques sont comparées avant/après.
- **4 parcours Edge complets**, FR/EN aux largeurs 1440 et 390 px : 18 routes publiques par combinaison, absence de contenu hors périmètre, images et liens, recherche, anciennes fiches/réservations et absence de débordement horizontal.
- **8 scénarios Edge de non-régression** : catalogue SQL/React, destinations desktop/mobile, bilingue, redirections, filtres, galerie clavier, transmission date/voyageurs, réservation/contact SQL, authentification admin, CSRF, menu mobile et 404.
- Captures desktop/mobile générées et inspectées visuellement dans `docs/screenshots/`.
- Compilation Vite de production réussie ; fichiers servis depuis `frontend/dist/`.
- Contrôle des règles CSS et des logos : règles conservées, seuls les chemins d’images ont changé ; logos identiques aux fichiers originaux.
- Les 14 fichiers HTML/CSS/JS/médias du site original sont inchangés (SHA-256).

Les anciens tests PHPUnit ont été adaptés aux nouvelles données de référence. La suite utilisant le mécanisme de migrations n’a pas été relancée, afin de conserver la contrainte précédente de ne pas utiliser ce mécanisme ; les vérifications PHP/API ci-dessus utilisent directement la base locale et une transaction annulée.

## Commandes et lancement

Depuis la racine : `php tools/apply-city-scope.php` applique de façon répétable le catalogue urbain et dépublie les offres hors périmètre sans suppression. `php tools/verify-city-scope.php` vérifie l’intégration sans conserver ses écritures de test. Aucun changement de schéma ni commande de migration n’est nécessaire.

Pour les tests navigateur avec Laravel et Vite démarrés : `npx playwright test` dans `frontend/`. Pour compiler : `npm run build` dans ce même dossier.

Utiliser `DEMARRER-REACT-LARAVEL.bat` et le serveur SQL habituel sur 3306. **Le dossier racine `dist/`, `start.py` et `DEMARRER-WINDOWS.bat` sont des archives de l’ancienne version : ne pas les publier ou les utiliser pour prévisualiser la nouvelle version.** Le script d’extraction historique écrit désormais ses résultats dans l’archive, afin de ne pas réintroduire les anciens contenus dans React.

## Mise à jour des photographies

Les photographies et leurs contrôles ont été enrichis : voir `IMAGES.md`. Le vérificateur `tools/verify-city-scope.php` effectue désormais 66 contrôles en lecture seule, sans transaction d’écriture sur la base active. Les tests de réservation sont réalisés séparément avec des enregistrements temporaires identifiés.
