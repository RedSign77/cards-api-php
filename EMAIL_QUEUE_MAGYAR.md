# Email Küldés Beállítása - Gyors Útmutató

## A Probléma

Az emailek **nem mennek ki automatikusan**, mert:
1. Az értesítések queue-ba (sorba) kerülnek
2. Nincs futó queue worker, ami feldolgozná őket

## Gyors Megoldás

### Fejlesztési Környezetben

Ha `composer dev` parancsot használsz, akkor **már fut egy queue worker**:
```bash
composer dev
```

Ez egyszerre indít:
- Web szervert
- **Queue worker-t** ✓
- Log viewer-t
- Vite-ot

### Ha NEM a composer dev-et használod

Nyiss egy új terminált és futtasd:
```bash
./queue-start.sh
```

Vagy manuálisan:
```bash
php artisan queue:work --tries=3 --verbose
```

**Fontos:** Ez a terminál folyamatosan fut kell hogy maradjon!

## Produkcióban - Supervisor (Ajánlott)

### 1. Supervisor telepítése:
```bash
sudo apt-get update
sudo apt-get install supervisor
```

### 2. Konfiguráció másolása:
```bash
sudo cp .reward/supervisor-queue.conf /etc/supervisor/conf.d/cards-api-queue.conf
```

### 3. Supervisor indítása:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cards-api-queue-worker:*
```

### 4. Állapot ellenőrzése:
```bash
sudo supervisorctl status
```

### 5. Ha változtattál a kódon, újraindítás:
```bash
sudo supervisorctl restart cards-api-queue-worker:*
```

## Cronjob Alternatíva

Ha nem szeretnél Supervisor-t használni, állíts be egy crontab-ot:

```bash
crontab -e
```

Add hozzá ezt a sort:
```
* * * * * cd /home/unreality1/projects/cards-api-php && php artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

Ez **percenként** elindít egy queue worker-t, ami feldolgozza a várakozó job-okat és leáll amikor nincs több.

## Email Tesztelés

### 1. Ellenőrizd, hogy vannak-e várakozó emailek:
```bash
php artisan tinker
```
```php
DB::table('jobs')->count();  // Várakozó job-ok száma
exit
```

### 2. Küldj egy teszt emailt:
```bash
php artisan tinker
```
```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Teszt email a Cards Forge API-tól', function($message) {
    $message->to('signred@gmail.com')->subject('Teszt Email');
});

echo "Email elküldve!\n";
exit
```

### 3. Feldolgozd a queue-t manuálisan:
```bash
php artisan queue:work --once
```

### 4. Ellenőrizd a logokat:
```bash
tail -f storage/logs/laravel.log
```

## Gmail Beállítások Ellenőrzése

A `.env` fájlban:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=signred@gmail.com
MAIL_PASSWORD="SuriLola1977!"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cardsforge.com"
MAIL_FROM_NAME="Cards Forge"
```

**Fontos:**
- A jelszónak Gmail App Password-nek kell lennie (nem a sima jelszó!)
- Generálás: https://myaccount.google.com/apppasswords
- Ehhez kell 2-Factor Authentication a Gmail fiókban

## Gyakori Problémák

### "Connection could not be established with host smtp.gmail.com"
- Ellenőrizd az internet kapcsolatot
- Port 587 nyitva van?
- Próbáld meg port 465-öt SSL-lel: `MAIL_PORT=465` és `MAIL_ENCRYPTION=ssl`

### "Invalid credentials"
- Nem sima jelszó, hanem **App Password** kell!
- https://myaccount.google.com/apppasswords
- 2FA be van kapcsolva a Gmail-ben?

### Az emailek még mindig nem mennek ki
1. Ellenőrizd, hogy fut-e a queue worker:
```bash
ps aux | grep "queue:work"
```

2. Ha nem fut, indítsd el:
```bash
./queue-start.sh
```

3. Vagy használd a `composer dev` parancsot

### Sikertelen job-ok
```bash
# Sikertelen job-ok listája
php artisan queue:failed

# Újrapróbálás
php artisan queue:retry all
```

## Gyors Ellenőrző Lista

- [x] `QUEUE_CONNECTION=database` be van állítva a `.env`-ben
- [ ] Queue worker fut (composer dev VAGY ./queue-start.sh VAGY supervisor)
- [x] Gmail SMTP beállítások helyesek
- [ ] Gmail App Password generálva és beállítva
- [ ] 2FA engedélyezve a Gmail fiókban
- [ ] Teszt email sikeresen elküldve

## Hasznos Parancsok

```bash
# Queue állapot
php artisan queue:monitor database

# Queue worker indítása
php artisan queue:work

# Egy job feldolgozása
php artisan queue:work --once

# Sikertelen job-ok
php artisan queue:failed

# Queue törlése
php artisan queue:clear

# Logok
tail -f storage/logs/laravel.log
```

## Teljes Dokumentáció

Részletes angol nyelvű dokumentáció: `QUEUE_SETUP.md`

## Support

Kérdések esetén: signred@gmail.com
