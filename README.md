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

---

## 👤 Auteur

**Yassine EL-AASMI**
GitHub : [@yassineelaa](https://github.com/yassineelaa)
