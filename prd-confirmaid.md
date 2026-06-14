# PRD — ConfirmaID

## 1. Visão geral do projeto

O **ConfirmaID** é uma plataforma de verificação de identidade digital que permite que uma pessoa valide sua identidade uma única vez e utilize essa verificação em empresas parceiras.

A proposta central é criar uma identidade verificada reutilizável. O usuário realiza o cadastro, envia fotos do documento e uma selfie segurando o documento ao lado do rosto. Após análise e aprovação, ele recebe um código único de verificação, que poderá ser informado em empresas parceiras para comprovar que sua identidade já foi validada.

Para as empresas parceiras, o ConfirmaID reduz a necessidade de coletar e armazenar documentos sensíveis dos clientes. Em vez disso, o parceiro consulta a plataforma para saber se aquele usuário está verificado.

---

## 2. Objetivo do produto

O objetivo do ConfirmaID é oferecer uma forma simples, segura e reutilizável de validar identidades digitais, reduzindo fraudes, retrabalho e fricção em processos de cadastro, contratação e assinatura de serviços.

### Objetivos principais

- Permitir que usuários validem sua identidade por meio de documento e selfie.
- Gerar um código único para cada identidade verificada.
- Permitir que empresas parceiras consultem se um usuário está verificado.
- Reduzir a necessidade de reenvio de documentos para diferentes empresas.
- Proteger dados sensíveis, evitando o compartilhamento desnecessário de documentos.
- Criar uma base confiável de usuários verificados.

---

## 3. Contexto do problema

Muitas empresas ainda validam clientes de forma manual, solicitando documentos por canais pouco seguros, como e-mail, mensagens ou formulários simples.

Esse processo gera problemas como:

- Exposição de documentos pessoais.
- Dificuldade de auditoria.
- Risco de fraude documental.
- Retrabalho para o cliente, que precisa enviar documentos várias vezes.
- Armazenamento desnecessário de dados sensíveis por várias empresas.
- Demora no processo de contratação.
- Falta de padronização na validação de identidade.

O ConfirmaID resolve esse problema centralizando a verificação e permitindo que empresas parceiras consultem apenas o status de identidade verificada, sem precisar receber todos os documentos do usuário.

---

## 4. Público-alvo

### Usuário final

Pessoa física que precisa comprovar sua identidade para contratar serviços, abrir cadastros, assinar contratos ou se relacionar com empresas parceiras.

Exemplos:

- Cliente de provedor de internet.
- Aluno ou responsável financeiro.
- Cliente de empresa de cobrança.
- Cliente de marketplace.
- Cliente de imobiliária.
- Cliente de serviços digitais.
- Cliente de empresas que exigem validação documental.

### Empresa parceira

Empresa que precisa confirmar se uma pessoa é real e se sua identidade foi validada antes de liberar um serviço, contrato ou cadastro.

Exemplos:

- Provedores de internet.
- Instituições de ensino.
- Empresas de cobrança.
- Fintechs.
- SaaS.
- Imobiliárias.
- Clínicas.
- Associações.
- Empresas de assinatura de contrato digital.

### Administrador interno

Equipe responsável por analisar documentos, aprovar ou reprovar verificações, gerenciar parceiros e acompanhar o funcionamento da plataforma.

---

## 5. Escopo do MVP

O MVP deve validar a proposta principal do produto com o menor conjunto possível de funcionalidades.

### O MVP deve permitir

- Cadastro de usuário final.
- Envio de documentos pelo usuário.
- Envio de selfie segurando o documento.
- Análise manual da verificação.
- Aprovação, reprovação ou solicitação de reenvio.
- Geração de código único para usuários aprovados.
- Cadastro de empresas parceiras.
- Consulta de usuários verificados por parceiro.
- Registro de histórico de consultas.
- Controle básico de status da verificação.

### O MVP não precisa ter inicialmente

- Leitura automática de documentos.
- Biometria facial automatizada.
- Prova de vida dinâmica.
- Score antifraude avançado.
- Aplicativo mobile nativo.
- White label para parceiros.
- Análise automática em tempo real.
- Validação automática em bases externas.
- Módulo financeiro completo.
- Marketplace de parceiros.

