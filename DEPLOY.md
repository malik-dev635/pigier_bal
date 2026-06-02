# Déploiement sur `https://pblog.ci/bal`  (serveur diablo / user `ckbstade`)

Le domaine **pblog.ci** est un domaine addon : sa racine web est `/home/ckbstade/pblog.ci/`
(et non `public_html`). On déploie l'app Laravel dans un sous-dossier `/bal` **sans toucher**
au site PHP existant.

## Arborescence cible (sécurisée)

```
/home/ckbstade/
├── pblog.ci/                         ← racine web du domaine pblog.ci (site existant)
│   ├── index.php, admin, api, ...    ← on n'y touche pas
│   └── bal  ──► lien vers /home/ckbstade/bal_app/public
└── bal_app/                          ← TOUT le code Laravel (hors web = invisible)
    ├── app/ config/ routes/ vendor/ ...
    ├── .env
    └── public/                       ← seul dossier exposé (via /bal)
```

Seul `bal_app/public` est accessible par le web. Le `.env`, la base, le code restent privés.

---

## 0. Vérifications rapides (à lancer maintenant en SSH)

```bash
php -v                                  # doit être 8.2 ou 8.3 (Laravel 11)
which composer || ls ~/composer.phar    # composer dispo ?
ls -la ~/pblog.ci | grep -i htaccess    # y a-t-il un .htaccess à la racine du site ?
```

Si `php -v` montre < 8.2 → cPanel → **MultiPHP Manager** → passe `pblog.ci` en PHP 8.2/8.3.

---

## 1. Créer la base MySQL (cPanel → MySQL Databases)

1. **Create New Database** : ex. `bal` → nom réel préfixé : `ckbstade_bal`
2. **Add New User** : ex. `bal` → réel `ckbstade_bal` + mot de passe fort
3. **Add User To Database** → coche **ALL PRIVILEGES**

Note `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

---

## 2. Préparer le paquet EN LOCAL (machine Windows)

```powershell
# A. Assets de production (URL du sous-dossier pour le CSS/JS)
$env:ASSET_URL = "https://pblog.ci/bal"
npm run build
Remove-Item Env:\ASSET_URL

# B. Dépendances PHP en mode production (on les embarque pour éviter composer côté serveur)
composer install --no-dev --optimize-autoloader

# C. Copie propre sans node_modules/.git/.env, puis ZIP
robocopy . ..\bal_app_deploy /E /XD node_modules .git /XF .env > $null
Compress-Archive -Path ..\bal_app_deploy\* -DestinationPath ..\bal_app.zip -Force
```

Tu obtiens `..\bal_app.zip` (à côté du dossier projet).

---

## 3. Envoyer et déposer le code

Téléverse `bal_app.zip` dans `/home/ckbstade/` (cPanel File Manager ou SFTP), puis en SSH :

```bash
cd ~
mkdir -p bal_app
unzip -o bal_app.zip -d bal_app
ls bal_app           # doit montrer app/ public/ vendor/ artisan ...
```

---

## 4. Configurer l'application (SSH)

```bash
cd ~/bal_app

cp .env.production.example .env
nano .env            # renseigne DB_DATABASE / DB_USERNAME / DB_PASSWORD

php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force

chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Si tu n'as PAS embarqué `vendor` dans le zip, lance d'abord :
> `composer install --no-dev --optimize-autoloader` (ou `php ~/composer.phar install --no-dev --optimize-autoloader`).

---

## 5. Brancher le sous-dossier `/bal`

```bash
ln -s /home/ckbstade/bal_app/public /home/ckbstade/pblog.ci/bal
ls -la ~/pblog.ci/bal        # doit pointer vers .../bal_app/public
```

Teste : **https://pblog.ci/bal/login**

### Si 404 / 500 / réécriture cassée
Édite `~/bal_app/public/.htaccess`, ajoute `RewriteBase /bal/` après `RewriteEngine On` :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /bal/
    ...
</IfModule>
```

Si l'hébergeur refuse les liens symboliques : crée `~/pblog.ci/bal/`, copies-y le contenu de
`~/bal_app/public/`, puis dans `~/pblog.ci/bal/index.php` fais pointer les deux `require`
vers `/home/ckbstade/bal_app/...`.

---

## 6. 🔒 Sécurité post-déploiement (OBLIGATOIRE)

Les comptes de démo ont des mots de passe publics :

```bash
cd ~/bal_app
# Nouveau mot de passe admin
php artisan tinker --execute="\$u=App\Models\User::where('email','admin@pigier.test')->first(); \$u->password=bcrypt('MOT_DE_PASSE_FORT'); \$u->save(); echo 'admin maj';"
# Supprimer les comptes démo prof + élève
php artisan tinker --execute="App\Models\User::whereIn('email',['prof@pigier.test','eleve@pigier.test'])->delete(); echo 'demos supprimes';"
```

Confirme dans `.env` : `APP_ENV=production` et `APP_DEBUG=false`.

---

## 7. Mises à jour ultérieures

En local : refais l'étape 2 (build + zip). Sur le serveur :

```bash
cd ~ && unzip -o bal_app.zip -d bal_app
cd ~/bal_app && php artisan migrate --force \
  && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## URL une fois en ligne

| Page | URL |
|------|-----|
| Connexion | https://pblog.ci/bal/login |
| Inscription élève | https://pblog.ci/bal/inscription |
| Espace de vote | https://pblog.ci/bal/vote |
| Administration | https://pblog.ci/bal/admin |
