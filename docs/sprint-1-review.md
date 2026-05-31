# Sprint 1 Review — Lovelyz Skincare

## Informacioni i Sprintit
| Fusha | Detaji |
|---|---|
| Sprint | Sprint 1 |
| Projekti | Lovelyz Skincare E-Commerce |
| Ekipi | 4 anëtarë |
| Mjeti i menaxhimit | Trello |
| Statusi | Completed |

---

## Çfarë u Përfundua në Sprint 1

###  Krijimi i Databazes Fillestare
- Krijimi i tabelave: `users`, `products`, `orders`, `order_details`
- Konfigurimi i MySQL përmes XAMPP
- Lidhja e backend-it me databazën përmes PDO

###  Autentikimi
- Regjistrimi i përdoruesit (`POST /api/register`)
- Login me JWT Token (`POST /api/login`)
- Role Based Access Control — `admin` / `client`
- Rate Limiting kundër sulmeve Brute Force
- Hashing i fjalëkalimeve me bcrypt

###  Frontend
- Ndërtimi i ndërfaqes kryesore (index.html)
- Faqja e login/register
- Faqja e profilit
- Paneli i administratorit (admin.html)
- Faqja e shportës (cart.html)
- Faqja e porosive (orders.html)

---

## Demo Notes
Të gjitha funksionalitetet e Sprint 1 u demonstruan me sukses në ambientin lokal (XAMPP). Databaza u krijua, autentikimi me JWT funksionon dhe frontend-i është i lidhur me backend-in.

---

## Statusi Final
**Të gjitha detyrat e Sprint 1 u plotësuan me sukses. **