---

## 6. Jornada do usuário final

### 6.1. Cadastro

O usuário acessa a plataforma e cria uma conta com seus dados básicos.

Dados mínimos:

- Nome completo.
- CPF.
- Data de nascimento.
- E-mail.
- Celular.
- Senha.
- Aceite dos termos de uso.
- Aceite da política de privacidade.

Após o cadastro, o usuário é direcionado para iniciar a verificação de identidade.

---

### 6.2. Escolha do documento

O usuário escolhe o tipo de documento que deseja enviar.

Tipos aceitos no MVP:

- CNH.
- RG.

O sistema deve informar claramente que as imagens precisam estar legíveis, sem cortes, sem reflexo e com boa iluminação.

---

### 6.3. Envio das imagens

O usuário deve enviar três imagens:

1. Foto da frente do documento.
2. Foto do verso do documento.
3. Selfie segurando a frente do documento ao lado do rosto.

O sistema deve permitir visualizar a imagem antes do envio definitivo.

O sistema deve impedir o envio caso algum dos arquivos obrigatórios esteja ausente.

---

### 6.4. Confirmação de envio

Após enviar os documentos, o usuário deve visualizar uma confirmação informando que a verificação foi enviada para análise.

Status exibido:

> Em análise

Mensagem sugerida:

> Recebemos seus documentos. Nossa equipe está analisando sua identidade e você será avisado quando a verificação for concluída.

---

### 6.5. Resultado da análise

O usuário poderá receber um dos seguintes resultados:

- Aprovado.
- Reprovado.
- Correção solicitada.
- Bloqueado.

Quando aprovado, o usuário recebe seu código ConfirmaID.

Exemplo:

```text
CID-492817
```

---

### 6.6. Uso do código

Após a aprovação, o usuário poderá usar seu código em empresas parceiras.

O código deve ser exibido em destaque na área do usuário, com opção de copiar.

A plataforma também poderá permitir que o usuário veja o histórico de empresas que consultaram sua identidade.

---

## 7. Jornada da empresa parceira

### 7.1. Cadastro do parceiro

A empresa parceira deve ser cadastrada na plataforma com seus dados básicos.

Dados mínimos:

- Razão social.
- Nome fantasia.
- CNPJ.
- Nome do responsável.
- E-mail do responsável.
- Telefone.
- Status do parceiro.

O parceiro só deve conseguir consultar usuários após estar ativo.

---

### 7.2. Consulta de identidade

O parceiro deve conseguir consultar se um usuário está verificado.

Formas de consulta previstas para o MVP:

- Código ConfirmaID.
- E-mail.
- CPF.

A consulta por CPF deve ser tratada como mais sensível e pode exigir permissões específicas.

---

### 7.3. Resultado da consulta

O parceiro deve receber apenas as informações necessárias para confirmar a identidade verificada.

Resultado aprovado:

```json
{
  "verified": true,
  "status": "approved",
  "verification_code": "CID-492817",
  "name": "João da Silva",
  "document_masked": "123.***.***-00",
  "verified_at": "2026-06-14",
  "expires_at": "2027-06-14"
}
```

Resultado não verificado:

```json
{
  "verified": false,
  "status": "not_found"
}
```

Resultado em análise:

```json
{
  "verified": false,
  "status": "under_review"
}
```

O parceiro não deve visualizar fotos do documento, selfie ou dados completos desnecessários.

---

### 7.4. Histórico de consultas

Toda consulta realizada por parceiro deve ser registrada.

Informações mínimas:

- Parceiro.
- Tipo de consulta.
- Resultado da consulta.
- Data e hora.
- Identificador do usuário, quando encontrado.
- Origem da consulta.
- Responsável ou credencial utilizada.

---

## 8. Jornada do administrador interno

### 8.1. Painel de verificações

O administrador deve visualizar uma lista de verificações pendentes.

A lista deve conter:

- Nome do usuário.
- CPF mascarado.
- Tipo de documento.
- Status.
- Data de envio.
- Ação para visualizar detalhes.

---

### 8.2. Detalhe da verificação

O administrador deve conseguir visualizar:

