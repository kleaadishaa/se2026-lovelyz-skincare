name: Bug Report
about: Raporto një bug në sistem
title: "bug me jwt token"
labels: bug


Përshkrimi i bug
Pas logout, token-i JWT mbetet i ruajtur në localStorage dhe useri mund të aksesojë faqet e mbrojtura pa u loguar përsëri.

Hapat për të kontrolluar
1. Logohu me email dhe fjalëkalim
2. Shko tek faqja e profilit — funksionon normalisht
3. Kliko "Sign Out"
4. Shtyp butonin "Back" të browserit
5. Faqja e profilit hapet përsëri pa kërkuar login

Rezultati i pritur
Pas logout useri duhet të ridrejtohet tek faqja e loginit dhe të mos ketë akses.

Rezultati aktual
Useri mund të kthehet prapa dhe të shohë faqen e profilit edhe pas logout.

Ambienti
- Browser: Chrome
- OS: Windows


name: Bug Report
about: Raporto një bug në sistem
title: "Session i vjetër shfaqet pas regjistrimit"
labels: bug

Përshkrimi i bug-ut
Kur një user i ri regjistrohet ndërsa një user tjetër është loguar më parë 
në të njëjtin browser, faqja shfaq të dhënat e userit të vjetër dhe jo 
të userit që sapo u regjistrua.

Hapat për të riprodhuar
1. Logohu si user "Eva" në browser
2. Dil (logout)
3. Regjistro një user të ri "Klevi" nga e njëjta browser
4. Pas regjistrimit ridrejtohet tek faqja kryesore
5. Shko tek profili

Rezultati i pritur
Profili duhet të shfaqë të dhënat e userit "Arta" që sapo u regjistrua.

Rezultati aktual
Profili shfaq të dhënat e userit "Eva" — session-i i vjetër nuk pastrohet 
gjatë procesit të regjistrimit.

Shkaku teknik
Funksioni `session_unset()` dhe `session_regenerate_id()` nuk thirren 
para se të krijohet session-i i ri në `signup.inc.php`.

Ambienti
- Browser: Chrome
- OS: Windows
- Server: XAMPP localhost
