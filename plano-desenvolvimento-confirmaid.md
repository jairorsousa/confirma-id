# Plano de Desenvolvimento - ConfirmaID

## 1. Objetivo

Implementar o MVP do ConfirmaID seguindo o PRD e o Design System existentes na raiz do projeto:

- `prd-confirmaid.md`
- `design-system-confirmaid.md`
- `logo-confirmaid.png`

O produto deve permitir que usuarios finais cadastrem sua identidade, enviem documentos e selfie, passem por analise manual e recebam um codigo unico ConfirmaID. Empresas parceiras devem consultar apenas o status de verificacao, sem acesso a documentos ou selfies.

Todo o ambiente deve rodar via Docker, incluindo aplicacao, banco de dados, cache, filas, storage local compativel com S3 e ferramentas auxiliares. Todo o desenvolvimento deve ser versionado no GitHub:

```text
git@github.com:jairorsousa/confirma-id.git
```

---

## 2. Stack definida

### Backend

- Laravel 13
- PHP 8.3+
- Laravel Sanctum para autenticacao SPA e tokens de API
- Spatie Laravel Permission para papeis e permissoes
- Spatie Laravel Activitylog para auditoria
- Filament para painel administrativo
- Pest/PHPUnit para testes automatizados

### Frontend

- React
- TypeScript
- Inertia.js
- Tailwind CSS
- shadcn/ui como base de componentes
- Lucide Icons para icones lineares

### Infraestrutura local

- Docker
- Docker Compose
- PostgreSQL
- Redis
- MinIO como storage S3 local
- Mailpit para testes de e-mail
- Nginx ou Caddy como web server local
- Container separado para fila
- Container separado para scheduler

### Deploy futuro

- Docker Compose em VPS, Laravel Forge, Laravel Cloud ou outro provedor compatibilizado com containers.
- Storage S3 compativel em producao: AWS S3, Cloudflare R2, DigitalOcean Spaces ou equivalente.
- PostgreSQL gerenciado em producao, se possivel.

---

## 3. Principios obrigatorios

### Produto

- O MVP deve seguir o `prd-confirmaid.md`.
- A validacao inicial sera manual.
- Parceiros nao podem acessar imagens de documentos ou selfies.
- Consulta de parceiro deve retornar apenas dados minimos e mascarados.
- Toda decisao administrativa deve ser auditavel.
- Toda consulta de parceiro deve gerar historico.

### Design

- Toda interface deve seguir o `design-system-confirmaid.md`.
- Usar a identidade visual do ConfirmaID desde o inicio.
- Usar verde `#22C55E` apenas para acoes principais e status positivo.
- Usar fundo geral `#F8FAFC`.
- Usar texto principal `#0F172A`.
- Usar texto secundario `#64748B`.
- Usar bordas `#E2E8F0`.
- Usar fonte Plus Jakarta Sans.
- Usar icones lineares, preferencialmente Lucide Icons.
- Componentes principais devem respeitar cards, badges, botoes, inputs e stepper definidos no design system.

### Seguranca e privacidade

- Documentos e selfies devem ficar em storage privado.
- Nunca salvar arquivos sensiveis em pasta publica.
- O acesso a imagens deve ser restrito ao admin autorizado.
- Usar policies/gates para controle de acesso.
- CPF deve ser mascarado em telas de parceiro.
- Dados completos devem aparecer apenas para perfis autorizados.
- Registrar consentimento do usuario.
- Registrar logs de acoes sensiveis.

### Versionamento

- Todo codigo, configuracao e documentacao tecnica devem ser versionados.
- Arquivos `.env` reais nao devem ser versionados.
- Usar `.env.example` com variaveis documentadas.
- Usar commits pequenos por etapa.
- Usar branches por funcionalidade.
- Abrir PRs para consolidar fases relevantes.

---

## 4. Estrutura inicial esperada

