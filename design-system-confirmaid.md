# Design System — ConfirmaID

## 1. Conceito visual

O **ConfirmaID** deve transmitir:

- Confiança
- Segurança
- Simplicidade
- Tecnologia
- Credibilidade

A identidade precisa parecer moderna o suficiente para uma startup SaaS, mas também séria o bastante para lidar com documentos, identidade e dados sensíveis.

O visual deve seguir estes princípios:

- Interface limpa.
- Muito espaço em branco.
- Verde como cor de confirmação e segurança.
- Bordas arredondadas.
- Ícones lineares.
- Cards suaves.
- Pouco ruído visual.
- Linguagem clara e objetiva.

---

## 2. Paleta de cores

### Cores principais

| Nome | Hex | Uso |
|---|---:|---|
| Primary Green | `#22C55E` | Botões principais, check, status aprovado |
| Deep Green | `#166534` | Textos fortes em verde, títulos institucionais |
| Mint Green | `#86EFAC` | Destaques leves, fundos de sucesso, ilustrações |
| Soft Background Green | `#F0FDF4` | Backgrounds suaves, cards informativos |
| Neutral Dark | `#0F172A` | Títulos e textos principais |
| Neutral Gray | `#64748B` | Textos secundários, descrições |

### Cores auxiliares recomendadas

| Nome | Hex | Uso |
|---|---:|---|
| White | `#FFFFFF` | Fundo principal |
| Border Light | `#E2E8F0` | Bordas de inputs e cards |
| Background Light | `#F8FAFC` | Fundo geral da aplicação |
| Success Light | `#DCFCE7` | Alertas positivos |
| Warning | `#F59E0B` | Status em análise ou pendente |
| Warning Light | `#FEF3C7` | Fundo de aviso |
| Danger | `#EF4444` | Erros, reprovação |
| Danger Light | `#FEE2E2` | Fundo de erro |
| Info | `#3B82F6` | Informações neutras |

---

## 3. Uso das cores por status

| Status | Cor principal | Fundo |
|---|---:|---:|
| Verificado | `#22C55E` | `#DCFCE7` |
| Em análise | `#F59E0B` | `#FEF3C7` |
| Pendente | `#64748B` | `#F1F5F9` |
| Correção solicitada | `#F97316` | `#FFEDD5` |
| Reprovado | `#EF4444` | `#FEE2E2` |
| Bloqueado | `#0F172A` | `#E2E8F0` |

---

## 4. Tipografia

### Fonte principal

**Plus Jakarta Sans**

É uma fonte moderna, limpa, tecnológica e muito boa para produtos SaaS.

### Hierarquia

| Elemento | Peso | Tamanho sugerido |
|---|---:|---:|
| Display / Hero | 700 | 48px |
| H1 | 700 | 36px |
| H2 | 700 | 28px |
| H3 | 600 | 22px |
| H4 | 600 | 18px |
| Texto padrão | 400 | 16px |
| Texto secundário | 400 | 14px |
| Label | 500 | 14px |
| Caption | 400 | 12px |
| Botão | 600 | 14px / 16px |

### Exemplo de uso

```css
font-family: "Plus Jakarta Sans", sans-serif;
```

Títulos devem usar `#0F172A`.

Textos secundários devem usar `#64748B`.

Links e ações positivas podem usar `#16A34A` ou `#22C55E`.

---

## 5. Bordas e radius

O ConfirmaID deve usar cantos arredondados, mas sem ficar infantil.

| Elemento | Radius |
|---|---:|
| Botões pequenos | `8px` |
| Inputs | `10px` |
| Cards | `16px` |
| Modais | `20px` |
| Badges | `999px` |
| Avatares / ícones circulares | `50%` |

---

## 6. Sombras

As sombras devem ser suaves.

```css
--shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
--shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
--shadow-lg: 0 16px 40px rgba(15, 23, 42, 0.12);
```

### Uso recomendado

| Sombra | Uso |
|---|---|
| `shadow-sm` | Inputs e pequenos cards |
| `shadow-md` | Cards principais |
| `shadow-lg` | Modais e painéis flutuantes |

---

## 7. Ícones

### Estilo dos ícones

Os ícones devem ser:

