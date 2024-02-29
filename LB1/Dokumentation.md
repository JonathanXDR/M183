# LB1: OWASP Top Ten Project - A09:2021 Security Logging and Monitoring Failures

## Überblick

In dieser Gruppenarbeit befassen wir uns mit dem Thema "A09:2021 – Security Logging and Monitoring Failures" aus den OWASP Top Ten 2021. Dieser Punkt fokussiert sich auf die Schwachstellen, die entstehen, wenn unzureichendes Logging und Monitoring von Sicherheitsereignissen implementiert werden. Diese Lücken ermöglichen es Angreifern oft, Angriffe durchzuführen, ohne entdeckt zu werden.

## Erläuterungen zu Aufgabe 3 und 4

### CWE & OWASP Top 10 Risks Zusammenhang

CWE steht für "Common Weakness Enumeration" und ist eine Liste von Software- und Hardware-Schwachstellen. CWE hilft bei der Identifikation und Klassifikation von Sicherheitslücken. Die OWASP Top Ten sind eng mit CWE verknüpft, da sie auf CWE-Einträgen basieren und die kritischsten Webanwendungssicherheitsrisiken hervorheben.

### Unterschied zwischen OWASP Top 10 Risks und OWASP Proactive Controls

Die "OWASP Top 10 Risks" listen die zehn kritischsten Sicherheitsrisiken für Webanwendungen auf, basierend auf ihrer Verbreitung und ihren potenziellen Auswirkungen. "OWASP Proactive Controls" hingegen sind eine Reihe von Sicherheitsmassnahmen, die Entwickler während des Softwareentwicklungsprozesses ergreifen sollten, um Sicherheitsrisiken proaktiv zu vermeiden.

## Theoretische Hintergründe

### Beschreibung der Bedrohung

Security Logging und Monitoring sind essentielle Bestandteile einer robusten Sicherheitsstrategie. Sie ermöglichen die Erkennung, Untersuchung und Reaktion auf Sicherheitsvorfälle. Ein Mangel daran führt dazu, dass Angriffe unbemerkt bleiben, Beweismittel nicht gesichert werden und somit die Möglichkeit zur Nachverfolgung und Analyse von Angriffen verloren geht. Die Folgen können Datenverlust, Systemkompromittierung und rechtliche sowie finanzielle Nachteile sein.

## Schwachstelle mit Codebeispiel

Ein typisches Beispiel für eine Schwachstelle in diesem Bereich ist das Fehlen von angemessenen Logging-Mechanismen für fehlgeschlagene Anmeldeversuche. Ohne diese Logs ist es unmöglich zu erkennen, ob ein Angreifer versucht, ein Passwort durch Brute-Force-Methoden zu erraten.

```javascript
// Unzureichendes Logging bei fehlgeschlagenen Anmeldeversuchen
app.post('/login', (req, res) => {
  const { username, password } = req.body;
  if (!authenticate(username, password)) {
    // Schwachstelle: Fehlgeschlagener Anmeldeversuch wird nicht geloggt
    res.status(401).send('Login failed');
  } else {
    // Erfolgreiche Anmeldung
    res.status(200).send('Login successful');
  }
});
```

## Massnahme mit Codebeispiel

Um die Schwachstelle zu beheben, sollte das System jeden fehlgeschlagenen Anmeldeversuch, einschliesslich Zeitstempel und IP-Adresse des Benutzers, loggen. Dies ermöglicht die Identifikation und Analyse verdächtiger Aktivitäten.

```javascript
// Verbessertes Logging für fehlgeschlagene Anmeldeversuche
app.post('/login', (req, res) => {
  const { username, password } = req.body;
  if (!authenticate(username, password)) {
    // Verbesserung: Fehlgeschlagener Anmeldeversuch wird geloggt
    console.error(
      `Failed login attempt for ${username} from ${
        req.ip
      } at ${new Date().toISOString()}`
    );
    res.status(401).send('Login failed');
  } else {
    // Erfolgreiche Anmeldung
    res.status(200).send('Login successful');
  }
});
```

## Resultate & Erkenntnisse

Durch die Implementierung von effektivem Logging und Monitoring können Sicherheitsvorfälle schneller erkannt und behoben werden. Dies erhöht nicht nur die Sicherheit der Anwendung, sondern unterstützt auch die Einhaltung von Compliance-Anforderungen.

## Hinweise auf weitere Unterlagen & Übungen

- OWASP Top Ten 2021: <https://owasp.org/Top10/>
- CWE - Common Weakness Enumeration: <https://cwe.mitre.org/>
- OWASP Proactive Controls: <https://owasp.orgwww-project-proactive-controls/>