```text
confirma-id/
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docker/
│   ├── php/
│   ├── nginx/
│   └── postgres/
├── resources/
│   ├── css/
│   ├── js/
│   │   ├── components/
│   │   ├── layouts/
│   │   ├── pages/
│   │   └── types/
│   └── views/
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── auth.php
│   ├── admin.php
│   └── partner.php
├── storage/
├── tests/
├── docker-compose.yml
├── Dockerfile
├── .env.example
├── .gitignore
├── prd-confirmaid.md
├── design-system-confirmaid.md
├── logo-confirmaid.png
└── plano-desenvolvimento-confirmaid.md
```

---

## 5. Etapa 0 - Preparacao do repositorio

### Tarefas

1. Inicializar Git, caso ainda nao exista:

```bash
git init
git branch -M main
git remote add origin git@github.com:jairorsousa/confirma-id.git
```

2. Criar `.gitignore` adequado para Laravel, Node, Docker e arquivos locais:

```text
/vendor
/node_modules
/.env
/.env.*
!/.env.example
/storage/*.key
/storage/app/private/*
/storage/framework/cache/*
/storage/framework/sessions/*
/storage/framework/views/*
/bootstrap/cache/*
.DS_Store
```

3. Fazer commit inicial com os documentos existentes:

```bash
git add .
git commit -m "docs: add project product and design documentation"
```

4. Enviar branch principal:

```bash
git push -u origin main
```

### Entregaveis

- Repositorio Git inicializado.
- Remote `origin` configurado.
- Documentos iniciais versionados.
- Branch `main` publicada.

---

## 6. Etapa 1 - Criacao do projeto Laravel com Docker

### Tarefas

1. Criar aplicacao Laravel 13.
2. Instalar starter kit Laravel com React, TypeScript, Inertia, Tailwind e shadcn/ui.
3. Configurar Docker Compose com servicos:
   - `app`
   - `web`
   - `postgres`
   - `redis`
   - `minio`
   - `mailpit`
   - `queue`
   - `scheduler`
   - `node`, se for separado do container PHP
4. Garantir que tudo rode por Docker:

```bash
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec node npm install
docker compose exec node npm run dev
```

5. Configurar `.env.example` com:
   - `APP_URL`
   - `DB_HOST=postgres`
   - `DB_DATABASE=confirma_id`
   - `DB_USERNAME=confirma_id`
   - `DB_PASSWORD=confirma_id`
   - `REDIS_HOST=redis`
   - `MAIL_HOST=mailpit`
   - `FILESYSTEM_DISK=s3`
   - variaveis do MinIO/S3

### Entregaveis

- Aplicacao Laravel rodando em Docker.
- Banco PostgreSQL rodando em Docker.
- Redis rodando em Docker.
- MinIO rodando em Docker.
- Mailpit rodando em Docker.
- Frontend compilando com Vite.

### Criterios de aceite

- `docker compose up -d` sobe todo o ambiente.
- A aplicacao abre no navegador.
- `php artisan migrate` roda dentro do container.
- Nenhuma dependencia local obrigatoria alem de Docker.

---

## 7. Etapa 2 - Base visual e Design System

### Tarefas

1. Configurar fonte Plus Jakarta Sans.
2. Configurar tokens do design system em Tailwind:
   - `brand.50`
   - `brand.200`
   - `brand.300`
   - `brand.500`
   - `brand.700`
   - `neutral.50`
   - `neutral.200`
   - `neutral.500`
   - `neutral.900`
3. Criar componentes base:
   - `Button`
   - `Input`
   - `Card`
   - `Badge`
   - `StatusBadge`
   - `Stepper`
   - `UploadCard`
   - `EmptyState`
   - `PageHeader`
4. Aplicar logo `logo-confirmaid.png` no layout publico e autenticado.
5. Criar layouts:
   - `PublicLayout`
   - `AuthLayout`
   - `UserLayout`
   - `PartnerLayout`
   - `AdminLayout` via Filament

### Entregaveis

