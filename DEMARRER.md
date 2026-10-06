# Démarrage (Windows / PowerShell) — 2 terminaux

Terminal 1 (API) :
    cd api
    php -S localhost:8000 index.php

Terminal 2 (site) :
    cd front
    npm install
    npm run dev

Ouvrir http://localhost:5173
Identifiants admin : fichier api/data/first-admin.txt (créé à la 1re tentative de connexion).
Si Windows Defender bloque api/index.php : ajouter le dossier du projet aux exclusions.
