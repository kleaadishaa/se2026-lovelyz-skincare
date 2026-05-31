# Software Design Document (SDD)

Ky dokument përshkruan arkitekturën e sistemit, strukturën e folderave të kodit dhe skemën logjike të bazës së të dhënave (MySQL) për platformën Lovelyz Skincare.

---

## 1. Arkitektura e Sistemit (Client-Server Architecture)
Sistemi është i ndërtuar mbi arkitekturën e decoupling (ndarjes së plotë) të Frontend-it nga Backend-i:
* **Presentation Layer (Frontend):** HTML5, CSS3 (Responsive Design), dhe JavaScript (Fetch API për komunikim asinkron).
* **Application Layer (Backend):** REST API e ndërtuar me Pure PHP, e cila menaxhon autentikimin (JWT), sigurinë (Rate Limiting) dhe logjikën e biznesit.
* **Data Layer (Database):** MySQL për ruajtjen e të dhënave të klientëve, produkteve dhe porosive.

---

## 2. Struktura e Folderave të Projektit
Për një menaxhim sa më të pastër dhe inxhinierik, kodi është i ndarë si më poshtë:

se2026-lovelyz-skincare/
├── .github/
│   └── ISSUE_TEMPLATE/
│       ├── bug.md
│       └── feature.md
├── src/                          # Gjithë kodi burimor
│   ├── auth/                     # Autentikimi (login, signup, JWT)
│   ├── api/                      # REST API endpoints
│   ├── includes/                 # Konfigurime dhe lidhja me DB
│   ├── assets/                   # CSS, imazhe dhe resurse statike
│   ├── database/
│   │   └── myfirstdatabase_2_.sql
│   ├── admin.html
│   ├── cart.html
│   ├── index.html
│   ├── index.php
│   ├── orders.html
│   ├── profile.html
│   └── script.js
├── tests/                       
│   ├── login_test.php
│   ├── signup_test.php
│   └── cart_test.php
├── docs/                         
│   ├── wireframes/
│   ├── SRS.md
│   ├── SDD.md
│   ├── test-report.md
│   ├── deployment.md
│   ├── user-manual.md
│   ├── sprint-1-review.md
│   └── sprint-1-retro.md
├── .gitignore
├── composer.json
└── README.md
---

## 3. Skema e Databazës (DB Schema)

Baza e të dhënave përbëhet nga 4 tabela kryesore të lidhura me çelësa të huaj (Foreign Keys) për të garantuar integritetin referencial:

### A. Tabela: `users`
| Kolona | Lloji (Type) | Atributet | Përshkrimi |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Id-ja unike e përdoruesit |
| `name` | VARCHAR(100) | NOT NULL | Emri dhe mbiemri |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Email-i i përdoruesit (përdoret për login) |
| `password` | VARCHAR(255) | NOT NULL | Fjalëkalimi i enkriptuar me `bcrypt` |
| `role` | VARCHAR(20) | DEFAULT 'client' | Roli në sistem (`client` ose `admin`) |

### B. Tabela: `products`
| Kolona | Lloji (Type) | Atributet | Përshkrimi |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Id-ja unike e produktit |
| `name` | VARCHAR(100) | NOT NULL | Emri i produktit të skincares |
| `price` | DECIMAL(10,2) | NOT NULL | Çmimi i produktit |
| `image_url` | VARCHAR(255) | NOT NULL | Path-i i fotos së ruajtur në server |

### C. Tabela: `orders`
| Kolona | Lloji (Type) | Atributet | Përshkrimi |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Id-ja unike e porosisë |
| `user_id` | INT | FOREIGN KEY REFERENCES `users(id)` | Klienti që ka bërë porosinë |
| `total_price` | DECIMAL(10,2) | NOT NULL | Vlera totale e porosisë |
| `status` | VARCHAR(50) | DEFAULT 'Pending' | Statusi (`Pending`, `Shipped`, `Cancelled`) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Data dhe ora e blerjes |

### D. Tabela: `order_details` (Lidhja Many-to-Many mes Porosive dhe Produkteve)
| Kolona | Lloji (Type) | Atributet | Përshkrimi |
| :--- | :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Id unike e rreshtit |
| `order_id` | INT | FOREIGN KEY REFERENCES `orders(id)` ON DELETE CASCADE | |
| `product_id` | INT | FOREIGN KEY REFERENCES `products(id)` | |
| `quantity` | INT | NOT NULL | Sasia e blerë për atë produkt |
# 1. Skema e Databazës

## Tabela: users

