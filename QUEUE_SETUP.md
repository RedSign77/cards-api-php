# Queue és Email Küldés Beállítása

Az emailek küldése queue-n keresztül történik. Ez azt jelenti, hogy az emailek nem azonnal kerülnek kiküldésre, hanem egy queue-ba (sor) kerülnek, és egy háttérfolyamat dolgozza fel őket.

## Jelenlegi Konfiguráció

### Queue Driver
A `.env` fájlban a queue driver nincs explicit beállítva, ezért az alapértelmezett `database` driver-t használja:
```env
QUEUE_CONNECTION=database
```

Ez azt jelenti, hogy a queue job-ok a `jobs` táblában kerülnek tárolásra.

### Email Értesítések
Az alábbi értesítések queue-ba kerülnek (ShouldQueue implementálják):
- `NewUserRegistered` - Új felhasználó regisztráció
- `UserEmailConfirmed` - Email cím megerősítés
- `NewGameAdded` - Új játék hozzáadása

## Probléma és Megoldás

### Miért nem mennek ki az emailek?

Az emailek a queue-ban várakoznak, de **nincs futó queue worker** ami feldolgozná őket.

### Fejlesztői Környezet (composer dev)

A `composer dev` parancs már tartalmaz egy queue worker-t:
```bash
composer dev
```

Ez egyidejűleg indít:
- Laravel development server
- **Queue worker** (`php artisan queue:listen --tries=1`)
- Log viewer (pail)
- Vite dev server

### Produkcióban / Rewardenv

Ha nem a `composer dev`-et használod, akkor manuálisan kell indítani a queue worker-t:

```bash
php artisan queue:work --tries=3 --timeout=90
```

## Cronjob Beállítása

### 1. Laravel Scheduler (Ajánlott)

A Laravel scheduler-t használva automatikusan futtathatod a queue worker-t és más feladatokat.

#### Crontab beállítás:
```bash
* * * * * cd /home/unreality1/projects/cards-api-php && php artisan schedule:run >> /dev/null 2>&1
```

#### Scheduler konfigurálása (app/Console/Kernel.php):
```php
protected function schedule(Schedule $schedule): void
{
    // Queue worker ellenőrzése minden 5 percben
    $schedule->command('queue:work --stop-when-empty --tries=3')
        ->everyFiveMinutes()
        ->withoutOverlapping();

    // Sikertelen job-ok újrapróbálása naponta
    $schedule->command('queue:retry all')
        ->daily();

    // Régi sikertelen job-ok törlése hetente
    $schedule->command('queue:flush')
        ->weekly();
}
```

### 2. Supervisor (Ajánlott Produkcióhoz)

A Supervisor egy process manager, ami biztosítja, hogy a queue worker mindig fusson.

#### Telepítés:
```bash
sudo apt-get install supervisor
```

#### Konfiguráció (/etc/supervisor/conf.d/cards-api-queue.conf):
```ini
[program:cards-api-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/unreality1/projects/cards-api-php/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=unreality1
numprocs=2
redirect_stderr=true
stdout_logfile=/home/unreality1/projects/cards-api-php/storage/logs/queue-worker.log
stopwaitsecs=3600
```

#### Supervisor parancsok:
```bash
# Konfiguráció újratöltése
sudo supervisorctl reread
sudo supervisorctl update

# Worker indítása
sudo supervisorctl start cards-api-queue-worker:*

# Worker állapot ellenőrzése
sudo supervisorctl status

# Worker újraindítása
sudo supervisorctl restart cards-api-queue-worker:*

# Worker leállítása
sudo supervisorctl stop cards-api-queue-worker:*
```

### 3. Systemd Service (Alternatíva)

#### Service fájl (/etc/systemd/system/cards-api-queue.service):
```ini
[Unit]
Description=Cards API Queue Worker
After=network.target

[Service]
Type=simple
User=unreality1
WorkingDirectory=/home/unreality1/projects/cards-api-php
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=10
StandardOutput=append:/home/unreality1/projects/cards-api-php/storage/logs/queue-worker.log
StandardError=append:/home/unreality1/projects/cards-api-php/storage/logs/queue-worker.log

[Install]
WantedBy=multi-user.target
```

#### Systemd parancsok:
```bash
# Service engedélyezése
sudo systemctl enable cards-api-queue.service

# Service indítása
sudo systemctl start cards-api-queue.service

# Állapot ellenőrzése
sudo systemctl status cards-api-queue.service

# Service újraindítása
sudo systemctl restart cards-api-queue.service

# Logok megtekintése
sudo journalctl -u cards-api-queue.service -f
```

## Rewardenv Specifikus