- Tema visual do ConfirmaID aplicado.
- Componentes reutilizaveis alinhados ao design system.
- Landing, login e cadastro usando identidade visual correta.

### Criterios de aceite

- Paleta, radius, badges e botoes seguem `design-system-confirmaid.md`.
- Interface nao usa paleta fora do padrao sem justificativa.
- Componentes sao reutilizados nas telas principais.

---

## 8. Etapa 3 - Autenticacao, perfis e permissoes

### Tarefas

1. Configurar cadastro e login.
2. Criar roles:
   - `user`
   - `partner`
   - `admin`
   - `super_admin`
3. Criar permissions iniciais:
   - `verification.view-own`
   - `verification.submit-own`
   - `verification.review`
   - `verification.approve`
   - `verification.reject`
   - `verification.request-correction`
   - `verification.block`
   - `partner.query`
   - `partner.manage`
   - `audit.view`
4. Configurar redirects por perfil:
   - usuario final para `/app`
   - parceiro para `/partner`
   - admin para `/admin`
5. Configurar seeders de usuarios iniciais.

### Entregaveis

- Login funcional.
- Cadastro funcional.
- Permissoes por papel.
- Admin inicial criado por seeder.

### Criterios de aceite

- Usuario comum nao acessa painel admin.
- Parceiro nao acessa documentos.
- Admin acessa verificacoes pendentes.

---

## 9. Etapa 4 - Modelagem de dados do MVP

### Tarefas

Criar migrations, models, factories e relacionamentos para:

1. `users`
   - dados de autenticacao
   - status da conta
2. `user_profiles`
   - nome completo
   - CPF
   - data de nascimento
   - celular
3. `verifications`
   - usuario
   - tipo de documento
   - status
   - codigo ConfirmaID
   - datas de envio, aprovacao, expiracao
4. `verification_files`
   - verificacao
   - tipo do arquivo: frente, verso, selfie
   - disk
   - path privado
   - mime type
   - tamanho
5. `verification_reviews`
   - verificacao
   - admin responsavel
   - decisao
   - motivo
   - observacao
6. `partners`
   - razao social
   - nome fantasia
   - CNPJ
   - responsavel
   - e-mail
   - telefone
   - status
7. `partner_members`
   - parceiro
   - usuario vinculado
   - papel no parceiro
8. `partner_queries`
   - parceiro
   - usuario encontrado
   - tipo de consulta
   - termo consultado com cuidado de privacidade
   - resultado
   - IP/origem
   - credencial utilizada
9. `consents`
   - usuario
   - tipo
   - versao
   - aceito em
   - IP

### Entregaveis

- Banco modelado.
- Factories para testes.
- Seeders para dados iniciais.

### Criterios de aceite

- CPF e e-mail possuem restricoes de unicidade.
- Codigo ConfirmaID e unico.
- Historico de reenvio e decisoes nao e perdido.

---

## 10. Etapa 5 - Jornada do usuario final

### Tarefas

1. Criar dashboard do usuario em `/app`.
2. Criar fluxo de verificacao com stepper:
   - dados pessoais
   - tipo de documento
   - frente do documento
   - verso do documento
   - selfie com documento
   - confirmacao
3. Validar uploads:
   - imagem obrigatoria
   - tipos permitidos
   - tamanho maximo
   - preview antes do envio
4. Salvar arquivos em storage privado S3/MinIO.
5. Alterar status para `under_review` apos envio completo.
6. Criar tela de status:
   - pendente
   - em analise
   - aprovado
   - reprovado
   - correcao solicitada
   - bloqueado
7. Criar exibicao do codigo `CID-000000` para aprovados.
8. Criar botao de copiar codigo.

### Entregaveis

- Fluxo completo de envio de documentos.
- Status visual do usuario.
- Codigo ConfirmaID exibido somente quando aprovado.

### Criterios de aceite