| Kolona     | Tipi | Përshkrimi |
|------------|------|------------|
| id         | INT PRIMARY KEY AUTO_INCREMENT | ID unike e përdoruesit |
| username   | VARCHAR(255) UNIQUE | Emri i përdoruesit (për login) |
| pwd        | VARCHAR(255) | Fjalëkalimi i hash-uar |
| email      | VARCHAR(255) UNIQUE | Adresa e email-it |
| role       | ENUM('admin','client') | Roli i përdoruesit në sistem |
| created_at | DATETIME | Data dhe ora e krijimit të llogarisë |

---

## Tabela: rate_limits

| Kolona       | Tipi | Përshkrimi |
|--------------|------|------------|
| id           | INT PRIMARY KEY AUTO_INCREMENT | ID unike e rreshtit |
| ip_address   | VARCHAR(45) | Adresa IP (IPv4/IPv6) |
| request_time | INT | Koha e kërkesës (Unix timestamp) |

---

## Tabela: products

| Kolona      | Tipi | Përshkrimi |
|-------------|------|------------|
| product_id  | INT PRIMARY KEY AUTO_INCREMENT | ID unike e produktit |
| name        | VARCHAR(255) | Emri i produktit |
| category    | VARCHAR(255) | Kategoria |
| description | TEXT | Përshkrim i detajuar |
| price       | DECIMAL(10,2) | Çmimi |
| stock       | INT | Sasia në magazinë |
| image       | VARCHAR(255) | Rruga ose linku i fotos |

---

## Tabela: discounts

| Kolona              | Tipi | Përshkrimi |
|---------------------|------|------------|
| discount_id         | INT PRIMARY KEY AUTO_INCREMENT | ID unike e zbritjes |
| product_id          | INT FK -> products(product_id) | Produkti që ka zbritje |
| discount_percent    | INT | Përqindja e zbritjes |
| start_date          | DATETIME | Data e fillimit |
| end_date            | DATETIME | Data e mbarimit |
| created_by_user_id  | INT FK -> users(id) | Admini që e krijoi |

---

## Tabela: orders

| Kolona            | Tipi | Përshkrimi |
|------------------|------|------------|
| order_id         | INT PRIMARY KEY AUTO_INCREMENT | ID unike e porosisë |
| user_id          | INT FK -> users(id) | Klienti që bëri porosinë |
| status           | ENUM('pending','processing','shipped','cancelled') | Statusi i porosisë |
| total_price      | DECIMAL(10,2) | Totali i porosisë |
| shipping_street  | VARCHAR(255) | Rruga e dërgesës |
| shipping_city    | VARCHAR(100) | Qyteti |
| shipping_country | VARCHAR(100) | Shteti |
| created_at       | DATETIME | Data e krijimit |

---

## Tabela: order_details

| Kolona            | Tipi | Përshkrimi |
|------------------|------|------------|
| order_detail_id  | INT PRIMARY KEY AUTO_INCREMENT | ID e detajit të porosisë |
| order_id         | INT FK -> orders(order_id) | Porosia kryesore |
| product_id       | INT FK -> products(product_id) | Produkti |
| product_name     | VARCHAR(255) | Emri i produktit në momentin e blerjes |
| price            | DECIMAL(10,2) | Çmimi për njësi |
| discount_percent | INT DEFAULT 0 | Zbritja e aplikuar |
| quantity         | INT | Sasia e porositur |

## 2. API Endpoints

| Metoda | Endpoint | Përshkrimi |
|---|---|---|
| POST | /api/register | Regjistrim |
| POST | /api/login | Login + JWT |
| GET | /api/products | Lista produkteve |
| POST | /api/upload_product | Shto produkt |
| DELETE | /api/delete_product | Fshi produkt |
| POST | /api/orders/create_order.php | Krijo porosi |
| GET | /api/clients/get_user | Merr profilin |
| PUT | /api/clients/update_user | Përditëso profilin |
| DELETE | /api/clients/delete_user | Fshi llogarinë |
| PUT | /api/clients/change_password | Ndrysho passwordin |
| POST | /api/cart/add_to_cart.php | Shto produkt në shportë |
| GET | /api/cart/get_cart.php | Merr artikujt e shportës |
| POST | /api/cart/checkout.php | Krijo porosinë dhe pastro shportën |
| DELETE | /api/cart/delete_cart_item.php | Fshi artikull nga shporta |
| GET | /api/orders/get_orders.php | Merr të gjitha porositë |
| GET | /api/orders/get_orders_details.php | Merr detajet e porosisë |
| PUT | /api/orders/update_order.php | Përditëso statusin e porosisë |
| DELETE | /api/orders/delete_order.php | Fshi porosinë |
| GET | /api/clients/change_password | Ndrysho passwordin |

