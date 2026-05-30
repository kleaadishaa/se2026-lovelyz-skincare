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
| POST | /api/orders | Krijo porosi |
| GET | /api/clients/get_user | Merr profilin |
| PUT | /api/clients/update_user | Përditëso profilin |
| DELETE | /api/clients/delete_user | Fshi llogarinë |