- Usuario nao envia verificacao incompleta.
- Arquivos nao ficam publicos.
- Usuario aprovado ve e copia o codigo.
- Usuario em analise nao e tratado como verificado.

---

## 11. Etapa 6 - Painel administrativo com Filament

### Tarefas

1. Instalar e configurar Filament.
2. Criar painel `/admin`.
3. Criar resources:
   - usuarios
   - verificacoes
   - parceiros
   - consultas de parceiros
   - logs de atividade
4. Criar tela de detalhe da verificacao:
   - dados cadastrais
   - frente do documento
   - verso do documento
   - selfie
   - status atual
   - historico de decisoes
   - observacoes internas
5. Criar acoes administrativas:
   - aprovar
   - reprovar
   - solicitar reenvio
   - bloquear
6. Gerar codigo ConfirmaID no momento da aprovacao.
7. Registrar toda decisao no historico.
8. Criar widgets:
   - verificacoes pendentes
   - aprovadas hoje
   - reprovadas hoje
   - tempo medio de analise

### Entregaveis

- Painel administrativo operacional.
- Analise manual completa.
- Dashboard operacional basico.

### Criterios de aceite

- Admin aprova e gera codigo unico.
- Admin reprova com motivo obrigatorio.
- Admin solicita reenvio com motivo.
- Admin bloqueia com motivo.
- Toda decisao aparece no historico.

---

## 12. Etapa 7 - Jornada da empresa parceira

### Tarefas

1. Criar painel `/partner`.
2. Criar dashboard do parceiro:
   - consultas realizadas
   - usuarios verificados consultados
   - consultas no mes
   - status/plano atual
3. Criar formulario de consulta:
   - codigo ConfirmaID
   - e-mail
   - CPF, somente se permitido
4. Criar retorno seguro:

```json
{
  "verified": true,
  "status": "approved",
  "verification_code": "CID-492817",
  "name": "Joao da Silva",
  "document_masked": "123.***.***-00",
  "verified_at": "2026-06-14",
  "expires_at": "2027-06-14"
}
```

5. Registrar cada consulta em `partner_queries`.
6. Criar historico de consultas.
7. Criar API para parceiros com Sanctum token:
   - `POST /api/partner/identity-query`
8. Configurar rate limiting por parceiro/token.

### Entregaveis

- Painel de consulta do parceiro.
- API inicial de consulta.
- Historico de consultas.

### Criterios de aceite

- Parceiro inativo nao consulta.
- Parceiro sem permissao nao consulta por CPF.
- Parceiro nao ve documentos, selfie ou CPF completo.
- Toda consulta gera historico.

---

## 13. Etapa 8 - Auditoria, logs e privacidade

### Tarefas

1. Configurar Spatie Activitylog.
2. Registrar eventos:
   - cadastro de usuario
   - aceite de termos
   - envio de documentos
   - aprovacao
   - reprovacao
   - solicitacao de reenvio
   - bloqueio
   - consulta de parceiro
   - alteracao de parceiro
3. Criar helpers de mascaramento:
   - CPF
   - e-mail parcial, quando necessario
   - telefone
4. Criar policies para:
   - verificacoes
   - arquivos de verificacao
   - parceiros
   - consultas
5. Usar URLs temporarias ou responses autenticadas para imagens sensiveis.
6. Documentar politica inicial de retencao de arquivos.

### Entregaveis

- Logs auditaveis.
- Dados sensiveis mascarados.
- Acesso a imagens protegido.

### Criterios de aceite

- Nao existe rota publica para documento/selfie.
- Acoes sensiveis ficam registradas.
- Dados sensiveis nao vazam em tela de parceiro.

---

## 14. Etapa 9 - Notificacoes e mensagens

### Tarefas

1. Configurar notificacoes por e-mail usando Mailpit em dev.
2. Criar notificacoes:
   - verificacao enviada
   - verificacao aprovada
   - verificacao reprovada
   - correcao solicitada
   - conta/verificacao bloqueada
