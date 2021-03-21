# Lumen Table Price

Essa API permite a criação da Table Price , também chamado de sistema francês de amortização.

## Estrutura de pastas:

-   `./src/` código fonte do projeto
-   `./docker/` arquivos auxiliares dos containeres

## Installation

Clone o projeto

```bash
git clone https://RoggerLima@bitbucket.org/RoggerLima/iouu.git
```

Acesse a pasta iouu:

```bash
cd iouu
```

Inicie o projeto (docker da API e MariaDb):

```bash
docker-compose up --build -d app_php
```

Instale as dependências do Lumen:

```bash
docker-compose run -w /var/www/html app_php composer install
```

Executa as migrations:

```bash
docker-compose run -w /var/www/html app_php php artisan migrate --seed
```

## Uso

### EndPoints

> Criar cliente

```php
http://localhost/api/member/create
Inputs
- name
- email
Response
 - json  // dados do cliente
```

> Criar contrato

```php
http://localhost/api/contract/create
Inputs
- member_id // código do cliente retornado no endpoind criar cliente
- amount // total a ser financiado
- periods // prazo de pagamento
- rate // taxa
Response
 - json // dados do contrato e tabela price
```

> Paragar a parcela

```php
http://localhost/api/contract/make/payment
Parametros
- member_id // código do cliente retornado no endpoind criar cliente
- contract_id // código do contrato
- quota // parcela que será paga
Response
- json // com informações da parcela paga
```

> Renegociar contrato

```php
http://localhost/api/contract/renegotiate
Parametros
- member_id // código do cliente retornado no endpoind criar cliente
- contract_id // código do contrato
- periods // prazo de pagamento
- rate // taxa (nao e obrigatorio, se não informado utiliza a taxa do contrato anterior)
Repsonse
 - json // novo contrato e nova tabela price
```

> Comtratos do cliente

```php
http://localhost/api/contract/client
Parametros
- member_id // código do cliente retornado no endpoind criar cliente
- email // código do contrato
Repsonse
 - json // retorno todos os contrados (ativos e renegociados)
```

# Tabelas

## Members

Armazena as informações do cliente

## Contratcs

Armazena as informações do contrato

## Table_prices

Armazena as informações da tabela price

## Dados de acesso ao banco de dados MariaDB

```bash
host: 127.0.0.1
User: iouu
Senha: iouu2021
Base: iouu
Porta: 3306

```

### Testing

```bash
 docker-compose run -w /var/www/html app_php php vendor/bin/phpunit
```

## License
