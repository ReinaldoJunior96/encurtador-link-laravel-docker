# LinkShrink / Encurtador Link Laravel Docker

## Visão geral

Este repositório contém uma aplicação Laravel 12 que começou como um encurtador de links e evoluiu para uma base web com múltiplos módulos:

- encurtador de links com expiração e contador de cliques
- checkout via Stripe para geração de links pagos
- envio de e-mail com o link encurtado
- autenticação de usuários com controle simples por `role`
- chat público e privado em tempo real com Laravel Reverb
- área de RH com formulários dinâmicos e métricas de respostas

O projeto usa renderização server-side com Blade, assets compilados com Vite e estilização com Tailwind CSS v4.

## Stack principal

### Backend

- PHP `^8.2` no Composer
- imagem Docker baseada em `php:8.3-fpm`
- Laravel `^12.0`
- Laravel Sanctum
- Laravel Reverb
- Stripe PHP SDK
- filas com driver `database`
- sessões com driver `database`
- cache com driver `database`

### Frontend

- Blade templates
- Vite
- Tailwind CSS `^4.1`
- Axios
- Laravel Echo
- Pusher JS usado como client para o Reverb

### Testes e qualidade

- Pest
- PHPUnit
- Laravel Pint

## O que a aplicação faz hoje

### 1. Encurtador de links

Implementado em [app/Http/Controllers/Api/LinkController.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Http/Controllers/Api/LinkController.php).

Fluxo atual:

- recebe uma URL válida em `POST /api/shorten`
- gera um código aleatório de 6 caracteres
- salva em `links`
- cria expiração automática
- redireciona no acesso ao código curto
- incrementa o contador de cliques

Observações:

- o endpoint gratuito cria links com expiração de `1 minuto`
- o fluxo pago do Stripe reaproveita link válido existente ou cria novo com expiração de `10 minutos`

### 2. Checkout Stripe

Implementado em [app/Http/Controllers/StripeController.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Http/Controllers/StripeController.php).

Fluxo atual:

- recebe a URL original
- cria sessão de checkout Stripe
- após pagamento confirmado, gera ou reaproveita um link curto
- envia e-mail com o link encurtado
- renderiza a tela inicial com o resultado

Dependências externas envolvidas:

- Stripe Checkout
- serviço de e-mail configurado no Laravel

### 3. Autenticação e usuários

Implementado principalmente em [app/Http/Controllers/UserController.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Http/Controllers/UserController.php).

Recursos atuais:

- registro
- login/logout
- listagem de usuários
- edição de `role`

Papéis encontrados:

- `admin`
- `funcionario`
- `estagiario`

As permissões são definidas em [app/Providers/AuthServiceProvider.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Providers/AuthServiceProvider.php).

### 4. Chat em tempo real

Implementado em [app/Http/Controllers/ChatController.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Http/Controllers/ChatController.php), com frontend em [resources/views/chat.blade.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/resources/views/chat.blade.php) e Echo/Reverb em [resources/js/bootstrap.js](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/resources/js/bootstrap.js).

Recursos atuais:

- chat público
- chat privado entre usuários
- indicador de digitação
- persistência das mensagens em banco
- broadcast de eventos em tempo real

### 5. RH e formulários dinâmicos

Implementado em [app/Http/Controllers/FormularioController.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/app/Http/Controllers/FormularioController.php).

Recursos atuais:

- CRUD de formulários
- estrutura de campos armazenada em JSON
- formulário público por `hashSlug`
- salvamento de respostas
- tela de métricas/resumo das respostas

Tipos de campo tratados no código:

- `text`
- `textarea`
- `number`
- `radio`
- `checkbox`
- `select`

## Arquitetura da aplicação

### Backend

O backend é uma aplicação monolítica Laravel organizada por MVC:

- `app/Http/Controllers`: regras de entrada HTTP
- `app/Models`: entidades `User`, `Link`, `Message`, `Formulario`, `FormularioResposta`
- `routes/web.php`: rotas HTML/autenticadas
- `routes/api.php`: endpoints de API e Stripe
- `app/Events`: eventos de broadcast do chat
- `app/Mail`: classe de envio do e-mail do link

### Frontend

O frontend não é SPA. Ele usa:

- Blade para renderização no servidor
- Tailwind para layout e estilos
- Vite para build e hot reload
- JavaScript simples para interações e realtime

Telas principais encontradas:

- landing page em `resources/views/welcome.blade.php`
- login e registro
- dashboard
- chat público e privado
- gestão de usuários
- RH e formulários

### Banco de dados

Configuração padrão em [config/database.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/config/database.php) e [.env.example](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/.env.example).

Estado atual:

- padrão do projeto: `sqlite`
- suporte configurado também para `mysql`, `mariadb`, `pgsql` e `sqlsrv`
- o `Dockerfile` instala `pdo_mysql`
- o `docker-compose.yml` possui um serviço MySQL comentado

Na prática, hoje o repositório sugere duas possibilidades:

- desenvolvimento simples com SQLite
- adaptação para MySQL via Docker, mas o container de banco está comentado

### Infra e runtime

#### Containers

O [docker-compose.yml](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/docker-compose.yml) define:

- `app`: container principal PHP/Laravel
- `webserver`: Nginx servindo a aplicação
- `db`: existe no arquivo, mas está comentado

