# CI, qualidade e versionamento continuo

Este projeto usa Docker Compose como ambiente padrao para desenvolvimento local e para o pipeline de CI.

## Pipeline

O workflow `.github/workflows/ci.yml` roda em `push` e `pull_request` para `main` e `develop`.

Etapas executadas:

1. Copia `.env.example` para `.env`.
2. Constroi a imagem Docker da aplicacao.
3. Instala dependencias PHP com Composer dentro do container `app`.
4. Instala dependencias Node com NPM dentro do container `node`.
5. Gera a chave da aplicacao.
6. Valida formatacao PHP com Laravel Pint.
7. Valida Prettier, ESLint e TypeScript.
8. Executa a suite automatizada.
9. Gera o build frontend.

Um pull request so deve ser mesclado quando o check `Quality, Tests and Build` estiver verde.

## Comandos locais

```bash
docker compose exec app vendor/bin/pint --test
docker compose exec node npm run quality
docker compose exec app php artisan test
docker compose exec node npm run build
```

Para corrigir formatacao localmente:

```bash
docker compose exec app vendor/bin/pint
docker compose exec node npm run format
docker compose exec node npm run lint
```

## Padrao de commits

Use commits pequenos e descritivos no formato:

```text
feat: add user verification upload flow
fix: prevent inactive partner queries
docs: update development plan
test: cover partner identity query
chore: configure docker services
```

Tipos recomendados:

- `feat`: nova funcionalidade.
- `fix`: correcao de bug.
- `docs`: documentacao.
- `test`: testes.
- `chore`: infraestrutura, configuracao ou tarefas internas.
- `refactor`: reorganizacao sem mudanca funcional.

## Fluxo de branches

- `main`: branch estavel e protegida.
- `develop`: integracao das proximas entregas.
- `feature/*`: novas funcionalidades.
- `fix/*`: correcoes pontuais.
- `chore/*`: infraestrutura e manutencao.

Exemplos:

```text
feature/docker-foundation
feature/auth-rbac
feature/user-verification-flow
feature/admin-review-panel
feature/partner-query
```

## Regras de merge

Antes de mesclar um PR:

1. O CI deve estar verde.
2. O PR deve estar atualizado com a branch base.
3. O escopo deve estar pequeno e revisavel.
4. Mudancas de comportamento devem ter testes.
5. Mudancas visuais devem respeitar `design-system-confirmaid.md`.
