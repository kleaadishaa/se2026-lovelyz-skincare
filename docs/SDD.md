# Software Design Document (SDD)

## 1. Database Schema

### Tabela: users
| Kolona | Tipi | Përshkrimi |
|---|---|---|
| id | INT PRIMARY KEY | ID unik |
| name | VARCHAR | Emri |
| email | VARCHAR UNIQUE | Email |
| password | VARCHAR | Hash i fjalëkalimit |
| role | ENUM('client','admin') | Roli |

### Tabela: products
| Kolona | Tipi | Përshkrimi |
|---|---|---|
| id | INT PRIMARY KEY | ID unik |
| name | VARCHAR | Emri produktit |
| price | DECIMAL | Çmimi |
| image | VARCHAR | Rruga e fotos |

### Tabela: orders
| Kolona | Tipi | Përshkrimi |
|---|---|---|
| id | INT PRIMARY KEY | ID unik |
| user_id | INT FK | Klienti |
| created_at | TIMESTAMP | Data |

### Tabela: order_details
| Kolona | Tipi | Përshkrimi |
|---|---|---|
| id | INT PRIMARY KEY | ID unik |
| order_id | INT FK | Porosia |
| product_id | INT FK | Produkti |
| quantity | INT | Sasia |

## 2. API Endpoints

| Metoda | Endpoint | Përshkrimi |
|---|---|---|
| POST | /api/register | Regjistrim |
| POST | /api/login | Login + JWT |
| GET | /api/products | Lista produkteve |
| POST | /api/upload_product | Shto produkt |
| DELETE | /api/delete_product | Fshi produkt |
| GET | /api/clients/get_user | Merr profilin |
| PUT | /api/clients/update_user | Përditëso profilin |
| DELETE | /api/clients/delete_user | Fshi llogarinë |
| POST | /api/cart/add_to_cart.php | Shto produkt në shportë |
| GET | /api/cart/get_cart.php | Merr artikujt e shportës |
| POST | /api/cart/checkout.php | Krijo porosinë dhe pastro shportën |
| DELETE | /api/cart/delete_cart_item.php | Fshi artikull nga shporta |
| POST | /api/orders/create_order.php | Krijo porosi |
| GET | /api/orders/get_orders.php | Merr të gjitha porositë |
| GET | /api/orders/get_orders_details.php | Merr detajet e porosisë |
| PUT | /api/orders/update_order.php | Përditëso statusin e porosisë |
| DELETE | /api/orders/delete_order.php | Fshi porosinë |