- Dados cadastrais do usuário.
- Foto da frente do documento.
- Foto do verso do documento.
- Selfie segurando o documento.
- Status atual.
- Histórico de análises.
- Observações internas.

---

### 8.3. Decisão da análise

O administrador deve poder tomar as seguintes decisões:

- Aprovar.
- Reprovar.
- Solicitar reenvio.
- Bloquear.

Cada decisão deve ser registrada com:

- Usuário analisado.
- Administrador responsável.
- Data e hora.
- Decisão.
- Observação.
- Motivo, quando aplicável.

---

### 8.4. Solicitação de reenvio

Quando a imagem estiver ilegível, incompleta ou inconsistente, o administrador poderá solicitar reenvio.

O sistema deve permitir informar o motivo.

Exemplos de motivos:

- Documento ilegível.
- Foto cortada.
- Selfie sem documento.
- Documento divergente dos dados informados.
- Imagem com reflexo.
- Documento não aceito.
- Suspeita de adulteração.

---

### 8.5. Gestão de parceiros

O administrador deve poder:

- Cadastrar parceiro.
- Ativar parceiro.
- Inativar parceiro.
- Visualizar consultas realizadas.
- Gerenciar permissões de consulta.
- Consultar histórico de uso.

---

## 9. Status do usuário e da verificação

### 9.1. Status da conta do usuário

| Status | Descrição |
|---|---|
| Ativo | Usuário pode acessar normalmente |
| Inativo | Usuário não pode utilizar a plataforma |
| Bloqueado | Usuário bloqueado por suspeita, fraude ou decisão administrativa |

---

### 9.2. Status da verificação

| Status | Descrição |
|---|---|
| Pendente | Usuário ainda não enviou todos os documentos |
| Em análise | Documentos enviados e aguardando validação |
| Aprovado | Identidade validada |
| Reprovado | Verificação negada |
| Correção solicitada | Usuário precisa reenviar informações |
| Expirado | Verificação não é mais válida |
| Bloqueado | Verificação bloqueada por suspeita ou fraude |

---

## 10. Requisitos funcionais

### RF001 — Cadastro de usuário

O sistema deve permitir que uma pessoa física crie uma conta informando seus dados básicos.

Critérios de aceite:

- O sistema deve exigir nome completo, CPF, data de nascimento, e-mail, celular e senha.
- O sistema deve validar campos obrigatórios.
- O sistema deve impedir cadastro duplicado com o mesmo CPF ou e-mail.
- O usuário deve aceitar os termos de uso e política de privacidade.

---

### RF002 — Login de usuário

O sistema deve permitir que usuários cadastrados acessem a plataforma.

Critérios de aceite:

- O usuário deve conseguir acessar usando e-mail e senha.
- O sistema deve impedir acesso com credenciais inválidas.
- O sistema deve exibir mensagem clara em caso de erro.

---

### RF003 — Escolha do tipo de documento

O sistema deve permitir que o usuário selecione o documento que será enviado para validação.

Critérios de aceite:

- O usuário deve poder escolher entre CNH e RG.
- O tipo escolhido deve ficar vinculado à verificação.
- O sistema deve exibir instruções específicas para envio do documento.

---

### RF004 — Envio da frente do documento

O sistema deve permitir que o usuário envie a imagem da frente do documento.

Critérios de aceite:

- O upload deve ser obrigatório.
- O usuário deve conseguir visualizar a imagem antes de confirmar.
- O sistema deve rejeitar arquivos em formato não permitido.
- O sistema deve exibir erro quando o arquivo estiver ausente.

---

### RF005 — Envio do verso do documento

O sistema deve permitir que o usuário envie a imagem do verso do documento.

Critérios de aceite:

- O upload deve ser obrigatório.
- O usuário deve conseguir visualizar a imagem antes de confirmar.
- O sistema deve rejeitar arquivos em formato não permitido.
- O sistema deve exibir erro quando o arquivo estiver ausente.

---

### RF006 — Envio de selfie com documento

O sistema deve permitir que o usuário envie uma selfie segurando a frente do documento ao lado do rosto.

Critérios de aceite:

- O upload deve ser obrigatório.
- O sistema deve orientar o usuário sobre boa iluminação e enquadramento.
- O usuário deve conseguir visualizar a imagem antes de confirmar.
- O sistema deve rejeitar arquivos em formato não permitido.

