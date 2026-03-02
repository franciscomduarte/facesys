# Plano de Implantacao — LC Estetica SaaS

## Visao Geral do Sistema

| Item | Valor |
|---|---|
| Framework | Laravel 12.x |
| PHP | >= 8.2 |
| Banco de Dados | PostgreSQL (recomendado) |
| Frontend | Vite 7 + Tailwind CSS 3 + Alpine.js 3 |
| PDF | barryvdh/laravel-dompdf |
| Auditoria | spatie/laravel-activitylog |
| Autenticacao | Laravel Breeze |
| Multi-tenancy | Single DB, empresa_id em todas as tabelas |

---

## 1. Ambientes

### 1.1 Desenvolvimento (Local)

| Config | Valor |
|---|---|
| APP_ENV | local |
| APP_DEBUG | true |
| DB_CONNECTION | pgsql |
| DB_HOST | 127.0.0.1 |
| CACHE_STORE | database |
| SESSION_DRIVER | database |
| QUEUE_CONNECTION | sync |
| MAIL_MAILER | log |
| FILESYSTEM_DISK | local |
| BILLING_GATEWAY | manual |

**Setup:**
```bash
git clone <repo> lcestetica-sys
cd lcestetica-sys
composer install
cp .env.example .env
php artisan key:generate
# Configurar DB_DATABASE, DB_USERNAME, DB_PASSWORD no .env
php artisan migrate --seed
php artisan storage:link
npm install
npm run dev    # Dev server com hot reload
```

**Usuarios de teste (seeder):**
| Email | Senha | Role | Empresa |
|---|---|---|---|
| superadmin@sistema.com | password | super_admin | — |
| admin@lcestetica.com | password | admin | LC Estetica |
| medico@lcestetica.com | password | medico | LC Estetica |
| recepcao@lcestetica.com | password | recepcionista | LC Estetica |
| admin@clinicabeleza.com | password | admin | Clinica Beleza |
| medico@clinicabeleza.com | password | medico | Clinica Beleza |

---

### 1.2 Staging (Homologacao)

| Config | Valor |
|---|---|
| APP_ENV | staging |
| APP_DEBUG | true |
| DB_CONNECTION | pgsql |
| CACHE_STORE | database (ou redis) |
| SESSION_DRIVER | database |
| QUEUE_CONNECTION | database |
| MAIL_MAILER | log ou mailtrap |
| FILESYSTEM_DISK | local |
| BILLING_GATEWAY | manual |

**Objetivo:** Validacao pelo cliente/QA antes de ir para producao. Dados podem ser copia sanitizada da producao ou seed padrao.

---

### 1.3 Producao

| Config | Valor |
|---|---|
| APP_ENV | production |
| APP_DEBUG | **false** |
| APP_URL | https://app.lcestetica.com |
| DB_CONNECTION | pgsql |
| CACHE_STORE | redis (recomendado) ou database |
| SESSION_DRIVER | redis (recomendado) ou database |
| QUEUE_CONNECTION | redis (recomendado) ou database |
| MAIL_MAILER | smtp / ses / resend |
| FILESYSTEM_DISK | s3 (recomendado) ou local |
| BILLING_GATEWAY | manual (futuro: stripe/mercadopago) |
| LOG_CHANNEL | stack |
| LOG_LEVEL | warning |
| BCRYPT_ROUNDS | 12 |
| SESSION_SECURE_COOKIE | true |

---

## 2. Requisitos de Infraestrutura

### 2.1 Servidor de Aplicacao

| Requisito | Minimo | Recomendado |
|---|---|---|
| CPU | 1 vCPU | 2 vCPU |
| RAM | 1 GB | 2 GB |
| Disco | 20 GB SSD | 40 GB SSD |
| SO | Ubuntu 22.04+ / Debian 12+ | Ubuntu 24.04 LTS |

**Extensoes PHP obrigatorias:**
- pdo_pgsql (PostgreSQL)
- mbstring
- openssl
- tokenizer
- ctype
- json
- bcmath
- xml
- curl
- gd ou imagick (para manipulacao de imagens/PDF)
- zip

