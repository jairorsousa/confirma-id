# Politica inicial de retencao de arquivos

Esta politica orienta o MVP do ConfirmaID para armazenamento de documentos e selfies enviados na verificacao de identidade.

## Arquivos sensiveis

- Frente do documento, verso do documento e selfie devem permanecer em disk privado.
- Nao deve existir rota publica para acesso direto a documentos ou selfies.
- Visualizacao operacional deve acontecer por rota autenticada e autorizada.
- URLs temporarias, quando usadas, devem ter validade curta e nunca ser persistidas em banco ou logs.

## Retencao

- Arquivos de verificacoes aprovadas ficam retidos enquanto a verificacao estiver valida.
- Arquivos de verificacoes reprovadas, bloqueadas ou substituidas por nova tentativa devem ser avaliados para remocao apos 180 dias.
- Logs de auditoria nao devem armazenar caminho privado completo, CPF completo, e-mail completo, telefone completo ou conteudo dos arquivos.
- Antes de producao, a regra de retencao deve ser revisada com requisitos legais e politicas de privacidade aplicaveis.

## Exclusao futura

- A exclusao fisica deve ser feita por job agendado.
- O job deve registrar somente identificadores internos, status e contadores de arquivos removidos.
- A exclusao deve preservar o historico de decisoes e consultas sem reter imagens sensiveis alem do necessario.
