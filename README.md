 # Projeto Symfony

## Requisitos para instalar a aplicação

- [Composer](https://getcomposer.org/download/)
- [Docker](https://docs.docker.com/get-docker/)

## Instalando projeto

Clonar o repositório
```
$ git clone https://github.com/eduardojansen/symfony-project.git
```

Acessar o diretório da aplicação
```
$ cd symfony-project
```

Criar containers do docker
```
$ docker-compose up --build
```

Instalar dependências do projeto
```
$ docker-compose run --rm php74-container composer install --ignore-platform-reqs
```

Criar banco de dados
```
$ docker-compose run --rm php bin/console doctrine:database:create
```

Executar migration
```
$ docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction
```

Executar fixtures
  * Será um usuário para teste da API aplicação. (User: admin, Senha: secret) 
     
```
$ docker-compose run --rm php74-container bin/console -e test doctrine:fixtures:load
```

## Execução dos testes

Criar banco de teste
```
$ docker exec -it  php74-container php bin/console -e test doctrine:database:create
```
Criar estrutura do banco 
```
$ docker exec -it  php74-container php bin/console -e test doctrine:schema:create
```
Executar fixtures para criação do usuário de teste
```
$ docker exec -it  php74-container bin/console -e test doctrine:fixtures:load -n
```
Executar testes da aplicação
```
$ docker exec -it  php74-container php ./vendor/bin/phpunit
```

## Acessar sistema

#### Importar arquivos XML

Anexe apenas arquivos XML, podendo anexar mais de um arquivo por vez. Após o upload, será exibido uma lista com os arquivos aptos para serem processados para o sisrtema.

* http://localhost:8080/index
    
## API

Os endpoints da API são acessíveis apenas para usuários autenticados. O usuário possui um token e esse token precisa ser enviado no cabeçalho de cada requisição, caso contrário será retornando um erro `HTTP 401`.

**Login**
---- 
Para gerar o token faça um POST para rota de login que será retornado um objeto com o `access_token`

O acesso pode ser feito usando as seguintes credenciais, desde que teha execuatdo o comando para executação das fixtures no ambiente de testes. 
  * User: admin | Senha: secret

##### Request
```
curl --request POST \
  --url http://localhost:8080/login \
  --header 'Content-Type: application/json' \
  --cookie PHPSESSID=7df965aa8507c34348e1ddc2c9c08e01 \
  --data '{
	"username": "admin",
	"password": "secret"
}'
```

##### Response

```
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIn0.TYD3rPsEhOOUnVlleDekzmFOlVujXKONE3fjln1I9NQ"
}
```

**People**
----
  Retorna um JSON com todos as pessoas cadastradas.

* **URL**

  http://localhost:8080/people


* **Method:**

  `GET`
  
*  **Query Params**
   * Ordenar pelo campo code ou qualquer outra campo da entidade.  
      * `sort[code]=DESC` ou `sort[name]=DESC`
   * Filtrar por nome "Name 1" e código "1"
      * `?name=Name%201&code=1`


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
```
{
  "success": true,
  "currentPage": 1,
  "perPage": 5,
  "data": [
    {
      "id": 4,
      "code": 1,
      "name": "Name 1",
      "phones": [
        "2345678",
        "1234567"
      ]
    },
    {
      "id": 5,
      "code": 2,
      "name": "Name 2",
      "phones": [
        "4444444"
      ]
    },
    {
      "id": 6,
      "code": 3,
      "name": "Name 3",
      "phones": [
        "7777777",
        "8888888"
      ]
    }
  ]
}
```
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Falha na autenticação." }`



**Shiporder**
----
  Retorna um JSON com todos as encomendas.

* **URL**

  http://localhost:8080/shiporders


* **Method:**

  `GET`
  
*  **Query Params**
   * Ordenar pelo campo code ou qualquer outra campo da entidade.  
      * `sort[code]=DESC`
   * Filtrar por nome "Name 1" e código "1"
      * `?code=1`


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
```
{
  "success": true,
  "currentPage": 1,
  "perPage": 5,
  "data": [
    {
      "id": 1,
      "code": 3,
      "person": {
        "id": 6,
        "code": 3,
        "name": "Name 3",
        "phones": [
          "7777777",
          "8888888"
        ]
      },
      "shipto": {
        "name": "Name 9",
        "address": "Address 9",
        "city": "City 9",
        "country": "Country 9"
      },
      "items": [
        {
          "id": 1,
          "title": "Title 9",
          "note": "Note 3",
          "quantity": 5,
          "prince": 1.12
        },
        {
          "id": 2,
          "title": "Title",
          "note": "Note 4",
          "quantity": 2,
          "prince": 77.12
        }
      ]
    }
  ]
}
```
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Falha na autenticação." }`




    
