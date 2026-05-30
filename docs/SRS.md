## 1. Përshkrimi i Projektit
Projekti konsiston në zhvillimin e një platforme elektronike (E-commerce) të quajtur "Lovelyz Skincare". Ky aplikacion u vjen në ndihmë përdoruesve për të shfletuar dhe blerë produkte kozmetike online, si dhe menaxherëve të sistemit për të mbajtur nën kontroll inventarin dhe porositë e klientëve.

## 2. Aktorët e Sistemit (Actors)
Sistemi mbështet dy role kryesore përdoruesish (Actors) me të drejta të ndara:

Klienti (Client): Përdorues i thjeshtë që regjistrohet, identifikohet, shfleton produktet, shton artikuj në shportë dhe kryen porosi.

Administratori (Admin): Përdorues me të drejta të plota që menaxhon produktet (CRUD), kontrollon listën e përdoruesve dhe ndjek statusin e porosive.

## 3. Arkitektura e Sistemit
Sistemi bazohet në arkitekturën Klient-Server (Client-Server Architecture) dhe funksionon si më poshtë:

Frontend (Klienti): Ndërfaqja e përdoruesit e ndërtuar me teknologji web, e cila komunikon me serverin përmes protokollit HTTP (Localhost). Ajo konsumon API-të për të shfaqur të dhënat në mënyrë dinamike dhe menaxhon në mënyrë të sigurt token-at e sesionit.

Backend (Serveri): Logjika e biznesit është zhvilluar me Pure PHP, duke ndërtuar një arkitekturë REST API. Për ambientin lokal është përdorur paketa XAMPP (Web Server Apache). Communication midis Frontend dhe Backend sigurohet përmes JWT (JSON Web Tokens).

Database (Baza e të Dhënave): Si sistem menaxhimi është përdorur MySQL (RDBMS). Modeli përbëhet nga entitetet: Users, Products, Orders dhe Order Details.

Siguria: Autentikimi bazohet në role (RBAC). Fjalëkalimet ruhen me hashing (bcrypt). Pikat fundore (Endpoints) mbrohen nga sulmet Brute-Force përmes mekanizmit Rate-Limiting (tabela rate_limits).

## 4. Diagrami i Skenarëve të Përdorimit (Use Case Diagram)

