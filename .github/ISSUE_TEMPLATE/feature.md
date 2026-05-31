---
name: Feature Request
about: Vecori per login
title: " feature "
labels: enhancement


Përshkrimi
Shto funksionin "Më mbaj të loguar" (Remember Me) në faqen e loginit, që token-i JWT të qëndrojë aktiv për 30 ditë nëse useri e zgjedh këtë opsion.

Arsyeja
Aktualisht token-i skadon shpejt dhe useri duhet të logohet çdo herë. Kjo është e papërshtatshme për përdorues të rregullt.

Detaje teknike
- Shto checkbox "Më mbaj të loguar" në formën e loginit
- Nëse zgjidhet → token JWT me `exp` 30 ditë
- Nëse nuk zgjidhet → token JWT me `exp` standard (1 orë)
- Ruaj token në `localStorage` me flag të veçantë

Kriteret e pranimit
      Checkbox shfaqet në faqen e loginit
      Token me 30 ditë krijohet kur zgjidhet opsioni
      Token standard kur nuk zgjidhet
      Logout fshin token-in në të dyja rastet