---

### RF007 — Confirmação de envio para análise

O sistema deve permitir que o usuário confirme o envio da verificação.

Critérios de aceite:

- A verificação só pode ser enviada após todos os arquivos obrigatórios serem adicionados.
- Após o envio, o status deve mudar para “Em análise”.
- O usuário deve visualizar uma mensagem de confirmação.

---

### RF008 — Listagem de verificações pendentes

O sistema deve permitir que administradores visualizem verificações pendentes de análise.

Critérios de aceite:

- A lista deve exibir nome, CPF mascarado, tipo de documento, status e data de envio.
- Deve ser possível filtrar por status.
- Deve ser possível acessar os detalhes da verificação.

---

### RF009 — Visualização detalhada da verificação

O sistema deve permitir que administradores visualizem os dados e imagens enviadas pelo usuário.

Critérios de aceite:

- O administrador deve visualizar dados cadastrais.
- O administrador deve visualizar frente do documento, verso do documento e selfie.
- O administrador deve visualizar histórico de decisões anteriores, quando houver.

---

### RF010 — Aprovação de verificação

O sistema deve permitir que o administrador aprove uma verificação.

Critérios de aceite:

- O status da verificação deve mudar para “Aprovado”.
- O sistema deve gerar um código ConfirmaID único.
- A data da aprovação deve ser registrada.
- O administrador responsável deve ser registrado.
- O usuário deve conseguir visualizar seu código após aprovação.

---

### RF011 — Reprovação de verificação

O sistema deve permitir que o administrador reprove uma verificação.

Critérios de aceite:

- O status da verificação deve mudar para “Reprovado”.
- O administrador deve informar o motivo da reprovação.
- A decisão deve ser registrada no histórico.
- O usuário deve visualizar o motivo de forma clara.

---

### RF012 — Solicitação de reenvio

O sistema deve permitir que o administrador solicite reenvio de imagens ou informações.

Critérios de aceite:

- O status deve mudar para “Correção solicitada”.
- O administrador deve informar o motivo.
- O usuário deve conseguir reenviar os arquivos solicitados.
- O histórico da solicitação deve ser registrado.

---

### RF013 — Bloqueio de usuário ou verificação

O sistema deve permitir bloquear uma verificação ou conta suspeita.

Critérios de aceite:

- O administrador deve informar o motivo do bloqueio.
- O usuário bloqueado não deve conseguir utilizar o código de verificação.
- Consultas de parceiros devem retornar status adequado.
- O bloqueio deve ser registrado em histórico.

---

### RF014 — Exibição do código ConfirmaID

O sistema deve exibir o código ConfirmaID para usuários aprovados.

Critérios de aceite:

- O código deve ser único.
- O código deve ser exibido em destaque.
- O usuário deve conseguir copiar o código.
- O código só deve ser exibido para verificações aprovadas.

---

### RF015 — Cadastro de parceiro

O sistema deve permitir o cadastro de empresas parceiras.

Critérios de aceite:

- O sistema deve exigir razão social, CNPJ, responsável, e-mail e telefone.
- O parceiro deve possuir status.
- Apenas parceiros ativos podem realizar consultas.

---

### RF016 — Consulta de identidade por parceiro

O sistema deve permitir que parceiros consultem a situação de identidade de um usuário.

Critérios de aceite:

- O parceiro deve poder consultar por código ConfirmaID.
- O parceiro pode consultar por e-mail ou CPF, conforme permissão.
- O sistema deve retornar se o usuário está verificado ou não.
- O sistema não deve retornar imagens de documentos ou selfie.

---

### RF017 — Registro de consultas de parceiros

O sistema deve registrar todas as consultas realizadas por parceiros.

Critérios de aceite:

- Toda consulta deve gerar um registro de auditoria.
- O registro deve conter parceiro, data, tipo de consulta e resultado.
- O administrador deve conseguir consultar esse histórico.

---

### RF018 — Painel do parceiro

O sistema deve disponibilizar uma área para parceiros consultarem identidades e acompanharem histórico.

Critérios de aceite:

