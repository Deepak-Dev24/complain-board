
# 🚀 AI Calling System – NGINX + PHP-FPM + MySQL

**Final Production Installation Guide**

> **Stack**: NGINX (LEMP) + PHP-FPM + MySQL

---

## 📌 SYSTEM REQUIREMENTS

* Ubuntu 22.04+
* Internet access
* GitHub repository access
* Non-root user with `sudo`

---

## 🔹 PART 1: BASIC SERVER SETUP

### 1️⃣ Update System

```bash
sudo apt update && sudo apt upgrade -y
```

---

### 2️⃣ Install NGINX

```bash
sudo apt install nginx -y
```

Check status:

```bash
sudo systemctl status nginx
```

---

### 3️⃣ Install MySQL

```bash
sudo apt install mysql-server -y
```

Secure MySQL:

```bash
sudo mysql_secure_installation
```

Check status:

```bash
sudo systemctl status mysql
```

---

### 4️⃣ Install PHP + PHP-FPM + Extensions

```bash
sudo apt install -y \
php8.1 \
php8.1-fpm \
php8.1-mysql \
php8.1-curl \
php8.1-zip \
php8.1-mbstring \
php8.1-xml
```

Check PHP-FPM:

```bash
sudo systemctl status php8.1-fpm
```

⚠️ **Do NOT install `libapache2-mod-php`**

---

## 🔹 PART 2: PROJECT CLONING

### 5️⃣ Install Git

```bash
sudo apt install git -y
```

---

### 6️⃣ Clone Project

```bash
cd /var/www
sudo git clone https://github.com/Deepak-Dev24/complain-board.git
```

Fix ownership:

```bash
sudo chown -R ubuntu:ubuntu /var/www/complain-board
```

---

## 🔹 PART 3: NGINX CONFIGURATION (CRITICAL)

### 7️⃣ Create NGINX Site Config

```bash
sudo nano /etc/nginx/sites-available/complain-board
```

Paste **exactly this**:

```nginx
server {
    listen 80;
    server_name sushruteyehospital.in www.sushruteyehospital.in;

    root /var/www/sushruteyehospital;
    index index.php index.html;

    access_log /var/log/nginx/sushruteyehospital.access.log;
    error_log  /var/log/nginx/sushruteyehospital.error.log;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

### 8️⃣ Enable Site

```bash
sudo ln -s /etc/nginx/sites-available/complain-board /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Access:

```
http://<SERVER-IP>
```

---

## 🔹 PART 4: DATABASE SETUP

### 🔟 Create Database & User

```bash
sudo mysql
```

```sql
CREATE DATABASE call_billing;

CREATE USER 'aiuser'@'localhost'
IDENTIFIED BY 'AiUser@123';

GRANT ALL PRIVILEGES ON call_billing.* TO 'aiuser'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

Import schema:

```bash
mysql -u aiuser -p call_billing < /var/www/complain-board/database/call_billing.sql
```

---

## 🔹 PART 5: APPLICATION CONFIG

### 1️⃣2️⃣ Configure PHP DB Connection

```bash
nano /var/www/complain-board/core/db.php
```

```php
$host = "127.0.0.1";
$db   = "call_billing";
$user = "aiuser";
$pass = "AiUser@123";
```

---

### 1️⃣3️⃣ Permissions

```bash
sudo chown -R www-data:www-data /var/www/complain-board
sudo chmod -R 755 /var/www/complain-board
sudo chmod -R 775 /var/www/complain-board/logs
```

---

## 🔹 PART 6: SECURITY RULES

* Only `public/` exposed
* Core logic protected (`core/`, `config/`, `api/`)
* APIs require session authentication
* Rate-limited via NGINX
* No phpMyAdmin installed

---

### 🔹 Clean Database Tables (If Needed)

```sql
USE call_billing;
SHOW TABLES;
TRUNCATE TABLE table_name;
```

---

## 🔹 PART 7: CRON AUTOMATION & WORKER SETUP

### 🔹 STEP 1: Verify CA Certificate

```bash
ls /etc/ssl/certs/ca-certificates.crt
```

If missing:

```bash
sudo apt update
sudo apt install -y ca-certificates
```

---

### 🔹 STEP 2: Add CA Path in PHP Files

Add in **both**:

* `download_recording.php`
* `cdr_provider.php`

```php
$CAFILE = '/etc/ssl/certs/ca-certificates.crt';
```

---

### 🔹 STEP 3: Create Required Directories

```bash
cd /var/www/complain-board
mkdir -p recordings transcripts
```

---

### 🔹 STEP 4: Permissions

```bash
sudo chown -R www-data:www-data recordings transcripts
sudo chmod -R 755 recordings transcripts
```

Verify:

```bash
ls -ld recordings transcripts
```

---

## 🔹 ffmpeg INSTALLATION

Check:

```bash
ffmpeg -version
```

Install if missing:

```bash
sudo apt update
sudo apt install -y ffmpeg
```

---

## 🔹 OPENAI API KEY

Temporary (current shell):

```bash
export OPENAI_API_KEY="sk-proj-PASTE-YOUR-REAL-KEY-HERE"
```

Verify:

```bash
echo $OPENAI_API_KEY
```

---

## 🔹 PYTHON DEPENDENCIES

```bash
pip3 install torch torchvision torchaudio --index-url https://download.pytorch.org/whl/cpu
pip3 install openai-whisper --no-deps
pip3 install tiktoken numpy tqdm regex requests numba
```

Verify Whisper:

```bash
python3 - <<EOF
import whisper
print("Whisper OK (CPU)")
EOF
```

---

## 🔹 WORKER SCRIPT TEMPLATE

```python
import whisper

model = whisper.load_model("base", device="cpu")
result = model.transcribe("recordings/sample.wav")

with open("transcripts/sample.txt", "w") as f:
    f.write(result["text"])
```

---

## 🔹 PYTHON DB CONFIG

`python/db.py`

```python
import mysql.connector

def get_db():
    return mysql.connector.connect(
        host="127.0.0.1",
        user="aiuser",
        password="AiUser@123",
        database="call_billing"
    )
```

---

## 🔹 SINGLE FILE TEST

```bash
python3 python/transcribe.py recordings/sample.wav transcripts/test.txt
python3 python/analyze.py transcripts/test.txt
```
python3 python/worker.py(use when single working will done correctly)
---

## 🔹 CRON JOB

```bash
crontab -e
```

```bash
*/10 * * * * /usr/bin/php /var/www/ai_calling_system/public/api/sync_cdr_to_db.php >> /var/www/ai_calling_system/logs/cron_sync.log 2>&1
```

---

## 🔹 PART 8: FIREWALL

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

---

## 🔹 PART 9: SERVICE VERIFICATION

```bash
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
```

✔ All services must be **active (running)**


