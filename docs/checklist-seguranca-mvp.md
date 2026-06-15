# Checklist de seguranca do MVP

Este checklist deve ser revisado antes de abrir o ConfirmaID para piloto.

## Controle de acesso

- [ ] Rotas de usuario final exigem autenticacao e perfil `user`.
- [ ] Rotas de parceiro exigem autenticacao, perfil `partner` e parceiro ativo.
- [ ] Rotas administrativas exigem perfil `admin` ou `super_admin`.
- [ ] Arquivos de verificacao so podem ser acessados por usuarios com permissao administrativa.
- [ ] Parceiros nao conseguem acessar documentos, selfies ou paths internos de storage.

## Arquivos e storage

- [ ] `FILESYSTEM_DISK=s3` em producao.
- [ ] `AWS_VISIBILITY=private`.
- [ ] `FILESYSTEM_THROW=true`.
- [ ] Bucket S3/R2/Spaces sem acesso publico.
- [ ] Bloqueio publico do provedor de storage ativado.
- [ ] Upload limitado por `CONFIRMAID_UPLOAD_MAX_FILE_KB`.
- [ ] Tipos aceitos restritos a JPEG, PNG e WebP.
- [ ] Validacao de imagem e MIME real ativa no backend.
- [ ] Nenhum link publico ou temporario e exibido para usuario final ou parceiro.

## Dados sensiveis

- [ ] CPF sempre mascarado fora da area administrativa.
- [ ] Parceiro recebe apenas status, nome, documento mascarado, datas e codigo aprovado.
- [ ] Codigo ConfirmaID so aparece para verificacao aprovada.
- [ ] Logs nao armazenam CPF completo, e-mail completo, telefone completo, caminho privado ou conteudo de arquivos.
- [ ] Campos sensiveis nao sao enviados de volta para a sessao em erros de validacao.

## Auditoria

- [ ] Upload de documentos gera `verification.documents_uploaded`.
- [ ] Aprovacao gera `verification.approved`.
- [ ] Reprovacao gera `verification.rejected`.
- [ ] Solicitacao de correcao gera `verification.correction_requested`.
- [ ] Bloqueio gera `verification.blocked`.
- [ ] Toda consulta de parceiro gera `partner.query`.
- [ ] Tentativa bloqueada de consulta por CPF tambem gera historico.

## Rate limit

- [ ] Login protegido pelo rate limit nativo.
- [ ] API de consulta protegida por `CONFIRMAID_PARTNER_API_RATE_PER_MINUTE`.
- [ ] Consulta web de parceiro protegida por `CONFIRMAID_PARTNER_WEB_RATE_PER_MINUTE`.
- [ ] Limites revisados antes do piloto conforme volume esperado.

## Producao

- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL` com HTTPS.
- [ ] `SESSION_SECURE_COOKIE=true`.
- [ ] Banco PostgreSQL gerenciado ou com backup automatizado.
- [ ] Storage S3 compativel com versionamento, criptografia e backup/replicacao quando disponivel.
- [ ] Fila e scheduler ativos.
- [ ] CI verde antes do deploy.

## Backups

- [ ] Backup diario do PostgreSQL ativo.
- [ ] Retencao minima definida em `BACKUP_DATABASE_RETENTION_DAYS`.
- [ ] Backup/versionamento do bucket de documentos ativo.
- [ ] Retencao minima definida em `BACKUP_STORAGE_RETENTION_DAYS`.
- [ ] Restore de banco testado antes do piloto.
- [ ] Restore de arquivo individual testado antes do piloto.