Ha Rewardenv-et használsz, a queue worker-t a Docker konténerben kell futtatni:

```bash
# Queue worker futtatása a konténerben
reward shell
php artisan queue:work --tries=3

# Vagy háttérben (detached)
reward shell -c "php artisan queue:work --daemon --tries=3" &
```

## Email Küldés Tesztelése

### 1. Queue tartalmának ellenőrzése:
```bash
php artisan queue:monitor database
```

### 2. Kézi queue feldolgozás:
```bash
php artisan queue:work --once
```

### 3. Test email küldése:
```bash
php artisan tinker
```
```php
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Notification;

$user = User::first();
Notification::route('mail', 'signred@gmail.com')->notify(new NewUserRegistered($user));
```

### 4. Gmail SMTP tesztelése:
```bash
php artisan tinker
```
```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Test email from Cards Forge API', function($message) {
    $message->to('signred@gmail.com')
            ->subject('Test Email');
});

echo "Email sent!\n";
```

## Gyakori Problémák és Megoldások

### 1. Emailek a queue-ban maradnak
**Probléma:** Nincs futó queue worker
**Megoldás:** Indítsd el a queue worker-t: `php artisan queue:work`

### 2. Gmail authentication failed
**Probléma:** Hibás Gmail App Password vagy 2FA nincs engedélyezve
**Megoldás:**
- Engedélyezd a 2-Factor Authentication-t
- Generálj új App Password-öt: https://myaccount.google.com/apppasswords
- Frissítsd a `MAIL_PASSWORD`-öt a `.env` fájlban

### 3. Queue worker crash-el
**Probléma:** Memory limit vagy timeout
**Megoldás:**
```bash
php artisan queue:work --timeout=90 --memory=256 --tries=3
```

### 4. Failed jobs halmozódnak
**Probléma:** Nem megfelelő email konfiguráció
**Megoldás:**
```bash
# Sikertelen job-ok megtekintése
php artisan queue:failed

# Egy adott job újrapróbálása
php artisan queue:retry {job_id}

# Összes sikertelen job újrapróbálása
php artisan queue:retry all

# Sikertelen job-ok törlése
php artisan queue:flush
```

### 5. Queue worker nem dolgoz háttérben
**Probléma:** A queue worker csak akkor fut, amikor manuálisan indítod
**Megoldás:** Használj Supervisor-t vagy systemd-t (lásd fent)

## Queue Parancsok Összefoglalója

```bash
# Queue worker indítása
php artisan queue:work

# Queue worker indítása specifikus connection-nel
php artisan queue:work database

# Egy job feldolgozása és leállás
php artisan queue:work --once

# Queue worker leállítása az aktuális job után
php artisan queue:work --stop-when-empty

# Queue tartalmának törlése
php artisan queue:clear

# Sikertelen job-ok listázása
php artisan queue:failed

# Összes sikertelen job újrapróbálása
php artisan queue:retry all

# Queue monitorozása
php artisan queue:monitor database

# Queue metrikák
php artisan queue:work --verbose
```

## Ajánlott Konfiguráció Fejlesztéshez

`.env` fájl:
```env
QUEUE_CONNECTION=database
```

Használd a `composer dev` parancsot, ami automatikusan elindítja a queue worker-t.

## Ajánlott Konfiguráció Produkcióhoz

`.env` fájl:
```env
QUEUE_CONNECTION=database
```

Használj **Supervisor**-t a queue worker folyamatos futtatásához (lásd fent).

## Azonnali Email Küldés (Sync Driver)

Ha nem akarsz queue-t használni és azonnal ki akarod küldeni az emaileket:

`.env` fájl:
```env
QUEUE_CONNECTION=sync
```

**Vigyázat:** Ez blokkolja a request-et amíg az email nem kerül kiküldésre, ami lassíthatja az alkalmazást.

## Ellenőrzőlista

- [ ] QUEUE_CONNECTION be van állítva (database ajánlott)
- [ ] MAIL_* környezeti változók helyesen be vannak állítva
- [ ] Gmail App Password generálva és beállítva
- [ ] Queue worker fut (composer dev VAGY supervisor VAGY systemd)
- [ ] jobs tábla létezik az adatbázisban
- [ ] failed_jobs tábla létezik az adatbázisban
- [ ] storage/logs írható
- [ ] Queue worker monitorozva van (supervisor ajánlott)

## Support

Ha továbbra is problémák vannak az email küldéssel, ellenőrizd:
1. `storage/logs/laravel.log` - Laravel hibák
2. `storage/logs/queue-worker.log` - Queue worker logok
3. Gmail beállítások - https://myaccount.google.com/security

Kapcsolat: signred@gmail.com