- O parceiro deve visualizar campo de consulta.
- O parceiro deve visualizar resultado da consulta.
- O parceiro deve visualizar histórico de consultas realizadas.
- O parceiro não deve visualizar informações sensíveis desnecessárias.

---

### RF019 — Painel administrativo

O sistema deve disponibilizar uma área administrativa para operação da plataforma.

Critérios de aceite:

- O administrador deve visualizar verificações pendentes.
- O administrador deve visualizar indicadores básicos.
- O administrador deve gerenciar usuários.
- O administrador deve gerenciar parceiros.
- O administrador deve consultar logs e histórico.

---

### RF020 — Histórico de ações

O sistema deve registrar ações relevantes realizadas na plataforma.

Critérios de aceite:

- Aprovações devem ser registradas.
- Reprovações devem ser registradas.
- Solicitações de reenvio devem ser registradas.
- Bloqueios devem ser registrados.
- Consultas de parceiros devem ser registradas.

---

### RF021 — Mascaramento de dados sensíveis

O sistema deve mascarar dados sensíveis em áreas onde o dado completo não seja necessário.

Critérios de aceite:

- CPF deve ser exibido de forma mascarada para parceiros.
- Dados completos devem ser restritos a perfis autorizados.
- Respostas de consulta devem conter apenas o necessário.

---

### RF022 — Termos e consentimento

O sistema deve registrar o aceite dos termos e da política de privacidade.

Critérios de aceite:

- O usuário deve aceitar os termos antes de enviar documentos.
- O sistema deve registrar data e hora do aceite.
- O sistema deve manter histórico da versão aceita, quando aplicável.

---

## 11. Requisitos não funcionais

### RNF001 — Segurança dos dados

O sistema deve proteger dados pessoais, documentos e imagens contra acesso não autorizado.

Critérios:

- Documentos e selfies não devem ser públicos.
- O acesso a imagens deve ser restrito a perfis autorizados.
- A plataforma deve possuir controle de permissões.
- A plataforma deve registrar acessos e ações sensíveis.

---

### RNF002 — Privacidade e minimização de dados

O sistema deve compartilhar com parceiros apenas os dados necessários para comprovar a verificação.

Critérios:

- Parceiros não devem receber imagens de documentos.
- Parceiros não devem receber dados completos sem necessidade.
- A resposta padrão deve informar apenas o status de verificação e dados mínimos de identificação.

---

### RNF003 — Rastreabilidade

O sistema deve permitir auditoria das principais ações.

Critérios:

- Deve ser possível identificar quem aprovou ou reprovou uma verificação.
- Deve ser possível identificar quando uma consulta foi realizada.
- Deve ser possível rastrear alterações de status.
- Logs sensíveis devem ser preservados pelo período definido pela política da plataforma.

---

### RNF004 — Disponibilidade

O sistema deve estar disponível para usuários e parceiros de forma contínua e confiável.

Critérios:

- O parceiro deve conseguir consultar identidades em tempo adequado.
- Indisponibilidades devem ser minimizadas.
- O sistema deve tratar falhas de forma clara e segura.

---

### RNF005 — Usabilidade

O sistema deve ser simples de usar, principalmente no fluxo de envio de documentos.

Critérios:

- O usuário deve entender claramente cada etapa da verificação.
- O sistema deve orientar sobre qualidade das fotos.
- Mensagens de erro devem ser claras.
- O fluxo deve funcionar bem em dispositivos móveis.

---

### RNF006 — Performance

O sistema deve responder de forma rápida nas principais interações.

Critérios:

- A consulta do parceiro deve retornar em poucos segundos.
- Telas principais devem carregar em tempo aceitável.
- Uploads devem exibir progresso ou feedback claro.
- Listagens administrativas devem permitir filtros para evitar lentidão.

---

### RNF007 — Escalabilidade operacional

O sistema deve permitir crescimento gradual da operação.

Critérios:

- Deve ser possível aumentar o número de usuários verificados.
- Deve ser possível cadastrar múltiplos parceiros.
- Deve ser possível operar um volume crescente de análises.
- O painel administrativo deve facilitar a priorização das análises.

---

### RNF008 — Conformidade com proteção de dados

