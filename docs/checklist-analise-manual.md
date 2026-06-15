# Checklist de analise manual

Este checklist orienta a revisao manual de documentos no MVP do ConfirmaID. Ele complementa o PRD e deve ser usado por operadores administrativos antes de aprovar, reprovar, solicitar correcao ou bloquear uma verificacao.

## Antes de iniciar

- [ ] Confirmar que a verificacao esta em analise.
- [ ] Abrir frente do documento, verso do documento e selfie apenas pela rota administrativa.
- [ ] Conferir se os tres arquivos carregam corretamente.
- [ ] Nao baixar, copiar ou compartilhar arquivos fora do ambiente autorizado.

## Documento

- [ ] Documento e RG ou CNH.
- [ ] Imagem esta legivel.
- [ ] Documento nao parece cortado, adulterado ou coberto.
- [ ] Nome do documento corresponde ao nome informado.
- [ ] CPF do documento corresponde ao CPF informado quando visivel.
- [ ] Data de nascimento corresponde ao cadastro.
- [ ] Usuario tem pelo menos 18 anos.

## Selfie

- [ ] Rosto esta visivel.
- [ ] Imagem nao esta borrada a ponto de impedir comparacao.
- [ ] Pessoa da selfie aparenta ser a mesma do documento.
- [ ] Nao ha sinal obvio de tela fotografada, montagem ou manipulacao.

## Decisao

Use `Aprovar` quando:

- [ ] Dados principais conferem.
- [ ] Documento e selfie sao legiveis.
- [ ] Nao ha indicio relevante de fraude.

Use `Solicitar correcao` quando:

- [ ] Arquivo esta ilegivel ou incompleto.
- [ ] Documento enviado nao permite confirmar todos os dados.
- [ ] Selfie precisa ser reenviada.
- [ ] O problema parece corrigivel pelo usuario.

Use `Reprovar` quando:

- [ ] Dados informados nao correspondem ao documento.
- [ ] Documento nao e aceito pelo MVP.
- [ ] Idade minima nao e atendida.
- [ ] Divergencia impede aprovacao, mas nao ha indicio suficiente para bloqueio.

Use `Bloquear` quando:

- [ ] Ha indicio forte de fraude.
- [ ] Documento aparenta adulteracao.
- [ ] Selfie ou documento claramente nao pertence ao usuario.
- [ ] Ha tentativa recorrente suspeita.

## Registro

- [ ] Toda reprovacao tem motivo claro.
- [ ] Toda solicitacao de correcao tem orientacao objetiva.
- [ ] Todo bloqueio tem justificativa suficiente para auditoria.
- [ ] Nao inserir CPF completo, telefone completo ou paths de arquivo nas notas.
- [ ] Confirmar que a decisao gerou historico na verificacao.