3. Padronizar microcopy seguindo o design system:
   - claro
   - direto
   - profissional
   - sem juridiquês excessivo

### Entregaveis

- Notificacoes transacionais basicas.
- Textos alinhados ao tom de voz do ConfirmaID.

### Criterios de aceite

- Usuario recebe feedback claro sobre cada mudanca de status.
- Mensagens evitam termos tecnicos desnecessarios.

---

## 15. Etapa 10 - Indicadores do MVP

### Tarefas

1. Criar consultas para indicadores:
   - total de usuarios
   - verificacoes iniciadas
   - enviadas para analise
   - aprovadas
   - reprovadas
   - correcao solicitada
   - taxa de abandono
   - tempo medio de analise
2. Criar indicadores de parceiro:
   - parceiros cadastrados
   - parceiros ativos
   - consultas realizadas
   - consultas com usuario verificado
   - consultas sem resultado
3. Criar widgets no Filament.

### Entregaveis

- Dashboard admin com indicadores do MVP.
- Dados operacionais basicos.

### Criterios de aceite

- Admin consegue acompanhar fila e produtividade.
- Indicadores carregam sem lentidao em base pequena/media.

---

## 16. Etapa 11 - Testes automatizados

### Tarefas

1. Configurar Pest ou PHPUnit.
2. Criar testes de feature:
   - cadastro
   - login
   - envio de verificacao
   - aprovacao
   - reprovacao
   - solicitacao de reenvio
   - bloqueio
   - consulta de parceiro
   - mascaramento de CPF
3. Criar testes de authorization:
   - usuario nao acessa admin
   - parceiro nao acessa documentos
   - parceiro inativo nao consulta
   - consulta por CPF exige permissao
4. Criar testes de storage:
   - upload vai para disk privado
   - arquivo obrigatorio ausente falha
5. Criar testes end-to-end para fluxos criticos, se o cronograma permitir.

### Entregaveis

- Suite automatizada cobrindo regras principais.
- Testes rodando dentro do Docker.

### Criterios de aceite

- `docker compose exec app php artisan test` passa.
- Regras de seguranca principais possuem teste.

---

## 17. Etapa 12 - CI, qualidade e versionamento continuo

### Tarefas

1. Criar GitHub Actions:
   - instalar dependencias PHP
   - instalar dependencias Node
   - rodar Pint
   - rodar testes
   - rodar build frontend
2. Configurar Laravel Pint.
3. Configurar TypeScript check.
4. Definir padrao de commits:

```text
feat: add user verification upload flow
fix: prevent inactive partner queries
docs: update development plan
test: cover partner identity query
chore: configure docker services
```

5. Definir fluxo de branches:

```text
main
develop
feature/docker-foundation
feature/auth-rbac
feature/user-verification-flow
feature/admin-review-panel
feature/partner-query
```

### Entregaveis

- Pipeline de CI.
- Padrao de qualidade.
- Fluxo de versionamento definido.

### Criterios de aceite

- PR nao deve ser mesclado se testes ou build falharem.
- Codigo formatado de forma consistente.

---

## 18. Etapa 13 - Hardening antes do MVP

### Tarefas

1. Revisar controle de acesso.
2. Revisar exposicao de arquivos.
3. Revisar mascaramento de dados.
4. Revisar logs sensiveis.
5. Configurar rate limit para login e consultas.
6. Configurar tamanho maximo de upload.
7. Configurar validacao de MIME real.
8. Revisar `.env.example`.
9. Revisar backups do banco e storage para producao.
10. Criar checklist operacional de analise manual baseado no PRD.

### Entregaveis

- Checklist de seguranca do MVP.
- Checklist de operacao manual.
- Ambiente pronto para piloto.

### Criterios de aceite

- Nenhum documento/selfie fica publico.
- Parceiro so recebe dados minimos.
- Aprovacao/reprovacao sempre gera historico.
- Consulta sempre gera historico.

