## 1. Përshkrimi i Projektit
Projekti konsiston në zhvillimin e një platforme elektronike (E-commerce) të quajtur "Lovelyz Skincare". Ky aplikacion u vjen në ndihmë përdoruesve për të shfletuar dhe blerë produkte kozmetike online, si dhe menaxherëve të sistemit për të mbajtur nën kontroll inventarin dhe porositë e klientëve.

## 2. Aktorët e Sistemit (Actors)
### Klienti (Client)

* Regjistrohet dhe identifikohet në sistem
* Menaxhon profilin personal
* Shfleton produktet
* Shton produkte në cart
* Kryen porosi
* Merr asistencë nga chatbot

### Administratori (Admin)

* Menaxhon produktet (CRUD)
* Menaxhon përdoruesit
* Monitoron porositë
* Ka akses të autorizuar në endpoint-et administrative


## 3. Arkitektura e Sistemit
Sistemi bazohet në arkitekturën Klient-Server (Client-Server Architecture) dhe funksionon si më poshtë:

Frontend (Klienti): Ndërfaqja e përdoruesit e ndërtuar me teknologji web, e cila komunikon me serverin përmes protokollit HTTP (Localhost). Ajo konsumon API-të për të shfaqur të dhënat në mënyrë dinamike dhe menaxhon në mënyrë të sigurt token-at e sesionit.

Backend (Serveri): Logjika e biznesit është zhvilluar me Pure PHP, duke ndërtuar një arkitekturë REST API. Për ambientin lokal është përdorur paketa XAMPP (Web Server Apache). Communication midis Frontend dhe Backend sigurohet përmes JWT (JSON Web Tokens).

Database (Baza e të Dhënave): Si sistem menaxhimi është përdorur MySQL (RDBMS). Modeli përbëhet nga entitetet: Users, Products, Orders dhe Order Details.

Siguria: Autentikimi bazohet në role (RBAC). Fjalëkalimet ruhen me hashing (bcrypt). Pikat fundore (Endpoints) mbrohen nga sulmet Brute-Force përmes mekanizmit Rate-Limiting (tabela rate_limits).

# 4. Kërkesat e Sistemit

## 4.1 Kërkesat Funksionale (Functional Requirements - FR)

### Menaxhimi i Autentikimit dhe Sigurisë

**FR-AUTH-01 (Regjistrimi i Përdoruesit)**
Sistemi duhet t’i lejojë një përdoruesi të krijojë një llogari të re përmes endpoint-it `/api/register`, duke ruajtur të dhënat në databazë dhe duke gjeneruar JWT Token.

**FR-AUTH-02 (Login i Përdoruesit)**
Sistemi duhet të lejojë autentikimin përmes endpoint-it `/api/login`, duke validuar email dhe password dhe duke kthyer JWT Token bashkë me rolin e përdoruesit (`admin` ose `client`).

**FR-AUTH-03 (Role Based Access Control - RBAC)**
Sistemi duhet të kufizojë aksesin sipas roleve. Vetëm administratori mund të aksesojë endpoint-et administrative dhe panelin e menaxhimit.

**FR-AUTH-04 (Mbrojtja nga Brute Force)**
Sistemi duhet të implementojë Rate Limiting për të kufizuar tentativat e shumta të login-it dhe për të parandaluar sulmet brute-force.

---

### Menaxhimi i Profilit të Përdoruesit

**FR-USER-01 (Leximi i Profilit)**
Sistemi duhet të marrë të dhënat aktuale të klientit të kyçur nga tabela `users` përmes endpoint-it `GET /api/clients/get_user` dhe t’i shfaqë ato në ndërfaqen grafike.

**FR-USER-02 (Përditësimi i Profilit)**
Sistemi duhet t’i lejojë përdoruesit të përditësojë emrin, mbiemrin dhe email-in përmes endpoint-it `PUT/POST /api/clients/update_user`.

**FR-USER-03 (Ndryshimi i Fjalëkalimit)**
Sistemi duhet të verifikojë password-in aktual dhe të lejojë ndryshimin e password-it përmes hashing me bcrypt.

**FR-USER-04 (Fshirja e Llogarisë)**
Sistemi duhet t’i lejojë përdoruesit ose administratorit të fshijë një profil ekzistues përmes endpoint-it `DELETE /api/clients/delete_user`.

---

### Menaxhimi i Produkteve

**FR-PRODUCT-01 (Shfaqja e Produkteve)**
Sistemi duhet të marrë listën e produkteve përmes endpoint-it `GET /api/products` dhe t’i shfaqë ato në frontend me emër, çmim dhe imazh.

**FR-PRODUCT-02 (Shtimi i Produktit)**
Administratori duhet të ketë mundësi të shtojë produkte të reja përmes endpoint-it `POST /api/upload_product`.

**FR-PRODUCT-03 (Përditësimi i Produktit)**
Administratori duhet të ketë mundësi të modifikojë informacionin e produkteve ekzistuese.

**FR-PRODUCT-04 (Fshirja e Produktit)**
Administratori duhet të ketë mundësi të fshijë produkte nga databaza përmes endpoint-it `DELETE /api/delete_product`.

---

### Menaxhimi i Porosive dhe Shportës

**FR-ORDER-01 (Shtimi në Cart)**
Klienti duhet të ketë mundësi të shtojë produkte në shportën virtuale (Cart).

**FR-ORDER-02 (Checkout / Krijimi i Porosisë)**
Sistemi duhet të krijojë një porosi përmes endpoint-it `POST /api/orders`, duke ruajtur të dhënat në tabelat `orders` dhe `order_details`.

**FR-ORDER-03 (Shfaqja e Detajeve të Porosisë)**
Sistemi duhet të marrë detajet e porosive përmes SQL JOIN ndërmjet tabelave përkatëse.

---

### Chatbot dhe Asistenca ndaj Klientit

**FR-CHATBOT-01 (Asistencë për Përdoruesin)**
Sistemi duhet të përfshijë një chatbot për asistencë klienti, i cili ndihmon përdoruesit në navigim dhe pyetje bazë rreth produkteve.

---

## 4.2 Kërkesat Jo-Funksionale (Non-Functional Requirements - NFR)

### Siguria

**NFR-SEC-01 (Validimi në Server)**
Të gjitha të dhënat e dërguara nga frontend-i duhet të validohen në backend (PHP). Input-et e pavlefshme duhet të kthejnë status `400 Bad Request`.

**NFR-SEC-02 (Autorizimi me JWT)**
Identiteti i përdoruesit nuk duhet të merret nga URL-ja, por nga JWT Token i dërguar në HTTP Header.

**NFR-SEC-03 (Hashing i Password-it)**
Të gjitha password-et duhet të ruhen të hash-uara me algoritmin bcrypt.

**NFR-SEC-04 (Prepared Statements / PDO)**
Të gjitha query-t SQL duhet të përdorin Prepared Statements (PDO) për të shmangur SQL Injection.

**NFR-SEC-05 (Rate Limiting)**
Serveri duhet të kufizojë tentativat e login-it dhe të kthejë status `429 Too Many Requests` pas tentativave të shumta të dështuara.

### Performanca

**NFR-PERF-01**
API-t duhet të ofrojnë kohë përgjigjeje të shpejtë për operacionet CRUD.

### Përdorshmëria (Usability)

**NFR-UI-01**
Ndërfaqja duhet të jetë responsive dhe funksionale si në desktop ashtu edhe në mobile.

### Disponueshmëria

**NFR-SYS-01**
Sistemi duhet të funksionojë në ambient lokal përmes Apache/MySQL në XAMPP.



