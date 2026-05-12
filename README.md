# 🚗 AuraRide - Plateforme Premium de VTC & Location

AuraRide est une application web de VTC et de location de véhicules haut de gamme construite avec **Laravel 12**. Elle se distingue par une interface moderne, immersive et interactive utilisant **Bootstrap 5**, **GSAP** pour les animations et **Leaflet** pour la cartographie.

---

## 🛠️ Guide d'Installation (Pas à pas)

Suivez ces étapes scrupuleusement pour installer le projet sur votre machine locale.

### 1. Prérequis
Assurez-vous d'avoir installé :
*   **PHP 8.2+**
*   **Composer**
*   **Node.js & npm**
*   **MySQL** (ou un serveur type XAMPP/WAMP)

### 2. Clonage du projet
```bash
git clone https://github.com/Leandre99/Auraride.git
cd Auraride
```

### 3. Installation des dépendances
```bash
# Dépendances PHP
composer install

# Dépendances JavaScript & CSS
npm install
```

### 4. Configuration de l'environnement
Copiez le fichier d'exemple et générez la clé de l'application :
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configuration de la base de données
1. Créez une base de données nommée `auraride` dans votre gestionnaire MySQL.
2. Ouvrez le fichier `.env` et vérifiez les informations de connexion :
   ```env
   DB_DATABASE=auraride
   DB_USERNAME=votre_utilisateur
   DB_PASSWORD=votre_mot_de_passe
   ```

### 6. Configuration des Emails (Gmail)
Pour que l'envoi d'emails fonctionne, vous devez configurer vos identifiants dans le `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-d-application
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_ADMIN_EMAIL=votre-email@gmail.com
```
*Note : Le mot de passe doit être un **"Mot de passe d'application"** généré dans votre compte Google.*

### 7. Initialisation de la base de données
Cette commande crée les tables et ajoute les utilisateurs de test (Admin, Chauffeurs, Clients) :
```bash
php artisan migrate --seed
```

### 8. Compilation des assets & Lancement
```bash
# Compilation des fichiers CSS/JS
npm run build

# Lancement du serveur de développement
php artisan serve
```

---

## ⚡ Fonctionnalités Clés
*   **Système de VTC** : Demande de course en temps réel avec suivi sur carte.
*   **Location de véhicules** : Réservation de véhicules avec ou sans chauffeur.
*   **Interface Chauffeur** : Dashboard dédié pour accepter les courses, contacter le client (Appel/WhatsApp) et valider les paiements.
*   **Interface Admin** : Gestion complète des utilisateurs, des revenus et des demandes en attente.
*   **Notifications Temps Réel** : Utilisation de Laravel Reverb pour une réactivité instantanée.
*   **Emails Automatiques** : Confirmations et reçus envoyés instantanément (mode `sync`).

---

## 🔑 Comptes de Test
Une fois le `seed` effectué, vous pouvez vous connecter avec :
*   **Admin** : `admin@atlasandco.com` / `password`
*   **Chauffeur** : `driver@atlasandco.com` / `password`
*   **Client** : `client@atlasandco.com` / `password`

---

## 📦 Commandes Utiles
*   `php artisan config:clear` : Vider le cache de configuration.
*   `php artisan reverb:start` : Lancer le serveur de WebSockets (pour le temps réel en local).