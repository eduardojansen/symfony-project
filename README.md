# Projeto Symfony
Fast lightweight Docker network using PHP MySQL Nginx and Node

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
    * http://localhost:8080/index
    
## Acesso a API

Os endpoints da API são acessíveis apenas para usuários autenticados. O usuário possui um token e esse token precisa ser enviado no cabeçalho de cada requisição, caso contrário será retornando um erro `HTTP 401`.

#### Login
 
Para gerar o token faça um POST para rota de login que será retornado um objeto com o `access_token`

* Request
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

* Response 

```
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIn0.TYD3rPsEhOOUnVlleDekzmFOlVujXKONE3fjln1I9NQ"
}
```

## Endpoints

* GET http://localhost:8080/people

* GET http://localhost:8080/shiporders

 

    