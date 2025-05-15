# Encurtador de Links - Laravel

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

## Sobre o Projeto

Este projeto é uma API de encurtador de links desenvolvida em Laravel. Você envia uma URL longa e recebe um link curto, que redireciona para a URL original. Os links possuem tempo de expiração configurável (por padrão, 1 minuto).

### Funcionalidades
- Encurtar links via API
- Redirecionamento automático ao acessar o link curto
- Expiração automática dos links (default: 1 minuto)
- Contador de cliques
- Respostas em JSON para integração fácil

## Como rodar o projeto

### Pré-requisitos
- Docker e Docker Compose instalados
- PHP 8+
- Composer

### Passos

1. **Clone o repositório:**
   ```sh
   git clone https://github.com/seu-usuario/encurtador-link.git
   cd encurtador-link
   ```

2. **Suba os containers:**
   ```sh
   docker-compose up -d
   ```

3. **Instale as dependências:**
   ```sh
   docker exec -it encurtador-link-backend composer install
   ```

4. **Rode as migrations:**
   ```sh
   docker exec -it encurtador-link-backend php artisan migrate
   ```

5. **(Opcional) Rode os testes:**
   ```sh
   docker exec -it encurtador-link-backend ./vendor/bin/pest
   ```

6. **Acesse a aplicação:**
   - A API estará disponível em: `http://localhost:8081`

## Como usar

### Encurtar um link
- **Endpoint:** `POST /api/shorten`
- **Body (JSON):**
  ```json
  {
    "url": "https://www.google.com"
  }
  ```
- **Resposta:**
  ```json
  {
    "short_url": "http://localhost:8081/s/abc123",
    "original_url": "https://www.google.com"
  }
  ```

### Redirecionar
- Basta acessar a URL curta retornada, exemplo:
  - `http://localhost:8081/s/abc123`

### Expiração
- O link expira após 1 minuto da criação. Após expirar, retorna erro 410 (Link expirado).

## Testes
- Os testes automatizados cobrem todos os fluxos principais, incluindo expiração, redirecionamento e validação de URL.

## Licença
MIT
