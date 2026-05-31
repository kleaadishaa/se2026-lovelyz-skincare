# Test Report
## Skenari 3: Menaxhimi i Shportës (Cart)
 
### TS-01 — Add to Cart (Shtimi i artikullit në shportë)
| Fusha | Detaji |
|---|---|
| Backend | POST /api/cart/add_to_cart.php — INSERT ose UPDATE quantity |
| Frontend | Shfaqet njoftimi "Artikulli u shtua në shportë" |
| Statusi | ✅ Passed |
 
![TS-01](screenshots/ts-01-add-to-cart.png)
 
---
 
### TS-02 — View Cart (Shikimi i shportës)
| Fusha | Detaji |
|---|---|
| Backend | GET /api/cart/get_cart.php — JOIN me tabelën products për imazhin |
| Frontend | Liston artikujt me emër, çmim dhe sasi në UI |
| Statusi | ✅ Passed |
 
![TS-02](screenshots/ts-02-view-cart.png)
 
---
 
### TS-03 — Checkout (Konfirmimi i blerjes)
| Fusha | Detaji |
|---|---|
| Backend | POST /api/cart/checkout.php — krijon porosinë dhe pastron shportën |
| Frontend | Redirect te faqja e konfirmimit pas pagesës |
| Statusi | ✅ Passed |
 
![TS-03](screenshots/ts-03-checkout.png)
 
---
 
### TS-04 — Delete Cart Item (Fshirja e artikullit nga shporta)
| Fusha | Detaji |
|---|---|
| Backend | DELETE /api/cart/delete_cart_item.php?cart_id=X |
| Frontend | Artikulli hiqet nga lista dhe totali përditësohet |
| Statusi | ✅ Passed |
 
![TS-04](screenshots/ts-04-delete-cart-item.png)
 
---
 
## Skenari 4: Menaxhimi i Porosive (Orders)
 
### TS-01 — Create Order (Krijimi i porosisë)
| Fusha | Detaji |
|---|---|
| Backend | POST /api/orders/create_order.php — Transactional INSERT (Orders & Details) |
| Frontend | Redirect te "Success Page" dhe pastrimi i shportës |
| Statusi | ✅ Passed |
 
![TS-05](screenshots/ts-05-create-order.png)
 
---
 
### TS-02 — Get Orders (Shikimi i porosive)
| Fusha | Detaji |
|---|---|
| Backend | GET /api/orders/get_orders.php — kthen të gjitha porositë e përdoruesit |
| Frontend | Liston porositë me status (Pending/Shipped) në UI |
| Statusi | ✅ Passed |
 
![TS-06](screenshots/ts-06-get-orders.png)
 
---
 
### TS-03 — Get Order Details (Detajet e porosisë)
| Fusha | Detaji |
|---|---|
| Backend | GET /api/orders/get_orders_details.php?order_id=X |
| Frontend | Shfaq listën e produkteve të blera dhe çmimet |
| Statusi | ✅ Passed |
 
![TS-07](screenshots/ts-07-order-details.png)
 
---
 
### TS-04 — Update Order Status (Anulimi/Përditësimi)
| Fusha | Detaji |
|---|---|
| Backend | PUT /api/orders/update_order.php — Ndryshon statusin në MySQL |
| Frontend | Butoni "Cancel" bëhet i padisponueshëm nëse statusi është 'Shipped' |
| Statusi | ✅ Passed |
 
![TS-08](screenshots/ts-08-update-order.png)
 
---
 
### TS-05 — Delete Order (Fshirja e porosisë)
| Fusha | Detaji |
|---|---|
| Backend | DELETE /api/orders/delete_order.php — fshin porosinë nga MySQL |
| Frontend | Porosia hiqet nga lista e porosive të përdoruesit |
| Statusi | ✅ Passed |
 
![TS-09](screenshots/ts-09-delete-order.png)

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
