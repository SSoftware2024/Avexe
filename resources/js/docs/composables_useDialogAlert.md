# useDialogAlert

**Categoria:** Composable   
**Arquivo:** `resources/js/composables/useDialogAlert.js`  

---

## 1. Importação

```javascript
import useDialogAlert from "@/composables/useDialogAlert";
```

Utilização:

```javascript
const dialogAlert = useDialogAlert();
```

O composable retorna uma API compartilhada. Portanto, não é necessário criar uma nova instância para cada componente.

## 2. Tipos de alertas

O método `open()` aceita os seguintes tipos:

| Tipo | Ícone | Finalidade |
|---|---|---|
| `info` | `mdi-information` | Informações gerais |
| `success` | `mdi-check-circle` | Operações concluídas |
| `warning` | `mdi-alert` | Avisos importantes |
| `error` | `mdi-close-octagon` | Erros e falhas |
| `question` | `mdi-help-circle-outline` | Solicitações de confirmação |

Cada tipo determina automaticamente o título e a cor do alerta.

## 3. Métodos disponíveis

### `open(message, type = 'info')`

Abre o diálogo com a mensagem e o tipo especificados.

| Parâmetro | Tipo | Descrição |
|---|---|---|
| `message` | String | Mensagem exibida no diálogo |
| `type` | String | Tipo do alerta. Padrão: `info` |

Retorno: `void`.

Exemplo:

```javascript
dialogAlert.open(
    "Empresa cadastrada com sucesso!",
    "success"
);
```

### `close()`

Fecha o diálogo.

Não recebe parâmetros.

Exemplo:

```javascript
dialogAlert.close();
```

### `setButtons(options)`

Configura os botões de confirmação e cancelamento.

| Propriedade | Tipo | Padrão | Descrição |
|---|---|---|---|
| `question_buttons` | Boolean | `false` | Exibe os botões de confirmação |
| `confirm_button_text` | String | `""` | Texto do botão de confirmação |
| `cancel_button_text` | String | `""` | Texto do botão de cancelamento |

Retorno: `api`, permitindo encadeamento de métodos.

Exemplo:

```javascript
dialogAlert.setButtons({
    question_buttons: true,
    confirm_button_text: "Excluir",
    cancel_button_text: "Cancelar"
});
```

### `confirmFunction(callback)`

Define a função executada quando o usuário confirma a operação.

| Parâmetro | Tipo | Descrição |
|---|---|---|
| `callback` | Function | Função executada na confirmação |

Retorno: `api`.

Exemplo:

```javascript
dialogAlert.confirmFunction(() => {
    console.log("Operação confirmada!");
});
```

### `cancelFunction(callback)`

Define a função executada quando o usuário cancela a operação.

| Parâmetro | Tipo | Descrição |
|---|---|---|
| `callback` | Function | Função executada no cancelamento |

Retorno: `api`.

Exemplo:

```javascript
dialogAlert.cancelFunction(() => {
    console.log("Operação cancelada!");
});
```

## 4. Exemplos de utilização

### 4.1. Alerta informativo

Utilizado para apresentar informações ao usuário.

```javascript
dialogAlert.open(
    "Dados atualizados com sucesso!",
    "success"
);
```

### 4.2. Confirmação de exclusão

Utilizado quando uma operação exige autorização do usuário antes de ser executada.

```javascript
dialogAlert
    .setButtons({
        question_buttons: true,
        confirm_button_text: "Excluir",
        cancel_button_text: "Cancelar"
    })
    .confirmFunction(() => {
        _delete(item.id);
    })
    .cancelFunction(() => {
        console.log("Exclusão cancelada");
    });

dialogAlert.open(
    `Deseja realmente excluir a empresa ${item.name}?`,
    "question"
);
```

### 4.3. Confirmação sem callback de cancelamento

O callback de cancelamento é opcional.

```javascript
dialogAlert
    .setButtons({
        question_buttons: true
    })
    .confirmFunction(() => {
        save();
    });

dialogAlert.open(
    "Deseja salvar as alterações?",
    "question"
);
```

## 5. Encadeamento de métodos (Method Chaining)

Os métodos `setButtons()`, `confirmFunction()` e `cancelFunction()` retornam o objeto `api`.

Isso permite configurar várias propriedades em uma única sequência de chamadas.

Exemplo:

```javascript
dialogAlert
    .setButtons({
        question_buttons: true,
        confirm_button_text: "Confirmar",
        cancel_button_text: "Voltar"
    })
    .confirmFunction(() => {
        executeAction();
    })
    .cancelFunction(() => {
        rollback();
    });

dialogAlert.open("Deseja continuar?", "question");
```

## 6. Estados compartilhados

O composable disponibiliza estados reativos exportados diretamente pelo módulo.

| Estado | Tipo | Descrição |
|---|---|---|
| `dialog` | Ref | Controla a visibilidade do diálogo |
| `data` | Reactive | Armazena mensagens, títulos, cores e configurações dos botões |
| `get_icon` | Computed | Determina o ícone conforme o tipo do alerta |

Esses estados são consumidos pelo componente `DialogAlert.vue`.

### Estrutura de `data`

```javascript
{
    title: "",
    message: "",
    type: "info",
    color: "",

    question_buttons: false,
    cancel_button_text: "",
    cancel_function: null,

    confirm_button_text: "",
    confirm_function: null
}
```

## 7. Componente associado: DialogAlert.vue

Responsável pela apresentação visual do diálogo.

Utiliza os estados e métodos exportados pelo composable para:

- Controlar sua abertura e fechamento.
- Apresentar o título e a mensagem.
- Exibir o ícone e a cor correspondentes.
- Exibir botões de confirmação quando configurados.
- Executar os callbacks de confirmação e cancelamento.

**Importante:** o componente deve ser montado globalmente na aplicação para que o diálogo esteja disponível em diferentes páginas e componentes.

## 8. Observações importantes

- O composable utiliza uma API compartilhada, não criando estados independentes por componente.
- Os métodos de configuração permitem encadeamento.
- Os callbacks de confirmação e cancelamento são opcionais.
- O método `open()` define automaticamente `question_buttons` como `true` quando o tipo é `question`.
- O método `open()` não limpa os callbacks e textos personalizados anteriormente configurados. Portanto, é necessário considerar configurações anteriores ao reutilizar o diálogo.
- O método `confirm()` executa o callback de confirmação e fecha o diálogo.
- O método `cancel()` executa o callback de cancelamento e fecha o diálogo.
- Os callbacks são executados sem argumentos e não possuem tratamento de erros ou espera de operações assíncronas na implementação atual.