O sistema deve ser projetado considerando proteção de dados pessoais e sensíveis.

Critérios:

- Deve existir registro de consentimento.
- Deve existir política de retenção de documentos.
- Deve existir controle de acesso.
- Deve existir processo para exclusão ou revisão de dados, conforme política da empresa.
- Deve existir clareza sobre finalidade de uso dos dados.

---

### RNF009 — Responsividade

O sistema deve ser acessível em diferentes tamanhos de tela.

Critérios:

- O fluxo do usuário final deve funcionar bem em celular.
- O painel do parceiro deve funcionar em desktop e notebook.
- O painel administrativo deve priorizar uso em desktop, mas sem quebrar em telas menores.

---

### RNF010 — Manutenibilidade

O sistema deve ser organizado para permitir evolução futura.

Critérios:

- As regras de status devem ser claras.
- As jornadas devem ser bem separadas entre usuário, parceiro e administrador.
- O sistema deve permitir inclusão futura de automações, biometria, prova de vida e validações externas.

---

## 12. Regras de negócio

### RN001 — Código único

Cada usuário aprovado deve possuir um código ConfirmaID único.

---

### RN002 — Código somente após aprovação

O código ConfirmaID só deve ser gerado ou ativado quando a verificação for aprovada.

---

### RN003 — Um CPF por conta

O sistema não deve permitir múltiplas contas ativas com o mesmo CPF.

---

### RN004 — Verificação em análise não é válida

Usuários com status “Em análise” não devem ser considerados verificados.

---

### RN005 — Verificação reprovada não é válida

Usuários com status “Reprovado” não devem ser considerados verificados.

---

### RN006 — Verificação bloqueada não é válida

Usuários com status “Bloqueado” não devem ser considerados verificados.

---

### RN007 — Parceiro inativo não consulta

Empresas parceiras inativas não devem conseguir realizar consultas.

---

### RN008 — Consulta deve gerar histórico

Toda consulta realizada por parceiro deve ser registrada.

---

### RN009 — Parceiro não acessa documentos

Parceiros não devem ter acesso às imagens de documentos ou selfies.

---

### RN010 — Dados sensíveis devem ser mascarados

Dados como CPF devem ser exibidos de forma mascarada quando o dado completo não for necessário.

---

### RN011 — Reenvio mantém histórico

Quando o usuário reenviar documentos, o sistema deve preservar o histórico da tentativa anterior.

---

### RN012 — Decisões administrativas devem ser auditáveis

Toda aprovação, reprovação, bloqueio ou solicitação de reenvio deve registrar responsável, data, hora e motivo, quando aplicável.

---

## 13. Indicadores do MVP

O MVP deve permitir acompanhar indicadores básicos para validar o produto.

### Indicadores de usuário

- Total de usuários cadastrados.
- Total de verificações iniciadas.
- Total de verificações enviadas para análise.
- Total de verificações aprovadas.
- Total de verificações reprovadas.
- Total de verificações com correção solicitada.
- Taxa de abandono antes do envio completo.
- Tempo médio entre envio e análise.

### Indicadores de parceiro

- Total de parceiros cadastrados.
- Total de parceiros ativos.
- Total de consultas realizadas.
- Total de consultas com usuário verificado.
- Total de consultas sem resultado.
- Parceiros com maior volume de consultas.

### Indicadores operacionais

- Verificações pendentes.
- Aprovações por dia.
- Reprovações por dia.
- Tempo médio de análise.
- Motivos mais comuns de reprovação ou reenvio.

---

## 14. Critérios de sucesso do MVP

O MVP será considerado bem-sucedido se:

- Usuários conseguirem completar o fluxo de cadastro e envio de documentos.
- Administradores conseguirem analisar e aprovar verificações manualmente.
- Usuários aprovados receberem um código único.
- Empresas parceiras conseguirem consultar se o usuário está verificado.
- O sistema registrar histórico das consultas.
- O fluxo reduzir a necessidade de o parceiro receber documentos diretamente.
- O produto conseguir ser testado com pelo menos um parceiro real.

---

## 15. Fora de escopo inicial

Os itens abaixo não fazem parte do MVP, mas podem entrar em versões futuras:

