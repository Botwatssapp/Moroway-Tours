# Audit et plan de migration

> Rapport historique : l’ancienne version HTML et certains fichiers mentionnés ont depuis été retirés ou déplacés. État actuel et restauration : [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md).


## Source conservée

`dist/` reste le site d’origine exécutable par `python start.py`. Les anciens README et lanceurs sont copiés dans `legacy-original/`. Les tailles et SHA-256 de tous les fichiers HTML, CSS, JS et médias fournis figurent dans `original-manifest.json`.

Le site initial comprend un HTML avec CSS et fonctions intégrées, `collection.js`, `tour-details.js`, `tour-details.css` et dix médias locaux (logos et photos). Aucun framework ni dépendance npm d’origine. Google Fonts fournit DM Sans et Playfair Display ; quelques photos restent externes via Unsplash.

## Inventaire fonctionnel

- Pages à fragments : accueil, destinations, destination, expériences, expérience, réservation, contact, à propos, services, galerie, administration, conditions, confidentialité ; extensions collections, départs, sur mesure, étudiants et FAQ.
- 29 offres : 3 expériences initiales et 26 programmes éditoriaux privés, partagés, grands itinéraires et excursions. Textes FR/EN, jours détaillés, étapes, points forts, nuitées, prestations, liens Maps et crédits photos.
- Filtres : ville de départ, collection et durée ; recherche d’accueil et mémorisation de la date/du groupe.
- Identité : couleurs vert `#203b35`, crème `#faf8f2`, terre cuite `#c6653d`, logo chameau ; cartes, sections, galeries, accordéons et en-tête sticky.
- Comportements : logo flottant, apparition au défilement, zoom des images, hover des cartes et boutons, progression de lecture, menu mobile, galerie modale avec flèches/Escape, réactions locales aux avis et réduction du mouvement.
- Formulaires initiaux : réservation et projets seulement en localStorage ; contact non connecté ; administration publique de démonstration. Aucun stockage serveur, authentification, paiement ou véritable disponibilité.
- Avis : trois exemples explicitement fictifs ; aucun témoignage réel inventé. Coordonnées professionnelles et réseaux non fournis.

## Migration réalisée par étapes

1. Extraire les données et présentations bilingues, conserver les CSS et médias ; sceller l’intégrité des originaux.
2. Créer React/Vite avec composants JSX, contexte de langue/données, React Router, formulaires et effets React. Les fragments éditoriaux statiques extraits sont analysés en éléments React ; aucun script Vanilla d’origine n’est exécuté dans le frontend.
3. Créer Laravel, modèles/relations/migrations, import idempotent, API publique, validation, limitation des requêtes, Sanctum SPA et autorisation serveur.
4. Brancher Axios, formulaires SQL et CRUD admin ; conserver le devis personnalisé et les anciennes URL à fragments.
5. Compiler et tester catalogue, filtrage, validation, SQL, authentification, autorisations, disponibilité, médias, avis, réseaux et sitemap. Vérifier séparément le navigateur et les limites d’infrastructure.
6. Documenter installation Windows, déploiement, SEO, tests réels et limites restantes.

## Changements justifiés

Les brouillons en localStorage ne deviennent jamais automatiquement des réservations : leur migration risquerait d’envoyer des demandes sans consentement. Le stockage local subsiste uniquement pour langue, préférences de recherche et réactions aux avis. L’ancienne administration publique est remplacée par une connexion privée. Les avertissements « aucun envoi » sont remplacés par des confirmations basées sur les réponses SQL. Les textes juridiques reflètent le nouveau traitement serveur mais restent à compléter avec les informations de l’exploitant.
