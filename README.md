## Transfer APP

Transfer APP é um MVP de transferência de dinheiro entre usuários
ele utiliza:
- PHP 7.4
- Laravel 8.x
- Mysql 5.7

### Instalação - Docker

##### 1 - Criar Containers
Execute o seguinte comando para que os containers sejam criados, mas atenção
a aplicação e o banco vão ser publicados nas portas 80, e 3306 então antes de executar
tenha certeza que não tenha nada executando nelas:

```
$ docker-compose up -d
```
Irá ser criado 3 containers:
- transfer-app-php: Local onde o PHP-FPM está instalado
- transfer-app-database: instancia que esta localizado nosso banco de dados
- transfer-app-server: Onde o servidor Nginx esta localizado

##### 2 - Instalando Dependencias
Rode o comando a seguir para que as dependências do PHP sejam instaladas:

```
$ docker exec transfer-app-php composer install
```

##### 3 - Configurando a aplicação
O comando a seguir copia o .env de exemplo para ser seu principal
no de exemplo já está configurado com as informações necessárias para 
se desenvolver a aplicação:
```
$ docker exec transfer-app-php cp .env.example .env
```

##### 4 - Migrando banco de dados
Rode o comando a seguir para que se crie as tabelas do banco de dados
e se quiser execute com a flag --seed para que sejam criadas informações
de teste:
```
$ docker exec transfer-app-php php artisan migrate
```

##### 5 - Testes (Opcional)
Para executar os testes automatizados basta executar:
```
$ docker exec transfer-app-php php artisan test
```

## Documentação

A documentação do modulo da API se encontra na especificação
open-api no seguinte link:

http://localhost/documentation

A especificação em formato .json se encontra no seguinte caminho

```
$ ./public/open-api/index.json
```