#### Dentro do container `app`

O [Dockerfile](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/Dockerfile) e o [supervisor.conf](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/.docker/supervisor/supervisor.conf) mostram que o container executa:

- `php-fpm`
- `php artisan serve --port=8090`
- `php artisan reverb:start --port=6001`
- `php artisan queue:work --daemon`
- `cron` para `php artisan schedule:run` a cada minuto

#### Proxy web

O Nginx em [.docker/nginx/default.conf](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/.docker/nginx/default.conf) aponta para:

- document root em `/var/www/public`
- PHP-FPM no host `encurtador-link-backend:9000`

### Realtime

O projeto usa:

- Laravel Reverb no backend
- Laravel Echo + Pusher JS no frontend
- WebSocket exposto na porta `6001` do container `app`

## Serviços externos e integrações

### Stripe

Usado para:

- criar sessão de pagamento
- validar status do checkout antes de liberar o link

Variáveis esperadas:

- `STRIPE_SECRET`
- opcionalmente `price_id`, `success_url` e `cancel_url` via request

### E-mail

Usado para:

- envio do link encurtado após pagamento

O `.env.example` está com `MAIL_MAILER=log`, então por padrão o projeto não envia e-mail real até a configuração de um provider SMTP/API.

### Reverb / WebSocket

Variáveis esperadas no ambiente:

- `REVERB_APP_KEY`
- `REVERB_APP_SECRET`
- `REVERB_APP_ID`
- `REVERB_HOST`
- `REVERB_PORT`
- `REVERB_SCHEME`
- equivalentes `VITE_REVERB_*` no frontend

## Estrutura de pastas

```text
.
├── .docker/
│   ├── nginx/default.conf
│   └── supervisor/supervisor.conf
├── app/
│   ├── Events/
│   ├── Http/Controllers/
│   │   ├── Api/LinkController.php
│   │   ├── ChatController.php
│   │   ├── FormularioController.php
│   │   ├── StripeController.php
│   │   └── UserController.php
│   ├── Mail/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── package.json
└── vite.config.js
```

## Modelagem principal

Tabelas/módulos identificados nas migrations:

- `users`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `personal_access_tokens`
- `links`
- `messages`
- `formularios`
- `formulario_respostas`

## Rotas principais

### Web

- `/` landing page
- `/login`
- `/register`
- `/dashboard`
- `/chat`
- `/chat/{user}`
- `/users`
- `/rh`
- `/rh/formulario/*`
- `/formulario/{hashSlug}`

### API

- `POST /api/shorten`
- `GET /api/s/{code}`
- `GET /api/checkout`
- `GET /api/success`
- `POST /api/stripe/webhook`

## Seeders existentes

O projeto já traz dados iniciais em [database/seeders/DatabaseSeeder.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/database/seeders/DatabaseSeeder.php):

- usuários de exemplo com papéis diferentes
- formulário de exemplo
- 500 respostas fake para métricas

Usuários seedados:

- `rei@email.com` / `123456` (`admin`)
- `bob@email.com` / `123456` (`funcionario`)
- `ana@email.com` / `123456` (`funcionario`)

## Como rodar

### Opção 1: local com Laravel

Pré-requisitos:

- PHP 8.2+
- Composer
- Node.js
- npm

Passos:

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run dev
php artisan serve
```

Se quiser chat em tempo real e fila funcionando no modo local, rode também:

```bash
php artisan reverb:start
php artisan queue:work
php artisan schedule:work
```

### Opção 2: Docker

Passos base:

```bash
docker compose up -d --build
docker exec -it encurtador-link-backend composer install
docker exec -it encurtador-link-backend php artisan key:generate
docker exec -it encurtador-link-backend php artisan migrate --seed
```

Portas encontradas:

- `8081`: Nginx
- `8090`: `artisan serve`
- `6001`: Reverb/WebSocket

## Testes

Existe cobertura inicial para o encurtador em [tests/Feature/LinkShortenerTest.php](/Users/reinaldojunior/Documents/encurtador-link-laravel-docker/tests/Feature/LinkShortenerTest.php).

Para executar:

```bash
php artisan test
```

## Estado atual do repositório

Pontos observados na análise:

- o `README` antigo descrevia só o encurtador, mas o projeto já tem outros módulos
- `vendor/` não está presente no workspace analisado
- `node_modules/` não está presente no workspace analisado
- por isso, os testes não puderam ser executados neste estado
- o serviço de banco no `docker-compose.yml` está comentado
- a rota `POST /api/stripe/webhook` existe em `routes/api.php`, mas não encontrei o método `webhook` implementado em `StripeController`
- o `.env.example` não traz as variáveis de Stripe e Reverb, embora o código espere essas configurações

## Resumo executivo

Hoje este projeto é um monólito Laravel com foco comercial e administrativo, combinando:

- geração de links curtos
- cobrança via Stripe
- envio de e-mail
- autenticação com papéis
- chat em tempo real
- formulários dinâmicos para RH

Ele já tem uma boa base de backend, views e infraestrutura Docker, mas ainda pede consolidação operacional em três frentes: variáveis de ambiente, banco de dados no `docker-compose` e fechamento de algumas integrações pendentes.
