
# 💸 API de Transferências

---

## 📌 Sumário

1. [Descrição do Projeto](#descrição-do-projeto)  
2. [Tecnologias Utilizadas](#tecnologias-utilizadas)  
3. [Instalação e Execução](#instalação-e-execução)  
4. [Padrões de Projeto (Design Patterns)](#padrões-de-projeto-design-patterns)  
5. [Testes Automatizados](#testes-automatizados)  
6. [Endpoints da API](#endpoints-da-api)  
   - [Cadastro de Usuário (`/register`)](#cadastro-de-usuário-register)  
   - [Transferência (`/transfer`)](#transferência-transfer)  
7. [Estrutura do Banco de Dados (MySQL)](#estrutura-do-banco-de-dados-mysql)  
8. [Estrutura do Projeto](#estrutura-do-projeto)  
9. [Considerações Finais](#considerações-finais)

---

## 📖 Descrição do Projeto

Esta API permite o **cadastro de usuários** e a **realização de transferências financeiras** entre contas. O sistema valida tipo de usuário, saldo disponível e autorizador externo antes de concluir transações.

---

## 🧰 Tecnologias Utilizadas

- **PHP 8.1+**
- **PHP Puro**
- **MySQL 8+**
- **Docker & Docker Compose**
- **PHPUnit**
- **Design Patterns** 

---

## 🚀 Instalação e Execução

### Pré-requisitos

- Docker
- Docker Compose

### Subir o ambiente

```bash
docker build -t api-pps .
docker-compose up -d --build
```

### Instalar dependências do PHP

```bash
composer install
```

---

## 🎯 Padrões de Projeto (Design Patterns)

- **Repository Pattern**
- **Singleton**
- **Factory**
- **Template Method**
- **Chain of Responsibility**

---

## ✅ Testes Automatizados

Utilizando **PHPUnit**, os testes estão localizados na pasta `tests/`.

### Rodar os testes

```bash
vendor/bin/phpunit tests/Feature/Error/TransferRouteTest.php
vendor/bin/phpunit tests/Feature/Success/RegisterRouteTest.php
```

---

## 🔐 Endpoints da API

### 📥 Cadastro de Usuário `/register`

- **Método:** `POST`
- **URL:** `/register`
- **Content-Type:** `application/x-www-form-urlencoded`

#### Campos esperados:

| Campo             | Tipo    | Obrigatório | Observações                 |
|------------------|---------|-------------|-----------------------------|
| `name`           | string  | Sim         | Nome completo               |
| `email`          | string  | Sim         | Deve ser único e válido     |
| `document`       | string  | Sim         | CPF ou CNPJ válido          |
| `password`       | string  | Sim         | Mínimo 3 caracteres         |
| `confirmPassword`| string  | Sim         | Igual ao campo `password`   |
| `typeUser`       | string  | Sim         | `COMMON` ou `SHOPKEEPER`    |

---

### 💸 Transferência `/transfer`

- **Método:** `POST`
- **URL:** `/transfer`
- **Content-Type:** `application/x-www-form-urlencoded`

#### Campos esperados:

| Campo    | Tipo   | Obrigatório | Descrição                         |
|----------|--------|-------------|-----------------------------------|
| `payer`  | int    | Sim         | ID do usuário que envia o valor   |
| `payee`  | int    | Sim         | ID do usuário que recebe          |
| `value`  | float  | Sim         | Valor a ser transferido           |

---

## 🗃️ Estrutura do Banco de Dados (MySQL)

### Tabelas

#### `users`

| Campo     | Tipo         | Observações           |
|-----------|--------------|------------------------|
| id        | INT          | Primary Key, Auto Inc  |
| name      | VARCHAR(255) |                        |
| email     | VARCHAR(255) | Único                  |
| document  | VARCHAR(20)  | CPF ou CNPJ            |
| password  | VARCHAR(255) | Hashed                 |
| typeUser  | ENUM         | `COMMON` ou `SHOPKEEPER` |

#### `wallets`

| Campo     | Tipo      | Observações           |
|-----------|-----------|------------------------|
| id        | INT       | Primary Key            |
| user_id   | INT       | Foreign Key → users.id |
| balance   | DECIMAL   |                        |

#### `transactions`

| Campo      | Tipo      | Observações              |
|------------|-----------|---------------------------|
| id         | INT       | Primary Key               |
| payer_id   | INT       | Foreign Key → users.id    |
| payee_id   | INT       | Foreign Key → users.id    |
| value      | DECIMAL   |                           |
| created_at | TIMESTAMP |                           |

---

## 📂 Estrutura do Projeto

```plaintext
PPS
├── .phpunit.cache/       # Cache gerado pelo PHPUnit.
├── Api/                  # Diretório principal da aplicação.
│   └── Business/         # Lógica de negócios.
│       ├── Data/         # Manipulação de dados.
│       ├── Queues/       # Gerenciamento de filas.
│       └── FactoryBusiness.php # Fábrica para instanciar objetos de negócio.
│   ├── Config/           # Configurações da aplicação.
│   ├── Controller/       # Controladores responsáveis pelas rotas.
│   ├── Exceptions/       # Tratamento de exceções.
│   ├── Lib/              # Bibliotecas auxiliares.
│   ├── Repository/       # Repositórios para acesso ao banco de dados.
│   │   ├── Entity/       # Entidades que representam tabelas do banco.
│   │   └── Mapper/       # Mapeamento entre objetos e tabelas.
│   └── docs/             # Documentação da API.
├── scripts/              # Scripts auxiliares.
├── tests/                # Testes automatizados.
├── vendor/               # Dependências gerenciadas pelo Composer.
├── views/                # Arquivos de visualização (se aplicável).
├── .dockerignore         # Arquivos ignorados pelo Docker.
├── .env                  # Variáveis de ambiente.
├── .gitignore            # Arquivos ignorados pelo Git.
├── .htaccess             # Configuração do servidor Apache.
├── composer.json         # Configuração do Composer.
├── composer.lock         # Lockfile do Composer.
├── docker-compose.yml    # Configuração do Docker Compose.
├── Dockerfile            # Configuração do Docker.
├── index.php             # Ponto de entrada da aplicação.
├── init.php              # Inicialização da aplicação.
├── ip_requests.json      # Registro de IPs para controle de requisições.
└── phpunit.xml           # Configuração do PHPUnit.
```

## 🧠 Considerações Finais

- **Lojistas não podem realizar transferências**, apenas receber.
- O sistema possui validações de saldo e tipo de usuário.
- Arquitetura pensada para escalar com segurança e testes confiáveis.
- Possibilidade futura de integração com serviços externos para autenticação, notificações e etc.

---

