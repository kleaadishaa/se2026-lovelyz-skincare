# Test Report

## Skenari 5: Menaxhimi i Profilit dhe Sigurisë së Llogarisë

### TS-01 — Get User (Marrja e të dhënave të profilit)
| Fusha | Detaji |
|---|---|
| Backend | GET /api/clients/get_user me JWT Token — kthen 200 OK |
| Frontend | Ngarkon emrin dhe email-in te faqja "Profili Im" |
| Statusi |  Passed |

![TS-01](screenshots/ts-01-get-user.png)

---

### TS-02 — Update Profile (Përditësimi i të dhënave)
| Fusha | Detaji |
|---|---|
| Backend | PUT /api/clients/update_user — përditëson në MySQL |
| Frontend | Shfaq "Profili u përditësua me sukses" |
| Statusi |  Passed |

![TS-02](screenshots/ts-02-update-profile.png)

---

### TS-03 — Change Password (Ndryshimi i fjalëkalimit)
| Fusha | Detaji |
|---|---|
| Backend | Hash-on password-in e ri përpara ruajtjes në MySQL |
| Frontend | Nxjerr gabim nëse "Konfirmo fjalëkalimin" nuk përputhet |
| Statusi |  Passed |

![TS-03](screenshots/ts-03-change-password.png)

---

### TS-04 — Delete User (Fshirja e llogarisë)
| Fusha | Detaji |
|---|---|
| Backend | DELETE /api/clients/delete_user — kthen 200 OK |
| Frontend | Logout automatik, fshihet JWT nga LocalStorage, redirect te Homepage |
| Statusi |  Passed |

![TS-04](screenshots/ts-04-delete-user.png)