---

## 19. Etapa 14 - Deploy do piloto

### Tarefas

1. Definir ambiente de hospedagem.
2. Configurar variaveis de producao.
3. Configurar banco PostgreSQL de producao.
4. Configurar storage S3 compativel de producao.
5. Configurar fila e scheduler.
6. Configurar HTTPS.
7. Rodar migrations.
8. Criar usuario `super_admin`.
9. Fazer smoke tests:
   - cadastro
   - upload
   - aprovacao
   - consulta de parceiro
   - e-mails
10. Criar tag de release:

```bash
git tag v0.1.0-mvp
git push origin v0.1.0-mvp
```

### Entregaveis

- MVP publicado para piloto.
- Release versionada.
- Smoke test validado.

---

## 20. Ordem recomendada de implementacao

1. Versionar documentos e plano.
2. Criar base Laravel com Docker.
3. Configurar banco, Redis, MinIO e Mailpit.
4. Aplicar design system.
5. Implementar autenticacao e permissoes.
6. Criar modelagem do banco.
7. Implementar fluxo do usuario final.
8. Implementar painel administrativo.
9. Implementar parceiro e consulta.
10. Implementar logs, auditoria e mascaramento.
11. Implementar notificacoes.
12. Criar indicadores.
13. Cobrir regras criticas com testes.
14. Configurar CI.
15. Fazer hardening.
16. Publicar piloto.

---

## 21. Definition of Done do MVP

O MVP sera considerado pronto quando:

- Usuario consegue criar conta.
- Usuario consegue enviar documento frente, verso e selfie.
- Usuario acompanha status da verificacao.
- Admin consegue analisar manualmente.
- Admin consegue aprovar e gerar codigo ConfirmaID.
- Admin consegue reprovar, solicitar reenvio e bloquear.
- Usuario aprovado visualiza e copia o codigo.
- Parceiro ativo consegue consultar identidade.
- Parceiro recebe somente status e dados minimos.
- Toda consulta de parceiro e registrada.
- Toda decisao administrativa e registrada.
- Dados sensiveis sao mascarados onde necessario.
- Documentos e selfies ficam privados.
- Aplicacao roda totalmente via Docker.
- Testes principais passam.
- Codigo esta versionado no GitHub.
- Interface segue `design-system-confirmaid.md`.

---

## 22. Primeiras tarefas praticas

### Sprint 1 - Fundacao

1. Inicializar Git e configurar remote.
2. Criar Laravel 13 com starter kit React/Inertia.
3. Criar Docker Compose completo.
4. Configurar PostgreSQL, Redis, MinIO e Mailpit.
5. Criar `.env.example`.
6. Fazer primeiro commit tecnico.

### Sprint 2 - Identidade visual e auth

1. Aplicar tokens do design system.
2. Criar componentes base.
3. Configurar login, cadastro e layouts.
4. Criar roles e permissions.
5. Criar seeders iniciais.

### Sprint 3 - Verificacao do usuario

1. Criar migrations do dominio.
2. Criar dashboard do usuario.
3. Criar stepper de verificacao.
4. Implementar uploads privados.
5. Criar status em analise.

### Sprint 4 - Operacao admin

1. Instalar Filament.
2. Criar resource de verificacoes.
3. Criar visualizacao de arquivos privados.
4. Criar acoes de aprovacao, reprovacao, reenvio e bloqueio.
5. Criar historico de decisoes.

### Sprint 5 - Parceiros

1. Criar cadastro de parceiros.
2. Criar painel do parceiro.
3. Criar consulta por codigo, e-mail e CPF autorizado.
4. Criar historico de consultas.
5. Criar API com Sanctum.

### Sprint 6 - Qualidade e piloto

1. Criar testes criticos.
2. Configurar CI.
3. Revisar seguranca.
4. Revisar design e responsividade.
5. Fazer deploy piloto.
6. Criar tag `v0.1.0-mvp`.
