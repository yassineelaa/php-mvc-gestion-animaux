# php-mvc-gestion-animaux

Application web **PHP (MVCR)** de **gestion d’animaux** avec **MySQL** et une **API JSON** minimale.
Objectif : créer / lister / consulter / supprimer des animaux, afficher les pages HTML côté site, et exposer des données côté API.

---

## ✨ Fonctionnalités

* **CRUD Animaux**

  * Créer un animal (nom, espèce, âge, image facultative)
  * Lister & consulter le détail
  * Supprimer 
* **Upload d’images** (PNG/JPEG) avec validations de base
* **API JSON** de lecture (liste + détail)
* **Architecture MVCR** claire : Router → Controller → Model/Storage (PDO) → View
* **Accès BDD sécurisé** : **PDO + requêtes préparées** (anti-injection SQL)

---

## 🧱 Stack & prérequis

* **PHP** ≥ 7.4
* **MySQL** 5.7+ / 8.0
* **Serveur web** (Apache/Nginx) ou serveur interne `php -S`
* Aucune dépendance Composer obligatoire

---

## 📁 Structure du projet

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

## 🧠 Architecture (MVCR)

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

## 🗃️ Base de données

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

## ⚙️ Configuration

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

## 🚀 Lancer en local

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

## 🧭 Routes (site)

* **Liste** : `site.php?action=list`
* **Détail** : `site.php?action=view&id={ID}`
* **Formulaire de création** : `site.php?action=new`
* **Création (POST)** : `site.php?action=save`
* **Suppression** : `site.php?action=delete&id={ID}` 


---

## 🔌 API JSON

Entrée : **`api.php`** (retours JSON)

* **Liste des animaux**
  `GET /api.php?collection=animaux`
  **Réponse** : tableau JSON d’animaux

* **Détail d’un animal**
  `GET /api.php?collection=animaux&id={ID}`
  **Réponse** : objet JSON (ou `404` si introuvable)

> L’API actuelle est **lecture seule**. Les routes **POST/PUT/DELETE** peuvent être ajoutées facilement (voir *Roadmap*).

---

## 🔐 Sécurité

* **Anti-injection SQL** : **PDO + requêtes préparées** (dans `AnimalStorageMySQL.php`)
* **Anti-XSS (sorties)** : `htmlspecialchars(...)` dans les vues
* **Validation serveur** : `AnimalBuilder` (obligatoires / types / formats)



---




## 🧪 Tests rapides

* Créer 2–3 animaux (dont un avec image)
* Vérifier liste / détail / suppression
* Tester l’API dans le navigateur :

  * `/api.php?collection=animaux`
  * `/api.php?collection=animaux&id=1`

---


## 📄 Licence

Projet académique — libre d’usage et d’amélioration à des fins pédagogiques.

Voici une **version anglaise** prête à coller sous ton README 👇

---

## 🇬🇧 English Version — php-mvc-gestion-animaux

Web application in **PHP (MVCR)** for **animal management** with **MySQL** and a minimal **JSON API**.
Goal: create / list / view / delete animals, render HTML pages for the site, and expose read-only data via an API.

---

## ✨ Features

* **Animals CRUD**

  * Create an animal (name, species, age, optional image)
  * List & view details
  * Delete
* **Image upload** (PNG/JPEG) with basic validations
* **Read-only JSON API** (list + detail)
* **Clear MVCR architecture**: Router → Controller → Model/Storage (PDO) → View
* **Secure DB access**: **PDO + prepared statements** (SQL injection protection)

---

## 🧱 Stack & Prerequisites

* **PHP** ≥ 7.4
* **MySQL** 5.7+ / 8.0
* **Web server** (Apache/Nginx) or PHP built-in server `php -S`
* No mandatory Composer dependencies

---

## 📁 Project Structure

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

## 🧠 Architecture (MVCR)

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

## 🗃️ Database

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

## ⚙️ Configuration

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

## 🚀 Run Locally

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

## 🧭 Routes (site)

* **List**: `site.php?action=list`
* **Detail**: `site.php?action=view&id={ID}`
* **Create form**: `site.php?action=new`
* **Create (POST)**: `site.php?action=save`
* **Delete**: `site.php?action=delete&id={ID}`

---

## 🔌 JSON API

Entrypoint: **`api.php`** (JSON responses)

* **Animals list**
  `GET /api.php?collection=animaux`
  **Response**: JSON array of animals

* **Animal detail**
  `GET /api.php?collection=animaux&id={ID}`
  **Response**: JSON object (or `404` if not found)

> The current API is **read-only**. **POST/PUT/DELETE** routes can be added easily (see *Roadmap*).

*(Note: the collection parameter currently uses the French word `animaux` to match the implementation.)*

---

## 🔐 Security

* **SQL injection protection**: **PDO + prepared statements** (see `AnimalStorageMySQL.php`)
* **Anti-XSS (outputs)**: `htmlspecialchars(...)` in views
* **Server-side validation**: `AnimalBuilder` (required fields / types / formats)

---

## 🧪 Quick Tests

* Create 2–3 animals (include one with an image)
* Check list / detail / deletion
* Test the API in the browser:

  * `/api.php?collection=animaux`
  * `/api.php?collection=animaux&id=1`

---

## 📄 License

Academic project — free to use and improve for educational purposes.

---

## 👤 Author

**Yassine EL-AASMI**
GitHub: [@yassineelaa](https://github.com/yassineelaa)


---

## 👤 Auteur

**Yassine EL-AASMI**
GitHub : [@yassineelaa](https://github.com/yassineelaa)