**Software:**
- PHP 8.2+ com PHP-FPM
- Nginx (recomendado) ou Apache
- Node.js 18+ (apenas para build, nao precisa rodar em producao)
- Composer 2.x
- Supervisor (para queue worker)
- Certbot (SSL Let's Encrypt)

### 2.2 Banco de Dados

| Requisito | Valor |
|---|---|
| Engine | PostgreSQL 15+ |
| Charset | UTF-8 |
| Disco | 10 GB inicial (cresce com uso) |
| Backup | Diario automatizado |

**Tabelas do sistema (25 migrations):**

| Grupo | Tabelas |
|---|---|
| Auth/Core | users, password_reset_tokens, sessions, cache, cache_locks |
| Jobs | jobs, job_batches, failed_jobs |
| Auditoria | activity_log |
| Clinico | patients, treatment_sessions, application_points, fotos_clinicas |
| Procedimentos | procedimentos, procedimento_sessao |
| Prescricoes | prescricoes, prescricao_items |
| Documentos | documentos_assinaveis, assinaturas, template_contratos, contratos |
| Agenda | agendamentos, agendamento_procedimento |
| SaaS | empresas, planos, subscriptions |

### 2.3 Armazenamento de Arquivos

Fotos clinicas sao armazenadas via `FILESYSTEM_DISK`:

| Ambiente | Disco | Detalhes |
|---|---|---|
| Local/Staging | local | storage/app/public/, acessivel via symlink |
| Producao | s3 | AWS S3 ou compativel (DigitalOcean Spaces, MinIO) |

**Variaveis S3 (producao):**
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=sa-east-1
AWS_BUCKET=lcestetica-uploads
AWS_URL=https://lcestetica-uploads.s3.sa-east-1.amazonaws.com
```

### 2.4 Redis (Recomendado para Producao)

```env
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=<senha-forte>
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 3. Variaveis de Ambiente Completas (.env)

### 3.1 Obrigatorias

```env
# --- Aplicacao ---
APP_NAME="LC Estetica"
APP_ENV=production
APP_KEY=                          # Gerado via: php artisan key:generate
APP_DEBUG=false
APP_URL=https://app.lcestetica.com
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR
APP_TIMEZONE=America/Sao_Paulo

# --- Banco de Dados ---
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lcestetica_sys
DB_USERNAME=lcestetica_app
DB_PASSWORD=<senha-forte>

# --- Sessao ---
SESSION_DRIVER=database           # ou redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# --- Cache ---
CACHE_STORE=database              # ou redis

# --- Fila ---
QUEUE_CONNECTION=database         # ou redis

# --- Billing ---
BILLING_GATEWAY=manual
BILLING_TRIAL_DIAS=14
BILLING_GRACIA_DIAS=7
```

### 3.2 Recomendadas

```env
# --- E-mail ---
MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lcestetica.com
MAIL_FROM_NAME="LC Estetica"

# --- Logs ---
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=warning
LOG_DAILY_DAYS=30

# --- Seguranca ---
BCRYPT_ROUNDS=12
```

### 3.3 Opcionais (conforme infra)

```env
# --- Redis ---
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=

# --- S3 ---
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=sa-east-1
AWS_BUCKET=lcestetica-uploads
```

---

## 4. Deploy em Producao — Passo a Passo

### 4.1 Primeiro Deploy

```bash
# 1. Clonar repositorio
git clone <repo> /var/www/lcestetica
cd /var/www/lcestetica

# 2. Instalar dependencias
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# 3. Configurar ambiente
cp .env.example .env
# Editar .env com valores de producao
php artisan key:generate

# 4. Banco de dados
php artisan migrate --force

# 5. Storage
php artisan storage:link

# 6. Otimizacoes
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Permissoes
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 4.2 Deploys Subsequentes

```bash
# 1. Baixar atualizacoes
git pull origin main

# 2. Dependencias
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# 3. Migrations
php artisan migrate --force

# 4. Limpar caches antigos e recriar
php artisan optimize:clear
php artisan optimize

# 5. Reiniciar workers
php artisan queue:restart
sudo supervisorctl restart lcestetica-worker:*
```

### 4.3 Modo Manutencao

```bash
# Ativar (mostra pagina 503 personalizada)
php artisan down --secret="bypass-token-aqui"

# Realizar deploy...

# Desativar
php artisan up
```

---

## 5. Configuracao do Servidor Web (Nginx)

```nginx
server {
    listen 80;
    server_name app.lcestetica.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name app.lcestetica.com;

    root /var/www/lcestetica/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/app.lcestetica.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/app.lcestetica.com/privkey.pem;

    # Seguranca
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Tamanho maximo de upload (fotos clinicas)
    client_max_body_size 20M;

    # Assets estaticos
    location /build/ {
        expires 1y;
        access_log off;
        add_header Cache-Control "public, immutable";
    }

    location /storage/ {
        expires 7d;
        access_log off;
    }

    # Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 60;
    }

    # Bloquear acesso a arquivos sensiveis
    location ~ /\.(?!well-known) {
        deny all;
    }

    location ~ \.(env|log)$ {
        deny all;
    }
}
```

---

## 6. Queue Worker (Supervisor)

**Arquivo:** `/etc/supervisor/conf.d/lcestetica-worker.conf`

```ini
[program:lcestetica-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/lcestetica/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/lcestetica/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start lcestetica-worker:*
```

---

## 7. Backup e Recuperacao

### 7.1 Banco de Dados (Cron diario)

```bash
# /etc/cron.d/lcestetica-backup
0 3 * * * postgres pg_dump -Fc lcestetica_sys > /backups/db/lcestetica_$(date +\%Y\%m\%d).dump
0 4 * * * find /backups/db/ -name "*.dump" -mtime +30 -delete
```

### 7.2 Arquivos de Upload

```bash
# Se usando disco local
0 3 * * * rsync -az /var/www/lcestetica/storage/app/public/ /backups/storage/

# Se usando S3 — backup ja e inerente (replicacao S3)
```

### 7.3 Restauracao

```bash
# Banco
pg_restore -d lcestetica_sys /backups/db/lcestetica_20260302.dump

# Arquivos
rsync -az /backups/storage/ /var/www/lcestetica/storage/app/public/
```

---

## 8. Monitoramento

### 8.1 Health Check

O Laravel 12 expoe o endpoint `/up` por padrao. Configurar monitoramento externo (UptimeRobot, Pingdom, etc.) para:
- `GET https://app.lcestetica.com/up` — esperar HTTP 200

### 8.2 Logs

```bash
# Logs da aplicacao
tail -f /var/www/lcestetica/storage/logs/laravel.log

# Logs do worker
tail -f /var/www/lcestetica/storage/logs/worker.log

# Logs do Nginx
tail -f /var/log/nginx/error.log
```

### 8.3 Metricas Recomendadas

| Metrica | Ferramenta |
|---|---|
| Uptime | UptimeRobot / Pingdom |
| Erros PHP | Sentry / Bugsnag / Laravel Telescope |
| Performance | Laravel Debugbar (staging), New Relic (prod) |
| Disco/CPU/RAM | Netdata / htop / Grafana |
| Queue health | `php artisan queue:monitor` |

---

## 9. Seguranca — Checklist de Producao

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] HTTPS com certificado valido (Let's Encrypt)
- [ ] Headers de seguranca no Nginx (X-Frame-Options, HSTS, etc.)
- [ ] `.env` fora do web root (public/ e o root)
- [ ] Senhas fortes no banco de dados
- [ ] `APP_KEY` gerada e protegida
- [ ] Firewall: apenas portas 80, 443, 22 (SSH restrito)
- [ ] Banco de dados nao exposto externamente
- [ ] Backups diarios testados
- [ ] Log rotation configurado (daily, 30 dias)
- [ ] Supervisor rodando queue workers
- [ ] Rate limiting ativo (429 — pagina personalizada ja existe)
- [ ] Paginas de erro personalizadas (401, 403, 404, 419, 422, 429, 500, 503)

---

## 10. Opcoes de Hospedagem

### 10.1 VPS Tradicional (Recomendado para inicio)

| Provedor | Plano | Preco Estimado |
|---|---|---|
| DigitalOcean | Droplet 2GB | ~US$12/mes |
| Hetzner | CX22 | ~EUR 4/mes |
| Vultr | Cloud 2GB | ~US$12/mes |

Stack: Ubuntu + Nginx + PHP-FPM + PostgreSQL + Redis + Supervisor

### 10.2 PaaS (Deploy simplificado)

| Provedor | Detalhes |
|---|---|
| Laravel Forge | Gerencia servidor na DO/AWS/Hetzner, deploy automatico |
| Laravel Cloud | PaaS nativo do Laravel (mais recente) |
| Render | Container-based, free tier limitado |
| Railway | Container-based, PostgreSQL incluso |

### 10.3 Producao Escalavel (Futuro)

| Servico | Uso |
|---|---|
| AWS ECS / EKS | Containers orquestrados |
| AWS RDS | PostgreSQL gerenciado |
| AWS ElastiCache | Redis gerenciado |
| AWS S3 | Armazenamento de arquivos |
| AWS SES | Envio de e-mails |
| CloudFront | CDN para assets estaticos |

---

## 11. Pipeline CI/CD (Sugestao)

```yaml
# .github/workflows/deploy.yml (exemplo simplificado)
name: Deploy
on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with: { php-version: '8.2' }
      - run: composer install --no-interaction
      - run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: deploy
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/lcestetica
            php artisan down
            git pull origin main
            composer install --optimize-autoloader --no-dev
            npm ci && npm run build
            php artisan migrate --force
            php artisan optimize
            php artisan queue:restart
            php artisan up
```

---

## 12. Resumo de Acoes por Ambiente

| Acao | Dev | Staging | Producao |
|---|---|---|---|
| composer install | --dev | --dev | --no-dev --optimize |
| npm run | dev | build | build |
| migrate | --seed | --force | --force |
| APP_DEBUG | true | true | **false** |
| MAIL_MAILER | log | mailtrap | smtp/ses |
| FILESYSTEM_DISK | local | local | s3 |
| QUEUE_CONNECTION | sync | database | redis |
| CACHE_STORE | database | database | redis |
| SESSION_DRIVER | database | database | redis |
| SSL | nao | opcional | **obrigatorio** |
| Supervisor | nao | opcional | **obrigatorio** |
| Backup | nao | opcional | **obrigatorio** |
| Monitoramento | nao | opcional | **obrigatorio** |
