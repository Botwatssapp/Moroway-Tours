# Changements livrés

> Rapport historique : l’ancienne version HTML et certains fichiers mentionnés ont depuis été retirés ou déplacés. État actuel et restauration : [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md).


- Création de `frontend/` en React/Vite : composants, pages, layout, hooks, contexte FR/EN, service Axios et routes propres.
- Reprise du CSS d’origine, des deux logos, des photos locales, des typographies, de la palette, des cartes, du menu, des transitions, du scroll, des accordéons et de la galerie au clavier.
- Reprise des pages initiales et ajout d’une véritable page 404 ; maintien des espaces sur mesure, étudiants, FAQ et mentions pratiques.
- Création de `backend/` avec Laravel/Sanctum, migrations, modèles/relations, validations, policy, middleware et configuration CORS/CSRF.
- Import idempotent des 29 offres et de leurs programmes bilingues ; photos avec crédits, destinations et avis de démonstration identifiés.
- Remplacement des réservations/brouillons locaux par une API SQL, confirmations conditionnées à l’enregistrement, statuts et devis final privé.
- Création du dashboard : CRUD, statistiques, réservations, messages, avis, photos, packs, disponibilités, réseaux et contenus essentiels.
- Création sécurisée des administrateurs par commande interactive, sans compte ou mot de passe prédéfini.
- Maintien des prix sur mesure, sans paiement en ligne ; vérification des capacités configurées sous transaction.
- Ajout de métadonnées par page, canonical, Open Graph, données structurées pertinentes, sitemap dynamique et robots.txt.
- Ajout du guide Windows, des lanceurs spécifiques React/Laravel et base isolée, tests backend et navigateur, captures et rapport d’intégrité.
- Conservation intacte de `dist/`, de l’ancien lanceur et de `start.py` ; archivage de l’ancien README et des lanceurs dans `legacy-original/`.

Le programme détaillé s’édite actuellement en JSON structuré dans l’admin. Les contenus de présentation statiques restent dans les sources React ; le titre d’accueil et les coordonnées sont éditables via l’API. Les limites et tests réellement effectués sont détaillés dans `TESTS.md`.
