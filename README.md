# 🛒 E-Commerce Platform

Ky është një aplikacion Ueb i kompletuar për menaxhimin e porosive, produkteve dhe përdoruesve, i pajisur me masa sigurie si enkriptimi i fjalëkalimeve dhe kontrolli i limiteve të kërkesave (Rate Limiting).

---

## 👥 Anëtarët e Ekipit
* **Klea Disha** - Team Lead / Backend/ DB Management
* **Etleva Koci** - Auth & Security / Backend
* **Erjola Latifllari** - Backend/ AI Integration
*  **Jurgen Tanushi** - Frontend & Integration

---

## 📝 Përshkrimi i Shkurtër
Projekti konsiston në zhvillimin e një platforme elektronike (E-commerce) e cila mbështet dy role kryesore përdoruesish (Actors): **Klientët (Clients)** dhe **Administratori (Admin)**. 
Sistemi bazohet në arkitekturën Klient-Server, ku ndërfaqja e përdoruesit (Frontend) luan rolin e klientit dhe komunikon me serverin lokal përmes protokollit HTTP (Localhost). Në prapavijë, menaxhimi i porosive, produkteve dhe përdoruesve është zhvilluar me **Pure PHP (REST API)**. 
Sistemi implementon kontrollin e aksesit të bazuar në role (RBAC), ku pas procesit të regjistrimit (Sign-Up) dhe identifikimit (Login), përdoruesit i jepen të drejtat përkatëse. Në aspektin e sigurisë, fjalëkalimet ruhen të enkriptuara (Hashed), komunikimi mbrohet përmes **JWT (JSON Web Tokens)**, dhe implementohet mekanizmi **Rate-Limiting** bazuar në IP për të parandaluar sulmet e tipit Brute-Force dhe DoS në pikat fundore të autentikimit.

---
