# Task - Gerenciador de Tarefas
![Status](https://img.shields.io/badge/status-em%20desenvolvimento-blue)
![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-336791?logo=postgresql)
![jQuery](https://img.shields.io/badge/jQuery-Frontend-0769AD?logo=jquery)

## 📸 Preview

> Interface moderna com dashboard, filtros dinâmicos e interação sem reload
<img width="2480" height="4100" alt="Image" src="https://github.com/user-attachments/assets/73590d32-e970-40b1-9f36-c242e82ee09e" />

## Descrição do Projeto

O **Task - Gerenciador de Tarefas** é um sistema web desenvolvido com foco na organização e no acompanhamento de atividades em um ambiente de trabalho ou estudo. O projeto permite cadastrar, visualizar, editar, excluir e filtrar tarefas, facilitando o controle do fluxo de trabalho e a distribuição de responsabilidades entre usuários.

O sistema já conta com integração entre **frontend**, **backend** e **banco de dados**, utilizando uma arquitetura simples, organizada e adequada para fins acadêmicos e de aprendizado prático.

## Tecnologias Utilizadas

### Frontend

* **HTML5**
* **CSS3**
* **JavaScript**
* **jQuery**
* **AJAX** para interações assíncronas sem recarregar a página

### Backend

* **PHP**
* **PDO (PHP Data Objects)** para conexão segura com o banco de dados

### Banco de Dados

* **PostgreSQL**

### Configuração

* **.env** para armazenamento seguro de credenciais

---

## Funcionalidades Implementadas

### 1. Cadastro de tarefas

O sistema permite cadastrar tarefas contendo os principais dados necessários para organização:

* título;
* descrição;
* responsável;
* prioridade;
* prazo;
* status.

### 1.1 👤 Responsáveis
- Cadastro sem reload/f5
- Validação de e-mail único
- Atualização automática do select

O cadastro pode ser realizado diretamente pela interface principal do sistema.

### 2. Listagem de tarefas

As tarefas cadastradas são exibidas em cartões visuais, contendo as principais informações para acompanhamento rápido.

### 3. Edição de tarefas

O sistema permite selecionar uma tarefa existente para editar seus dados.

### 4. Exclusão de tarefas

A exclusão funciona com confirmação do usuário e é realizada via AJAX, sem recarregar a página.

### 5. Filtros dinâmicos

As tarefas podem ser filtradas por:

* status;
* prioridade;
* responsável.

Os filtros funcionam de forma dinâmica.

### 6. Atualização visual de indicadores

O painel superior mostra contadores automáticos com:

* total de tarefas;
* tarefas aguardando;
* tarefas em andamento;
* tarefas concluídas.

Esses indicadores são atualizados conforme os filtros e ações realizadas.

### 7. Validação backend

Mesmo com interações no frontend, o sistema valida os dados no backend para garantir consistência e segurança.

### 8. Uso de variáveis de ambiente

As credenciais do banco não ficam expostas diretamente no código principal, sendo carregadas a partir de um arquivo `.env`.

---

## Segurança e Boas Práticas Aplicadas

Durante o desenvolvimento, foram adotadas boas práticas importantes:

* uso de **PDO** com prepared statements;
* separação entre configuração, models, frontend e estilo;
* validação de dados no backend;
* uso de arquivo `.env` para credenciais;
* retorno em JSON para requisições AJAX;
* organização do código visando manutenção futura.

---

## Melhorias Técnicas Já Realizadas

Ao longo da implementação, algumas evoluções importantes foram aplicadas:

* substituição de credenciais fixas por variáveis de ambiente;
* correção da exclusão para funcionar sem recarregar a página;
* correção do cadastro via AJAX para exibir a nova tarefa imediatamente;
* atualização automática dos indicadores do sistema;
* filtros funcionando de forma dinâmica no frontend;
* validações backend para evitar dados inválidos.

---

## Como Executar o Projeto

### Pré-requisitos

É necessário ter instalado:

* PHP;
* PostgreSQL;
* um servidor local, como XAMPP, WAMP, Laragon ou o servidor embutido do PHP;
* um navegador web.

### Passo a passo

1. Clone ou baixe o projeto.
2. Crie o banco de dados PostgreSQL.
3. Execute o script `database/schema.sql` para criar as tabelas.
4. Configure o arquivo `.env` com as credenciais corretas do banco.
5. Inicie o servidor PHP.
6. Acesse o sistema pelo navegador.

Exemplo genérico de configuração do `.env`:

```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=nome_do_banco
DB_USER=usuario
DB_PASS=senha
```

Exemplo genérico para subir o servidor embutido do PHP:

```bash
php -S localhost:8000
```

Depois, acessar no navegador:

```text
http://localhost:8000
```

---

## Possíveis Melhorias Futuras

O projeto já está funcional, mas pode evoluir com novas funcionalidades, como:

* edição sem reload via AJAX;
* paginação de tarefas;
* autenticação de usuários;
* níveis de acesso;
* ordenação por prazo ou prioridade;
* dashboard com métricas mais detalhadas;
* melhoria na experiência do usuário com animações e loaders;
* separação em camadas mais completas, como Controllers e Services.

---

## Aprendizados Obtidos no Projeto

Este projeto contribui para o aprendizado prático de diversos conceitos importantes, como:

* integração entre frontend e backend;
* manipulação do DOM com jQuery;
* uso de AJAX para operações assíncronas;
* comunicação com banco de dados relacional;
* organização de código em estrutura MVC simplificada;
* aplicação de boas práticas de validação e segurança.

---

## Considerações Finais

O **Task - Gerenciador de Tarefas** representa uma aplicação completa para fins acadêmicos e de aprendizado, reunindo conceitos essenciais do desenvolvimento web moderno em uma solução funcional e organizada.

Mesmo sendo um projeto em estágio inicial de evolução, ele já apresenta recursos importantes encontrados em sistemas reais, como CRUD completo, filtros dinâmicos, comunicação assíncrona e persistência em banco de dados.

O sistema também foi construído de forma a permitir expansão futura, tornando-o uma base sólida para novas implementações.

---

## Autor

**Mateus Ernandes da Cunha**

---

## Licença

Este projeto foi desenvolvido para fins acadêmicos e de aprendizado.
