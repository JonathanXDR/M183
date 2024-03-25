# Auftrag Penetrationtesting

## 1 Rahmenbedingungen und Ziele

### 1.1 Lernziele

Dieser Auftrag sollte Sie befähigen folgende Lernziele zu erreichen:

- Sie können Sicherheitslücken in einer bestehenden Applikation durch den Einsatz von Pentesting-
  Tools und durch Code-Reviews identifizieren.
- Sie können die identifizierten Schwachstellen durch Anpassung der Implementation und / oder
  einführen von Sicherheitsmassnahmen schliessen bzw. die Auswirkungen eines möglichen Vorfalls
  minimieren.
- Sie können eine Applikation systematisch durchtesten und die Tests wie auch die Testergebnisse
  sinnvoll dokumentieren.
- Sie können Findings aus den Testresultaten ableiten und Testresultate auch kritisch hinterfragen.

### 1.2 Rahmenbedingungen

| **Form**          | **Gruppenarbeit**                                     |
| ----------------- | ----------------------------------------------------- |
| **Gruppengrösse** | 2-3 Personen                                          |
| **Abgabetermine** | Werden durch Yves Nussle definiert (und nachgereicht) |

## 2 Ablauf

Jede Gruppe übernimmt im Laufe der Projektarbeit mal die Rolle der Entwickler und mal die Rolle der Tester.

Der Auftrag findet in 3 Phasen statt:

- **Phase 1:** Gegeben ist eine TODO-Listen-Applikation. Diese können Sie unter <https://gitlab.com/ch-tbz-it/Stud/m183/lb2-applikation> beziehen. Die Applikation enthält jedoch einige Sicherheitsmängel.
  Ihre Aufgabe in der Rolle als Entwickler ist es, diese Mängel zu identifizieren und möglichst viele
  davon zu schliessen. Sie sollten zudem die Applikation um eigene Features erweitern (Details im
  Kapitel 2.1 Erweiterung der Applikation).
- **Phase 2:** Die Entwickler übergeben Ihre Applikation den Testern. Jetzt wechseln Sie Ihre Rolle und
  werden von Entwicklern zu Testern. Die Tester (also Sie) analysieren die Applikation auf noch
  vorhandene Schwachstellen und erstellen ein Testprotokoll aus welchem hervorgeht, was getestet
  wurde und welche allfälligen Schwachstellen noch gefunden wurden.
- **Phase 3:** Die Entwickler (wieder Sie - erneuter Rollenwechsel) erhalten das Testprotokoll der Tester
  und müssen Ihrerseits nun nochmals die Applikation überarbeiten und die noch gefundenen
  Schwachstellen fixen (sofern noch welche gefunden wurden). Zudem müssen die Entwickler den
  Testern schriftlich ein Feedback geben, was Sie von dem Findings und dem Testprotokoll halten.

Die Zuteilung welche Gruppe die Applikation welcher anderen Gruppe testen wird, wird durch die Lehrperson
vorgenommen und entsprechend kommuniziert.

### 2.1 Erweiterung der Applikation

Der Umfang für die Erweiterung der Applikation in Phase 1 sollte 4-8 Stunden pro Person nicht übersteigen.
Wichtig: die Behebung der Schwachstellen, die bereits vorhanden sind, ist in dieser Zeitangabe nicht
enthalten. In der Wahl, welche Features Sie erweitern möchten, sind Sie frei. Wählen Sie jedoch etwas (oder auch 2-3 Dinge... je nach persönlichen Interessen), welches keine andere Gruppe hat. Die Lehrperson wird
über alle Gruppen hinweg koordinieren, welche Gruppe welche Features umsetzen wird. Die Features, die
Sie erweitern, sollten etwas mit den drei Schutzzielen (CIA = Confidentiality, Integrity, Availability) zu tun
haben.

Sollten Sie keine eigenen Ideen haben, können Sie sich an folgender Liste orientieren:

- User Registrierung - damit sich neue Benutzer für die TODO-Listen-App registrieren können
- MFA - Mehrfaktorauthentifizierung (zur Erhöhung der Sicherheit beim Login)
- PW-Reset - Self-Service-Funktionalität, wenn das PW vergessen wurde
- Schutz vor Brute-Force-Attacke auf die Login-Maske - beispielsweise blockieren des Logins für eine
  kurze Zeit bei vielen falschen Versuchen od. E-Mail-Info an den Inhaber des Kontos
- Logging der Login-Aktivitäten oder auch eine Log-History beim Editieren der TODOs (Logging &
  Monitoring)
- Löschen eines Benutzerkontos inkl. allfälliger der Person zugewiesenen Tasks (Thema Datenschutz
  und Umsetzung Recht auf Vergessen)
- Einbinden eines Identity-Profiders (beispielsweise Login über Microsoft- od. Google-Account)
- Einsatz einer WAF (web application firewall)
- ...

Die Liste ist nicht abschliessend. Einige der Features sind zudem aufwendiger als andere. Schauen Sie, dass
es für Sie in der Gruppe vom Aufwand her passt, dass Sie aber ein Thema wählen, das Sie auch persönlich
weiterbringt bzw. persönlich interessiert. Gewisse Themen - wenn diese nicht gleich umgesetzt werden -
können auch mehrfach vergeben werden. Beispielsweise wenn Sie Google als Identityprovider einbinden und
eine andere Gruppe Microsoft, dann ist das in Ordnung. Die abschliessende Entscheidung, ob Ihr
gewünschtes Feature in Ordnung ist und auch vom Aufwand her passt, liegt aber bei der Lehrperson.

## 3 Bewertung

Die Bewertung wird Ihnen durch Yves Nussle ab dem 14.03.2024 nachgereicht.
