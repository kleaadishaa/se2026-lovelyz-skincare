name: Bug Report
about: Raporto një bug në sistem
title: "bug"
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
