# Documentação da API de despesas

Esta API fornece uma maneira de gerenciar despesas. Ela usa a autenticação Sanctum para proteger as rotas.

## Começando

Para começar a usar a API, execute o seguinte comando:

`./vendor/bin/sail up` 

Para que o comando `./vendor/bin/sail up` up rode corretamente, você precisa ter o seguinte configurado:

- Docker: O comando docker deve estar instalado e disponível no seu sistema. Você pode verificar se o Docker está instalado executando o seguinte comando: 
  
  **docker --version**

- Sail: O comando sail deve estar disponível no seu sistema. Você pode instalar o Sail executando o seguinte comando: 

  **composer require laravel/sail**

- Permissão de execução: O arquivo ./vendor/bin/sail deve ter permissão de execução. Você pode verificar se o arquivo tem permissão de execução executando o seguinte comando:

  **chmod +x ./vendor/bin/sail**

- Ambiente de execução: O comando sail up deve ser executado no mesmo diretório do arquivo docker-compose.yml. O arquivo docker-compose.yml é um arquivo de configuração que define os containers Docker que serão criados quando você executar o comando sail up.

Se você tiver todos esses itens configurados, o comando `./vendor/bin/sail up` deverá funcionar corretamente.

Para rodar as migrations execute o seguinte comando:

`./vendor/bin/sail artisan migrate` 

## Autenticação

- Para obter um token de acesso, será preciso registrar um usuário para isso envie uma solicitação POST para rota /api/register. A solicitação deve conter os seguintes campos:
    - *email*: O endereço de e-mail do usuário.
    - *name*: Nome do usuário.
    - *password*: A senha do usuário.
    - *password_confirmation*: Confirmação da senha do usuário.

- Após o registro envie uma solicitação POST para a rota /api/token, para obter o token de forma efetiva:
    - *email*: O endereço de e-mail do usuário.
    - *token_name*: Nome do token. A resposta da solicitação será um token de acesso do Sanctum.

## Endpoints

### A API fornece os seguintes endpoints:

POST /api/register: Registra um usuário. 

POST /api/token: Cria a token de autenticação (Guarde ela com atenção pois será utilizada nas desmais requisições). 

POST /api/expenses: Cria uma nova despesa.

GET /api/expenses: Lista todas as despesas. 

GET /api/expenses/{id}: Obtém uma despesa específica. 

PUT /api/expenses/{id}: Atualiza uma despesa específica. 

DELETE /api/expenses/{id}: Exclui uma despesa específica.

## Exemplos:

### Registra um novo usuário

curl -X POST

- H "Content-Type: application/json"
- d `{ "email": "[johndoe@example.com](mailto:johndoe@example.com)", "name": "John Doe", "password": "Password1234", "password_confirmation": "Password1234", }`

http://localhost/api/register

### Obter um token de acesso

curl -X POST

- H "Content-Type: application/json"
- d `{ "email": "[johndoe@example.com](mailto:johndoe@example.com)", "token_name": "name token" }`

http://localhost/api/token

### Listar todas as despesas

curl -X GET

- H "Authorization: Bearer {token}"

http://localhost/api/expenses

### Criar uma nova despesa

curl -X POST

- H "Authorization: Bearer {token}"
- H "Content-Type: application/json"
- d `{ "description": "Despesa de teste", "user_id": 1, "value": 100, "date": "2023-09-24 00:00:00" }`

http://localhost/api/expenses

### Atualizar uma despesa

curl -X PUT

- H "Authorization: Bearer {token}"
- H "Content-Type: application/json"
- d `{ "description": "Despesa de teste", "user_id": 1, "value": 100, "date": "2023-09-24 00:00:00" }`

http://localhost:8000/api/expenses/1

### Excluir uma despesa

curl -X DELETE

- H "Authorization: Bearer {token}"

http://localhost:8000/api/expenses/1

## Testes:

Para executar todos os testes de uma única vez execute o seguinte comando:

`./vendor/bin/sail test`

Se pretende executa-los de forma separada, execute um de cada vez:

- **./vendor/bin/sail test tests/Feature/Http/Controllers/API/TokenControllerTest.php**
- **./vendor/bin/sail test tests/Feature/Http/Controllers/API/ExpenseControllerTest.php**
- **./vendor/bin/sail test tests/Feature/Listeners/Expense/Created/SendCreatedExpenseNotificationTest.php**
- **./vendor/bin/sail test tests/Feature/Notifications/ExpenseCreatedTest.php**