- Análise automática de documentos.
- Reconhecimento facial automático.
- Prova de vida com movimento.
- Detecção automática de fraude.
- Consulta automática em bases externas.
- Aplicativo mobile nativo.
- SDK para parceiros.
- Autorização ativa do usuário para cada consulta.
- Webhooks personalizados.
- Cobrança automática por plano ou consulta.
- White label.
- Dashboard avançado de risco.
- Verificação de empresas.
- Validação de documentos internacionais.

---

## 16. Roadmap sugerido

### Fase 1 — MVP operacional

- Cadastro de usuário.
- Upload de documentos.
- Selfie com documento.
- Análise manual.
- Código ConfirmaID.
- Cadastro de parceiros.
- Consulta de identidade.
- Histórico de consultas.

### Fase 2 — Melhorias de experiência e controle

- Melhorias no fluxo mobile.
- Motivos padronizados de reprovação.
- Notificações para usuário.
- Indicadores operacionais.
- Permissões mais detalhadas para parceiros.
- Histórico de empresas que consultaram o usuário.

### Fase 3 — Automação da validação

- Extração automática de dados do documento.
- Validação automática de qualidade da imagem.
- Comparação entre dados digitados e documento.
- Detecção de documentos duplicados.
- Classificação de risco.

### Fase 4 — Identidade reutilizável com autorização

- Usuário autoriza consultas de parceiros.
- Usuário revoga autorizações.
- Histórico completo de compartilhamento.
- Compartilhamento controlado de atributos.
- Selo de identidade verificada.

### Fase 5 — Produto enterprise

- Regras avançadas por parceiro.
- Relatórios por empresa.
- Integrações mais completas.
- Contratos e planos comerciais.
- Gestão avançada de permissões.
- Auditoria avançada.

---

## 17. Premissas

- A validação inicial será manual.
- O usuário final será responsável por enviar imagens legíveis.
- O parceiro usará o ConfirmaID para consultar o status de verificação, não para acessar documentos.
- O produto começará com foco em pessoas físicas.
- O primeiro mercado pode ser empresas que hoje já solicitam documentos manualmente.
- A plataforma deve ser desenhada desde o início com atenção à privacidade e proteção de dados.

---

## 18. Riscos e pontos de atenção

### Risco jurídico e regulatório

O produto lida com dados pessoais e imagens de documentos. É necessário cuidado com consentimento, finalidade, retenção e compartilhamento de dados.

### Risco de fraude manual

Como o MVP começa com análise manual, existe risco de erro humano. É importante criar checklist de análise e histórico de decisões.

### Risco de baixa conversão

O usuário pode abandonar o fluxo se o envio de documentos for difícil. A experiência mobile precisa ser simples.

### Risco de excesso de dados para parceiros

O parceiro deve receber apenas o necessário. Compartilhar documentos ou dados completos pode aumentar risco jurídico e operacional.

### Risco de operação pesada

Se muitos usuários enviarem documentos, a análise manual pode virar gargalo. O painel administrativo deve facilitar priorização e produtividade.

---

## 19. Checklist de análise manual

O administrador deve validar, no mínimo:

- Documento está legível.
- Frente e verso pertencem ao mesmo documento.
- Nome do documento corresponde ao nome cadastrado.
- CPF do documento corresponde ao CPF cadastrado, quando disponível.
- Selfie mostra o rosto do usuário.
- Selfie mostra o documento ao lado do rosto.
- Documento não aparenta corte, montagem ou adulteração.
- Imagem não está escura, borrada ou com reflexo excessivo.
- Tipo do documento enviado corresponde ao tipo selecionado.
- Não há divergência relevante entre os dados informados e o documento.

---

## 20. Resumo final

O ConfirmaID é uma plataforma de identidade verificada reutilizável. O usuário valida sua identidade uma vez, recebe um código único e pode utilizar esse código em empresas parceiras.

Para empresas, o ConfirmaID simplifica a verificação de clientes, reduz o armazenamento de documentos sensíveis e melhora a segurança do processo de contratação.

O MVP deve focar em simplicidade: cadastro, envio de documentos, selfie, análise manual, aprovação, geração de código e consulta por parceiros.