- Lineares.
- Minimalistas.
- Com stroke médio.
- Arredondados.
- Preferencialmente em verde ou neutral dark.

### Bibliotecas recomendadas

- Lucide Icons
- Heroicons
- Phosphor Icons

### Ícones principais da marca

| Conceito | Ícone sugerido |
|---|---|
| Identidade | ID Card |
| Segurança | Shield Check |
| Confirmação | Circle Check |
| Documento | File Text |
| Selfie/Biometria | Scan Face |
| Parceiro | Building |
| API | Braces / Code |
| Histórico | Clock |
| Bloqueado | Shield Alert |
| Revisão | Eye |

---

## 8. Botões

### Botão primário

Uso: ações principais.

Exemplos:

- Continuar
- Enviar documentos
- Aprovar verificação

```css
.btn-primary {
  background: #22C55E;
  color: #FFFFFF;
  border-radius: 10px;
  font-weight: 600;
  height: 44px;
  padding: 0 20px;
  border: none;
}
```

Hover:

```css
.btn-primary:hover {
  background: #16A34A;
}
```

---

### Botão secundário

Uso: ações alternativas.

```css
.btn-secondary {
  background: #F0FDF4;
  color: #166534;
  border: 1px solid #BBF7D0;
  border-radius: 10px;
  font-weight: 600;
}
```

---

### Botão neutro

Uso: cancelar, voltar e ações menos importantes.

```css
.btn-neutral {
  background: #FFFFFF;
  color: #0F172A;
  border: 1px solid #E2E8F0;
  border-radius: 10px;
}
```

---

### Botão destrutivo

Uso: reprovar, bloquear ou excluir.

```css
.btn-danger {
  background: #EF4444;
  color: #FFFFFF;
  border-radius: 10px;
}
```

---

## 9. Inputs

Inputs devem ser limpos, com borda clara e foco verde.

```css
.input {
  height: 44px;
  border: 1px solid #E2E8F0;
  border-radius: 10px;
  padding: 0 14px;
  font-size: 14px;
  color: #0F172A;
  background: #FFFFFF;
}
```

Foco:

```css
.input:focus {
  border-color: #22C55E;
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.16);
  outline: none;
}
```

Placeholder:

```css
.input::placeholder {
  color: #94A3B8;
}
```

---

## 10. Cards

Os cards são a base visual do produto.

### Card padrão

```css
.card {
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
  padding: 24px;
}
```

### Card de sucesso

```css
.card-success {
  background: #F0FDF4;
  border: 1px solid #BBF7D0;
  border-radius: 16px;
}
```

### Card de alerta

```css
.card-warning {
  background: #FEF3C7;
  border: 1px solid #FDE68A;
  border-radius: 16px;
}
```

---

## 11. Badges

Badges são importantes para mostrar status de verificação.

```css
.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 28px;
  padding: 0 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}
```

### Variações

```css
.badge-approved {
  background: #DCFCE7;
  color: #166534;
}

.badge-pending {
  background: #F1F5F9;
  color: #64748B;
}

.badge-review {
  background: #FEF3C7;
  color: #92400E;
}

.badge-rejected {
  background: #FEE2E2;
  color: #991B1B;
}
```

### Textos recomendados

- Verificado
- Em análise
- Pendente
- Correção solicitada
- Reprovado
- Bloqueado

---

## 12. Layout da aplicação

### Background geral

```css
body {
  background: #F8FAFC;
  color: #0F172A;
}
```

### Container

```css
.container {
  max-width: 1180px;
  margin: 0 auto;
  padding: 32px 24px;
}
```

### Grid de dashboard

```css
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 24px;
}
```

Cards principais:

```css
.card-4 {
  grid-column: span 4;
}

.card-6 {
  grid-column: span 6;
}

.card-12 {
  grid-column: span 12;
}
```

---

## 13. Componentes principais do ConfirmaID

### 13.1. Header público

Usado na landing page.

Elementos:

- Logo ConfirmaID.
- Links: Como funciona, Para empresas, Segurança, Preços.
- Botão secundário: Entrar.
- Botão primário: Criar conta.

Visual:

```text
[confirmaId]       Como funciona   Para empresas   Segurança   Preços       Entrar   Criar conta
```

---

