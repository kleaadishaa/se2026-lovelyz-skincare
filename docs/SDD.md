# Software Design Document (SDD)

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

