# DB Engineering Tierpark Thüle

Das Backend befindet sich in dem Ordner `api` und das Frontend in dem Ordner `dashboard`.
Die Livedemonstration findet sich unter der URL [https://xn--dbengineering-tierpark-thle-63c.de/](https://xn--dbengineering-tierpark-thle-63c.de/).

## Verwendete Technologien

Für das Frontend wurde [NextJS](https://nextjs.org/) und [MUI](https://mui.com/) verwendet. Für das Backend wurde PHP mit dem [Slim](https://www.slimframework.com/) Framework verwendet.

## Installation

Damit die Installation funktioniert, muss zuerst die Datenbank erstellt werden. Als zweiten Schritt muss die Anwendung auf einem Apache Server mit PHP übertragen werden.

## Datenbank

In dem neusten [Release](https://github.com/pschroee/dbengineering-tierpark-thuele/releases) befindet sich die Datei `RELEASE.zip`. Darin befindet sich der Ordner `sql-scripts`. Das SQL-Script muss in einer neuen Datenbank importiert werden.

### Webanwendung

Auf dem Apache Webserver muss `mod_rewrite` aktiviert sein, damit die Installation funktioniert. Außerdem muss die Option für den Ordner in der Apache Konfiguration auf `Allow Override All` gesetzt werden.

```
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Für die Anwendung muss die PHP Version größer als 8.0 sein. Außerdem wird der MySQL Driver benötigt.

Für die Installation muss aus dem neusten [Release](https://github.com/pschroee/dbengineering-tierpark-thuele/releases) die Webanwendung entpackt und auf einem Apache Server geladen werden. Alles aus dem Ordner `webapp` muss in dem Root Ordner des Apache Server kopiert werden.

Die Datei `/api/config.php.example` muss in `/api/config.php` umbenannt werden und die Werte dementsprechend geändert werden. Die Datenbankeinstellungen müssen dabei eingetragen werden.
