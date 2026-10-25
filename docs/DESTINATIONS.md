# Destinations publiques : Fès, Tanger, Casablanca

> Rapport historique : l’ancienne version HTML et certains fichiers mentionnés ont depuis été retirés ou déplacés. État actuel et restauration : [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md).


L’API `/api/destinations` expose exclusivement ces trois villes, dans cet ordre. Les cartes de l’accueil, la page Destinations et le sélecteur de recherche partagent cette même liste. Les filtres associés incluent les départs et arrivées dans la ville choisie. Les anciens filtres restent accessibles directement pour conserver les liens historiques.

Les autres lignes `destinations`, les circuits et les réservations ne sont pas supprimés. Les écrans administrateur conservent toutes les destinations. La migration de données conserve les identifiants ; elle remplace uniquement les anciennes images génériques des trois villes et complète leurs descriptions vides. Les véritables photos ajoutées par l’agence sont conservées.

Le HTML original `dist/` reste l’archive intacte. Le frontend React compilé est dans `frontend/dist/`.

## Photos correspondant aux villes

Les anciennes données associaient le désert à Fès/Tanger et Chefchaouen à Casablanca. Les photos suivantes les remplacent uniquement dans les cartes Destinations. Elles sont servies localement, sans recadrage du fichier ; le cadrage visuel utilise le `object-fit: cover` existant. Les crédits sont également fournis par l’API et accessibles sur les images au survol.

- **Fès — Bab Bou Jeloud** : Josep Renalias, [source](https://commons.wikimedia.org/wiki/File:Fes_-_Bab_Bou_Jeloud.JPG), [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/).
- **Tanger** : Kieron Wood, [source](https://commons.wikimedia.org/wiki/File:Tangier_from_the_sea.JPG), [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/).
- **Casablanca — mosquée Hassan II** : Walaadari, [source](https://commons.wikimedia.org/wiki/File:Maroc,_Mosquée_Hassan_2,_Grand_Casablanca.jpg), [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/).

Les règles de styles ajoutées sont limitées à la grille Destinations : trois colonnes desktop, avec les breakpoints, cartes, transitions, hover et animations préexistants.

## Vérifications effectuées

- Suite backend sur MariaDB locale : **22 tests réussis, 147 assertions**, dont la liste publique exacte, les liens et la conservation des destinations masquées, excursions et réservations.
- Deux scénarios Edge réussis aux largeurs **1440 et 390 px**, sur l’accueil et `/destinations` : ordre des villes, images effectivement chargées, liens vers les circuits, sélecteur de recherche et absence de débordement horizontal.
- Captures inspectées visuellement : `screenshots/destinations-1440.png` et `screenshots/destinations-390.png`.
- Base locale après migration : dix destinations conservées, 29 excursions conservées, nombre de réservations inchangé.
- Les 14 fichiers du site HTML original restent inchangés (SHA-256).
- Compilation de production exécutée dans `frontend/dist/`.
