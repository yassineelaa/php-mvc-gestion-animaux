

# php-mvc-gestion-animaux

## 🇫🇷 Version française

### Sommaire

* [Présentation](#fr-presentation)
* [✨ Fonctionnalités](#fr-fonctionnalites)
* [🧱 Stack & prérequis](#fr-stack)
* [📁 Structure du projet](#fr-structure)
* [🧠 Architecture (MVCR)](#fr-architecture)
* [🗃️ Base de données](#fr-bdd)
* [⚙️ Configuration](#fr-config)
* [🚀 Lancer en local](#fr-run)
* [🧭 Routes (site)](#fr-routes)
* [🔌 API JSON](#fr-api)
* [🔐 Sécurité](#fr-securite)
* [🧪 Tests rapides](#fr-tests)
* [📄 Licence](#fr-licence)
* [👤 Auteur](#fr-auteur)

---

### <a id="fr-presentation"></a>Présentation

Application web **PHP (MVCR)** de **gestion d’animaux** avec **MySQL** et une **API JSON** minimale.
Objectif : créer / lister / consulter / supprimer des animaux, afficher les pages HTML côté site, et exposer des données côté API.

---

### <a id="fr-fonctionnalites"></a>✨ Fonctionnalités

* **CRUD Animaux**

  * Créer un animal (nom, espèce, âge, image facultative)
  * Lister & consulter le détail
  * Supprimer
* **Upload d’images** (PNG/JPEG) avec validations de base
* **API JSON** de lecture (liste + détail)
* **Architecture MVCR** claire : Router → Controller → Model/Storage (PDO) → View
* **Accès BDD sécurisé** : **PDO + requêtes préparées** (anti-injection SQL)

---

### <a id="fr-stack"></a>🧱 Stack & prérequis

* **PHP** ≥ 7.4
* **MySQL** 5.7+ / 8.0
* **Serveur web** (Apache/Nginx) ou serveur interne `php -S`
* Aucune dépendance Composer obligatoire

---

### <a id="fr-structure"></a>📁 Structure du projet

```
php-mvc-gestion-animaux/
├─ site.php                  # Entrée du site (HTML)
├─ api.php                   # Entrée API (JSON, lecture)
├─ css/
│  └─ style.css
└─ src/
   ├─ Router.php             # Routage (actions -> contrôleur)
   ├─ control/
   │  └─ Controller.php      # Logique métier (create/delete/list/view)
   ├─ model/
   │  ├─ Animal.php
   │  ├─ AnimalBuilder.php   # Validation des champs (name/species/age)
   │  ├─ AnimalStorage.php   # Interface de stockage
   │  ├─ AnimalStorageMySQL.php    # Implémentation MySQL (PDO préparé)
   │  ├─ AnimalStorageSession.php  # Stockage en session (ex. maquette)
   │  └─ AnimalStorageStub.php     # Stub / squelette
   └─ view/
      ├─ View.php            # Vues HTML
      └─ JSONView.php        # Sorties JSON (API)
```

---

### <a id="fr-architecture"></a>🧠 Architecture (MVCR)

* **Router** : lit `$_GET['action']`, appelle la bonne méthode du **Controller**.
* **Controller** : récupère/valide les entrées (via **AnimalBuilder**), appelle le **Storage**.
* **Model/Storage** : requêtes **PDO préparées** vers MySQL.
* **View** : génère l’HTML (échappement `htmlspecialchars`).

Schéma rapide :

```
Request → site.php?action=... → Router → Controller → AnimalStorageMySQL (PDO) → View (HTML)
                                                     └→ JSONView (API)
```

---

### <a id="fr-bdd"></a>🗃️ Base de données

Créer la base et la table `animals` :

```sql
CREATE TABLE animals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  species VARCHAR(255) NOT NULL,
  age INT NOT NULL,
  image VARCHAR(255) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### <a id="fr-config"></a>⚙️ Configuration

Le projet attend une configuration **PDO** incluse par `site.php` et `api.php`.

**Option A — Mettre la config dans le projet**

1. Crée `config/mysql_config.php` avec :

```php
<?php
$dsn  = 'mysql:host=localhost;dbname=zoo;charset=utf8mb4';
$user = 'root';
$pass = '';

$pdo = new PDO($dsn, $user, $pass, [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

2. Dans `site.php` et `api.php`, inclure :

```php
require_once __DIR__ . '/config/mysql_config.php';
```

**Option B — Chemin externe (privé)**
Place le fichier de config en dehors du repo (ex. répertoire privé) et ajuste le `require_once` en conséquence.
💡 Évite de versionner des identifiants (ajoute la config au `.gitignore`).

---

### <a id="fr-run"></a>🚀 Lancer en local

1. Crée la table (script SQL ci-dessus)
2. Configure `config/mysql_config.php`
3. Lance un serveur :

```bash
php -S localhost:8000
```

4. Ouvre :

* **Site** : `http://localhost:8000/site.php`
* **Liste** : `http://localhost:8000/site.php?action=list`

---

### <a id="fr-routes"></a>🧭 Routes (site)

* **Liste** : `site.php?action=list`
* **Détail** : `site.php?action=view&id={ID}`
* **Formulaire de création** : `site.php?action=new`
* **Création (POST)** : `site.php?action=save`
* **Suppression** : `site.php?action=delete&id={ID}`

---

### <a id="fr-api"></a>🔌 API JSON

Entrée : **`api.php`** (retours JSON)

* **Liste des animaux**
  `GET /api.php?collection=animaux`
  **Réponse** : tableau JSON d’animaux

* **Détail d’un animal**
  `GET /api.php?collection=animaux&id={ID}`
  **Réponse** : objet JSON (ou `404` si introuvable)

> L’API actuelle est **lecture seule**. Les routes **POST/PUT/DELETE** peuvent être ajoutées facilement (voir *Roadmap*).

---

### <a id="fr-securite"></a>🔐 Sécurité

* **Anti-injection SQL** : **PDO + requêtes préparées** (dans `AnimalStorageMySQL.php`)
* **Anti-XSS (sorties)** : `htmlspecialchars(...)` dans les vues
* **Validation serveur** : `AnimalBuilder` (obligatoires / types / formats)

---

### <a id="fr-tests"></a>🧪 Tests rapides

* Créer 2–3 animaux (dont un avec image)
* Vérifier liste / détail / suppression
* Tester l’API dans le navigateur :

  * `/api.php?collection=animaux`
  * `/api.php?collection=animaux&id=1`

---

### <a id="fr-licence"></a>📄 Licence

Projet académique — libre d’usage et d’amélioration à des fins pédagogiques.

---

### <a id="fr-auteur"></a>👤 Auteur

**Yassine EL-AASMI**
GitHub : [@yassineelaa](https://github.com/yassineelaa)

---

## 🇬🇧 English version

### Table of Contents

* [Overview](#en-overview)
* [✨ Features](#en-features)
* [🧱 Stack & Prerequisites](#en-stack)
* [📁 Project Structure](#en-structure)
* [🧠 Architecture (MVCR)](#en-architecture)
* [🗃️ Database](#en-db)
* [⚙️ Configuration](#en-config)
* [🚀 Run Locally](#en-run)
* [🧭 Routes (site)](#en-routes)
* [🔌 JSON API](#en-api)
* [🔐 Security](#en-security)
* [🧪 Quick Tests](#en-tests)
* [📄 License](#en-license)
* [👤 Author](#en-author)

---

### <a id="en-overview"></a>Overview

Web application in **PHP (MVCR)** for **animal management** with **MySQL** and a minimal **JSON API**.
Goal: create / list / view / delete animals, render HTML pages for the site, and expose read-only data via an API.

---

### <a id="en-features"></a>✨ Features

* **Animals CRUD**

  * Create an animal (name, species, age, optional image)
  * List & view details
  * Delete
* **Image upload** (PNG/JPEG) with basic validations
* **Read-only JSON API** (list + detail)
* **Clear MVCR architecture**: Router → Controller → Model/Storage (PDO) → View
* **Secure DB access**: **PDO + prepared statements** (SQL injection protection)

---

### <a id="en-stack"></a>🧱 Stack & Prerequisites

* **PHP** ≥ 7.4
* **MySQL** 5.7+ / 8.0
* **Web server** (Apache/Nginx) or PHP built-in server `php -S`
* No mandatory Composer dependencies

---

### <a id="en-structure"></a>📁 Project Structure

```
php-mvc-gestion-animaux/
├─ site.php                  # Site entrypoint (HTML)
├─ api.php                   # API entrypoint (JSON, read-only)
├─ css/
│  └─ style.css
└─ src/
   ├─ Router.php             # Routing (actions -> controller)
   ├─ control/
   │  └─ Controller.php      # Business logic (create/delete/list/view)
   ├─ model/
   │  ├─ Animal.php
   │  ├─ AnimalBuilder.php   # Field validation (name/species/age)
   │  ├─ AnimalStorage.php   # Storage interface
   │  ├─ AnimalStorageMySQL.php    # MySQL implementation (prepared PDO)
   │  ├─ AnimalStorageSession.php  # Session storage (e.g., prototype)
   │  └─ AnimalStorageStub.php     # Stub / skeleton
   └─ view/
      ├─ View.php            # HTML views
      └─ JSONView.php        # JSON outputs (API)
```

---

### <a id="en-architecture"></a>🧠 Architecture (MVCR)

* **Router**: reads `$_GET['action']`, calls the proper **Controller** method.
* **Controller**: collects/validates inputs (via **AnimalBuilder**), calls the **Storage**.
* **Model/Storage**: **prepared PDO** queries to MySQL.
* **View**: generates HTML (with `htmlspecialchars` escaping).

Quick sketch:

```
Request → site.php?action=... → Router → Controller → AnimalStorageMySQL (PDO) → View (HTML)
                                                     └→ JSONView (API)
```

---

### <a id="en-db"></a>🗃️ Database

Create the database and the `animals` table:

```sql
CREATE TABLE animals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  species VARCHAR(255) NOT NULL,
  age INT NOT NULL,
  image VARCHAR(255) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### <a id="en-config"></a>⚙️ Configuration

The project expects a **PDO** configuration required by both `site.php` and `api.php`.

**Option A — Put the config inside the project**

1. Create `config/mysql_config.php`:

```php
<?php
$dsn  = 'mysql:host=localhost;dbname=zoo;charset=utf8mb4';
$user = 'root';
$pass = '';

$pdo = new PDO($dsn, $user, $pass, [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

2. In `site.php` and `api.php`, include it:

```php
require_once __DIR__ . '/config/mysql_config.php';
```

**Option B — External (private) path**
Place the config file outside the repo (e.g., a private directory) and adjust `require_once` accordingly.
💡 Avoid committing credentials (add the config to `.gitignore`).

---

### <a id="en-run"></a>🚀 Run Locally

1. Create the table (SQL script above)
2. Configure `config/mysql_config.php`
3. Start a server:

```bash
php -S localhost:8000
```

4. Open:

* **Site**: `http://localhost:8000/site.php`
* **List**: `http://localhost:8000/site.php?action=list`

---

### <a id="en-routes"></a>🧭 Routes (site)

* **List**: `site.php?action=list`
* **Detail**: `site.php?action=view&id={ID}`
* **Create form**: `site.php?action=new`
* **Create (POST)**: `site.php?action=save`
* **Delete**: `site.php?action=delete&id={ID}`

---

### <a id="en-api"></a>🔌 JSON API

Entrypoint: **`api.php`** (JSON responses)

* **Animals list**
  `GET /api.php?collection=animaux`
  **Response**: JSON array of animals

* **Animal detail**
  `GET /api.php?collection=animaux&id={ID}`
  **Response**: JSON object (or `404` if not found)

> The current API is **read-only**. **POST/PUT/DELETE** routes can be added easily (see *Roadmap*).
> *(Note: the collection parameter intentionally uses the French word `animaux` to match the implementation.)*

---

### <a id="en-security"></a>🔐 Security

* **SQL injection protection**: **PDO + prepared statements** (see `AnimalStorageMySQL.php`)
* **Anti-XSS (outputs)**: `htmlspecialchars(...)` in views
* **Server-side validation**: `AnimalBuilder` (required fields / types / formats)

---

### <a id="en-tests"></a>🧪 Quick Tests

* Create 2–3 animals (include one with an image)
* Check list / detail / deletion
* Test the API in the browser:

  * `/api.php?collection=animaux`
  * `/api.php?collection=animaux&id=1`

---

### <a id="en-license"></a>📄 License

Academic project — free to use and improve for educational purposes.

---

### <a id="en-author"></a>👤 Author

**Yassine EL-AASMI**
GitHub: [@yassineelaa](https://github.com/yassineelaa)

---

