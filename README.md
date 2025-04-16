# web2-ecommerce

## 📝 Descrição
Projeto de e-commerce desenvolvido para a disciplina de Web II. Este projeto utiliza Docker para gerenciar os serviços necessários, incluindo um banco de dados PostgreSQL e uma aplicação PHP.

## 📂 Estrutura do Projeto
```
docker-compose.yaml
Dockerfile
README.md
src/
    index.php
    init.php
    dao/
        PostgresDao.php
```

## ✅ Pré-requisitos
- Docker
- Docker Compose

## ⚙️ Configuração
1. **Criar o arquivo `.env`:**
   Crie um arquivo chamado `.env` na raiz do projeto, baseado no arquivo `.env-example`. Para isso, execute o comando:
   ```bash
   cp .env-example .env
   ```
   Em seguida, edite o arquivo `.env` com as configurações adequadas para o seu ambiente.

2. **Subir os serviços com Docker Compose:**
   Execute o comando abaixo para construir as imagens e iniciar os contêineres:
   ```bash
   docker-compose up --build
   ```

3. **Acessar o projeto:**
   Após os contêineres estarem em execução, acesse o projeto no navegador através do endereço:
   [http://localhost:8080](http://localhost:8080)

## 🛑 Parar os serviços
Para parar e remover os contêineres, utilize o comando:
```bash
docker-compose down
```