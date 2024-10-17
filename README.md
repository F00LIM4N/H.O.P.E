# Introduction à la solution HOPE (installation et usage)

Workshop 2024-2025 - Groupe 8 : 
- CRUCIANI Valentine;
- DERRIEN Hugo;
- RAMBAUD Cécile;
- AZNAR Léo

Application Symfony permettant de se former, sur différents thèmes comme le cyberharcèlement, 
la cybersécurité et d'autres modules de formations. Plusieurs pages accessibles selon la connexion et le rôle affecté à
l'utilisateur.

## Pré-requis
- PHP version >= 8.1;
- PostgreSQL version >= 14.9;
- Symfony version >= 6.4.1;
- Composer >= 2.2.6;
- Node >= 20.5.1;

## Environnement de développement

### En local
#### Installer les dépendances :
```shell
composer install
```
```shell
npm install
```
#### Compilation avec Webpack :
```shell
npm run watch
```

#### Lancer le server :
```shell
symfony serve
```

### Pour démarrer le projet
- Dupliquer le fichier env et le rennomer en ".env.local";
- Démarrer un serveur.

## Environnement de production

### Prérequis :
- vérifier que les variables d'environnements sont correctes (APP_ENV=prod, etc ...)
- installer les dépendaces :
```shell
composer install
```
```shell
npm install
```
- Compiler avec Webpack :
```shell
npm run build
```