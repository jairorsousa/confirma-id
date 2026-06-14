# ConfirmaID

Plataforma de verificacao de identidade digital reutilizavel, seguindo o PRD e o Design System mantidos na raiz do projeto.

## Stack atual

- Laravel com React, TypeScript, Inertia.js, Tailwind CSS e shadcn/ui
- PostgreSQL
- Redis
- MinIO para storage S3 local
- Mailpit para e-mails locais
- Docker Compose para toda a infraestrutura

> Observacao: o starter oficial React disponivel no momento instalou Laravel `12.62.0`. A estrutura foi preparada para seguir o plano do projeto e pode ser atualizada para Laravel 13 quando o template/dependencias estiverem alinhados.

## Primeira execucao

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

## Serviços locais

- Aplicacao: http://localhost:8088
- Vite: http://localhost:5173
- Mailpit: http://localhost:8025
- MinIO API: http://localhost:9000
- MinIO Console: http://localhost:9001
- PostgreSQL host: `localhost:15432`

Credenciais locais padrao:

```text
PostgreSQL
DB: confirma_id
User: confirma_id
Password: confirma_id

MinIO
User: confirmaid
Password: confirmaid-secret
Bucket: confirma-id
```

## Comandos úteis

```bash
docker compose up -d
docker compose ps
docker compose logs -f app
docker compose exec app php artisan test
docker compose exec node npm run build
docker compose exec app php artisan migrate
```

## Documentacao do produto

- `prd-confirmaid.md`
- `design-system-confirmaid.md`
- `plano-desenvolvimento-confirmaid.md`
