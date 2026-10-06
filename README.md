# Gestion scolaire

Application de gestion scolaire : élèves, classes, matières, notes, absences.
**Stack :** Vue 3 · Vue Router · Pinia · Tailwind CSS · PHP · SQLite

**Lien déployé :** _à compléter_ · **Captures d'écran :** _à ajouter dans `docs/`_

## Lancer en local
```bash
# Back (depuis la racine du projet)
cd api
php -S localhost:8000 index.php
# Front (autre terminal)
cd front && npm install && npm run dev
```
Au premier appel de l'API, la base SQLite est créée avec un compte admin.
Son email et son mot de passe sont écrits dans `api/data/first-admin.txt` (ignoré par git).
Pour les choisir vous-même : variables d'environnement `ADMIN_EMAIL` et `ADMIN_PASSWORD`.

## Structure
- `api/` : routeur (`index.php`), une responsabilité par fichier (`auth`, `students`, `catalog`, `grades`, `absences`, `stats`), `schema.sql`
- `front/src/` : `views/`, `components/`, `stores/` (un store Pinia par domaine), `router/`, `services/api.js`

## Calcul des moyennes
Moyenne d'une matière = moyenne des notes pondérées par leur coefficient.
Moyenne générale = moyenne des matières pondérées par le coefficient de la matière.

## Déploiement
`cd front && npm run build`, puis sur l'hébergeur PHP : contenu de `front/dist/` à la racine web et dossier `api/` à côté.
Ajouter une règle de réécriture vers `index.html` pour le routeur Vue (mode history).