### 13.2. Hero da landing page

Título sugerido:

> **Identidade verificada com confiança.**

Subtítulo:

> **Valide seus clientes com documento e selfie, gere um código único de verificação e permita consultas seguras por empresas parceiras.**

Botões:

- Começar agora
- Conhecer para empresas

Elementos visuais:

- Card de “Verificação concluída”.
- Ícone shield check.
- Fundo verde muito suave.

---

### 13.3. Stepper de verificação

Componente para mostrar progresso do usuário.

Etapas:

1. Dados pessoais.
2. Documento.
3. Selfie.
4. Análise.
5. Concluído.

Visual:

```text
Dados pessoais — Documento — Selfie — Análise — Concluído
```

Estado:

- Etapa concluída: verde.
- Etapa atual: verde com borda.
- Etapa futura: cinza.

---

### 13.4. Upload de documento

Card de upload:

Título:

> **Envie a frente do documento**

Descrição:

> **Use uma foto nítida, sem cortes e com boa iluminação.**

Botão:

> **Selecionar imagem**

Estados:

- Vazio.
- Carregando.
- Imagem enviada.
- Erro.
- Reenvio solicitado.

---

### 13.5. Card de status do usuário

Exemplo aprovado:

```text
Identidade verificada

Sua conta foi validada com sucesso.
Código: CID-492817

[Copiar código]
```

Visual:

- Fundo `#F0FDF4`.
- Ícone shield check.
- Código em destaque.
- Botão copiar.

---

### 13.6. Consulta do parceiro

Campo:

> **Digite o CPF, e-mail ou código de verificação**

Botão:

> **Consultar identidade**

Resultado aprovado:

```text
Cliente verificado

Nome: João da Silva
CPF: 123.***.***-00
Código: CID-492817
Verificado em: 14/06/2026
```

Importante: o parceiro não deve ver documentos ou selfies, apenas o status.

---

## 14. Telas principais

### 14.1. Tela de login

Visual:

- Fundo `#F8FAFC`.
- Card branco centralizado.
- Logo no topo.
- Inputs: e-mail e senha.
- Botão verde.
- Link para criar conta.

Copy:

> **Acesse sua conta**
>
> Entre para acompanhar sua verificação ou consultar identidades como parceiro.

---

### 14.2. Tela de cadastro

Campos:

- Nome completo.
- CPF.
- Data de nascimento.
- E-mail.
- Celular.
- Senha.
- Aceite dos termos.

Botão:

> **Criar minha conta**

Mensagem de apoio:

> Seus dados são usados apenas para validação de identidade e protegidos conforme nossa política de privacidade.

---

### 14.3. Tela de verificação

Layout recomendado:

À esquerda:

- Stepper.
- Instruções.

À direita:

- Card de envio da etapa atual.

Exemplo:

> **Verificação de identidade**
>
> Para proteger sua conta, precisamos confirmar seu documento e uma selfie com o documento ao lado do rosto.

---

### 14.4. Tela de análise

Quando o usuário já enviou tudo:

> **Sua identidade está em análise**
>
> Recebemos seus documentos e nossa equipe está validando as informações.
> Você será avisado assim que a verificação for concluída.

Badge:

> **Em análise**

Cor:

- Fundo `#FEF3C7`
- Texto `#92400E`

---

### 14.5. Tela de aprovado

> **Identidade verificada com sucesso**
>
> Agora você pode usar seu código ConfirmaID em empresas parceiras.
>
> **CID-492817**
>
> [Copiar código]

---

### 14.6. Tela do painel admin

Cards superiores:

- Verificações pendentes.
- Aprovadas hoje.
- Reprovadas hoje.
- Tempo médio de análise.

Tabela:

| Usuário | CPF | Documento | Status | Enviado em | Ação |
|---|---|---|---|---|---|

Ações:

- Ver detalhes.
- Aprovar.
- Reprovar.
- Solicitar reenvio.

---

### 14.7. Tela de parceiro

Cards:

- Consultas realizadas.
- Usuários verificados.
- Consultas no mês.
- Plano atual.

Campo principal:

```text
Consultar identidade
[CPF, e-mail ou código] [Consultar]
```

Histórico:

