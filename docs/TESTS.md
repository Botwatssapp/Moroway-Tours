# Vérifications de livraison

> État après nettoyage total : voir [NETTOYAGE-TOTAL.md](NETTOYAGE-TOTAL.md). Les résultats ci-dessous sont les validations historiques.


Effectuées le 24 octobre 2026, sur Windows, avec PHP 8.2.12, Node 24.12.0, Laravel 12.70.1, Sanctum 4.3.3 et Edge en mode headless.

## Résultats obtenus

| Vérification | Résultat |
| --- | --- |
| PHPUnit sur SQLite en mémoire | **20 tests réussis, 108 assertions** |
| Même suite avec le pilote Laravel MySQL sur MariaDB XAMPP 10.4.32 | **20 tests réussis, 108 assertions** |
| Playwright avec Edge, serveurs Vite/Laravel et base SQL locale | **5 scénarios réussis** |
| Captures complémentaires desktop/mobile | Réalisées et inspectées visuellement |
| Vérification SHA-256 des sources originales | **14 fichiers inchangés** |
| Build Vite de production | Réussi ; sortie dans `frontend/dist/` |
| Migrations, seeder et lien des médias | Exécutés sur la base MariaDB locale |

## Périmètre backend testé

- Import des 29 circuits, détail avec photos et réponse 404.
- Filtres de ville/type/durée et recherche serveur.
- Réservation réellement enregistrée ; impossibilité pour le client de s’attribuer un statut confirmé ou un devis.
- Rejet des dates passées, voyageurs invalides, e-mails incorrects, absence de consentement et honeypot renseigné.
- Vérification que le pack appartient au circuit.
- Dates fermées, capacité épuisée et libération après annulation ; circuit dépublié non réservable.
- Demandes sur mesure et étudiantes sans circuit fixe.
- Enregistrement et validation du contact.
- Accès privé refusé aux visiteurs et comptes non administrateurs.
- Connexion, mot de passe erroné, déconnexion et refus d’une connexion non admin.
- CRUD circuits/destinations ; modification du statut et du devis ; conservation d’un circuit associé à une réservation.
- Packs, disponibilités, avis fictifs et réglages ; rejet des domaines sociaux non autorisés.
- Rejet d’un programme mal formé et acceptation de la structure éditoriale importée.
- Rejet des SVG, upload réel d’un PNG, suppression du fichier uploadé.
- Limitation des tentatives de connexion et formulaires.
- Sitemap avec les circuits publiés et exclusion de l’administration.

## Périmètre navigateur testé

1. Accueil, 9 cartes éditoriales branchées sur l’API, chargement du logo local, changement FR/EN, redirection d’une ancienne URL vers le circuit.
2. Filtrage réel côté serveur, détail et ses 3 jours, galerie modale, flèches/Escape, conservation date/voyageurs vers le formulaire de réservation.
3. Réservation et contact envoyés depuis le navigateur, confirmations après réponse du serveur, puis comptage SQL des deux enregistrements avec le même identifiant de test.
4. Réponse 401 sans session, réponse 419 sans jeton CSRF, connexion admin via cookies, consultation des demandes/messages, déconnexion.
5. Menu mobile et navigation contact aux largeurs **390 et 768 px**, réduction du mouvement, absence de débordement horizontal, page 404.

Le script de capture complémentaire vérifie les pages circuit et réservation à 390 px et enregistre l’accueil/détail à 1440 px. Les captures sont dans `docs/screenshots/`. Les comptes et demandes de test navigateur sont temporaires et retirés à la fin du scénario.

## Limites constatées et points de lancement

- Le pilote MySQL a été testé sur **MariaDB**, pas sur un serveur Oracle MySQL. Les migrations utilisent les types et contraintes compatibles MySQL ; prévoir un passage de la même suite sur le serveur cible.
- La capacité a été testée de façon séquentielle ; le code utilise une transaction et un verrou de circuit, mais aucun test de charge/concurrence multi-processus n’a été exécuté.
- Aucun test Safari, Firefox ou appareil physique ; aucune mesure chiffrée Core Web Vitals/Lighthouse.
- L’outil d’aperçu intégré a expiré. Les vérifications visuelles ont été réalisées via Edge headless et des captures locales.
- Les informations de l’entreprise, liens sociaux, conditions d’annulation et durée de conservation n’ont pas été inventés. Ils doivent être renseignés avant exploitation commerciale.
- Les trois avis importés restent marqués **fictifs**. Aucun avis client authentique n’a été inventé.
- Aucun e-mail automatique, paiement, déploiement public ou connexion à une plateforme de voyage externe n’a été effectué.
- Le site est une SPA : voir la stratégie de pré-rendu/SSR et les réglages de production dans le README.

## Reproduire

Suivre l’installation du README, puis `php vendor/bin/phpunit` dans `backend/`. Pour la suite SQL : base dédiée `moroway_tours_test`, `.env.testing` configuré, puis `php vendor/bin/phpunit --configuration phpunit.mysql.xml`.

Avec Laravel et Vite actifs : `npx playwright test` depuis `frontend/`. Les identifiants temporaires sont générés en mémoire par le helper CLI `tools/e2e-fixture.php`. Pour vérifier les originaux, exécuter `node tools/verify-original.mjs` depuis la racine.