| Consulta | Resultado | Data | Usuário |
|---|---|---|---|

---

## 15. Tom de voz da marca

O ConfirmaID deve falar de forma:

- Clara.
- Segura.
- Direta.
- Profissional.
- Sem juridiquês excessivo.

### Exemplos de microcopy

Evite:

> Submeta seus documentos para processamento.

Use:

> Envie seus documentos para iniciar a verificação.

Evite:

> Falha na validação biométrica.

Use:

> Não conseguimos validar sua selfie. Envie uma nova foto com boa iluminação.

Evite:

> Status: approved.

Use:

> Identidade verificada.

---

## 16. Padrão de código visual

Como o nome é **ConfirmaID**, recomenda-se usar códigos assim:

```text
CID-492817
CID-84F2A9
CID-2026-8F42
```

Melhor padrão para o MVP:

```text
CID-492817
```

É curto, fácil de falar e fácil de digitar.

---

## 17. Tokens CSS

```css
:root {
  /* Brand */
  --color-primary: #22C55E;
  --color-primary-dark: #166534;
  --color-primary-light: #86EFAC;
  --color-primary-soft: #F0FDF4;

  /* Neutral */
  --color-dark: #0F172A;
  --color-gray: #64748B;
  --color-border: #E2E8F0;
  --color-background: #F8FAFC;
  --color-white: #FFFFFF;

  /* Feedback */
  --color-success: #22C55E;
  --color-success-light: #DCFCE7;
  --color-warning: #F59E0B;
  --color-warning-light: #FEF3C7;
  --color-danger: #EF4444;
  --color-danger-light: #FEE2E2;
  --color-info: #3B82F6;

  /* Radius */
  --radius-sm: 8px;
  --radius-md: 10px;
  --radius-lg: 16px;
  --radius-xl: 20px;
  --radius-full: 999px;

  /* Shadow */
  --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
  --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
  --shadow-lg: 0 16px 40px rgba(15, 23, 42, 0.12);

  /* Typography */
  --font-family: "Plus Jakarta Sans", sans-serif;
}
```

---

## 18. Classes utilitárias

```css
.text-primary {
  color: var(--color-primary);
}

.text-dark {
  color: var(--color-dark);
}

.text-muted {
  color: var(--color-gray);
}

.bg-soft-green {
  background: var(--color-primary-soft);
}

.border-default {
  border: 1px solid var(--color-border);
}

.rounded-card {
  border-radius: var(--radius-lg);
}

.shadow-card {
  box-shadow: var(--shadow-md);
}
```

---

## 19. Configuração sugerida para Tailwind

```js
colors: {
  brand: {
    50: '#F0FDF4',
    200: '#BBF7D0',
    300: '#86EFAC',
    500: '#22C55E',
    700: '#166534',
  },
  neutral: {
    50: '#F8FAFC',
    200: '#E2E8F0',
    500: '#64748B',
    900: '#0F172A',
  }
}
```

Classes comuns:

```text
bg-brand-500
text-brand-700
bg-brand-50
border-neutral-200
text-neutral-900
text-neutral-500
rounded-2xl
shadow-sm
```

---

## 20. Direção visual final

O ConfirmaID deve parecer com uma mistura de:

- SaaS moderno.
- Plataforma de segurança.
- Produto de identidade digital.
- Interface confiável para empresas brasileiras.

Referências visuais desejadas:

- Cards claros.
- Ícones verdes lineares.
- Telas com poucos elementos.
- Botões bem destacados.
- Status visuais fáceis de entender.
- Layout mobile-first.

---

## 21. Guia rápido para designer ou dev

### Use bastante

- Fundo branco ou `#F8FAFC`.
- Cards brancos.
- Verde `#22C55E` só para ações importantes.
- Texto principal `#0F172A`.
- Texto secundário `#64748B`.
- Bordas leves `#E2E8F0`.
- Radius entre `10px` e `16px`.

### Evite

- Verde demais na tela.
- Fundo escuro.
- Gradientes exagerados.
- Ícones preenchidos demais.
- Sombras pesadas.
- Muitos elementos por tela.
- Textos longos em etapas críticas.

---

## 22. Frase guia da identidade

> **ConfirmaID: identidade verificada, simples, segura e reutilizável.**
