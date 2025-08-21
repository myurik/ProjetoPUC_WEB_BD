# Academia Esportiva — Fundamentos de Programação para Web (PUC-PR)

Sistema web simples para **gestão de uma academia** (membros/alunos e instrutores) com autenticação e painel administrativo. Projeto desenvolvido para a disciplina *Fundamentos de Programação para Web*.

> **Stack**: PHP 8 (PDO + sessões), MySQL/MariaDB, Bootstrap 5, JavaScript (ES6), HTML/CSS.  

---

## 📦 Estrutura do projeto
```
AcademiaEsportiva/
├── index.php                    # redireciona para pages/index.php
├── Diagrama_ER.pdf             # diagrama entidade‑relacionamento
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   └── custom.css
│   ├── img/                    # avatares (exibição no CRUD)
│   └── js/
│       ├── bootstrap.bundle.min.js
│       └── script.js
├── bd/
│   ├── bd_acad.sql             # criação das tabelas + dados exemplo
│   └── conectaBD.php           # credenciais do banco (PDO)
├── inc/
│   ├── autentica.php           # helpers de sessão/login
│   ├── header.php              # <head> + navbar base
│   └── footer.php              # scripts e rodapé
└── pages/
    ├── index.php               # login/cadastro + roteamento
    ├── home.php                # landing (visitante) + modais login/cadastro
    ├── dashboard.php           # painel após login
    ├── logout.php              # encerra sessão
    ├── sobre.php               # resumo do sistema
    ├── instrutores/
    │   ├── index.php           # listagem + filtros + modais
    │   └── form.php            # criar/editar
    └── membros/
        ├── index.php
        └── form.php
```

---

## 🗄️ Modelo de dados (resumo)

Tabelas principais (vide **`bd/bd_acad.sql`** e **`Diagrama_ER.pdf`**):

- **usuarios**: `id`, `nome` (único), `senha` (bcrypt), `criado_em`  
- **instrutores**: `id`, `nome`, `data_nascimento`, `especialidade`, `email` (único), `telefone`, `biografia`, `disponibilidade`, `foto` (BLOB), `criado_em`
- **membros**: `id`, `nome`, `data_nascimento`, `email` (único), `telefone`, `instrutor_id` (FK → `instrutores.id`, *ON DELETE SET NULL*), `status` (`Ativo`/`Inativo`), `endereco`, `emergencia_nome`, `emergencia_telefone`, `plano` (`Mensal`/`Trimestral`/`Anual`), `observacoes`, `foto` (BLOB), `data_inicio`, `criado_em`

O script **`bd_acad.sql`** já inclui **dados de exemplo** (instrutores, membros) e um **usuário `admin`** com senha *hash* (bcrypt). Caso não saiba a senha do `admin`, cadastre um novo usuário pela tela inicial (modal **Cadastro**).

---

## ▶️ Como executar localmente

### 1) Pré‑requisitos
- PHP 8+ (com extensão `pdo_mysql` habilitada)  
- MySQL 8+ ou MariaDB 10.x  
- Servidor web (Apache/Nginx) **ou** servidor embutido do PHP

### 2) Configuração do banco
1. Crie um banco (ex.: `academia`).  
2. Importe `bd/bd_acad.sql`.  
3. Ajuste `bd/conectaBD.php` com **host**, **database**, **usuário** e **senha** do seu ambiente.

### 3) Subir a aplicação
Com o servidor embutido do PHP, a partir da pasta **`AcademiaEsportiva/`**:
```bash
php -S localhost:8000
```
Acesse: **http://localhost:8000** (o `index.php` na raiz redireciona para `pages/index.php`).

> Em ambientes como XAMPP/WAMP, coloque a pasta dentro do *document root* (ex.: `htdocs/`) e acesse pelo navegador.

---

## 🔐 Autenticação
- Senhas armazenadas com `password_hash`/`password_verify` (bcrypt).  
- Controle de acesso por sessão em `inc/autentica.php` (`isLoggedIn()`, `requireLogin()`, `logout()`).
- Fluxo:
  1. **Visitante** vê `home.php` com os modais **Login** e **Cadastro**.
  2. Ao logar, é redirecionado para o **`dashboard.php`**.

---

## ✍️ Funcionalidades principais

### Instrutores
- CRUD completo (criar, editar, excluir, visualizar em modal);  
- Validação de **idade mínima de 18 anos**;  
- Upload de **foto** (armazenada em BLOB);  
- Filtros no front: **nome**, **especialidade**, **disponibilidade**.

### Membros
- CRUD completo;  
- Validação de **idade mínima de 15 anos**;  
- Atribuição a **instrutor** (FK);  
- Campos administrativos: **status**, **plano**, **data de início**, **contatos de emergência**, **observações**;  
- Upload de **foto** (BLOB);  
- Filtros no front: **nome**, **status**, **plano**, **instrutor**.

### UI/UX
- Layout responsivo com **Bootstrap 5** + **Bootstrap Icons / Font Awesome**;  
- `assets/js/script.js`: validação de formulários, exibição/ocultação de senha, *mask* de telefone, toasts, e filtros de tabela;  
- `assets/css/custom.css`: paleta, tipografia e pequenos refinamentos.

---

## 🧪 Dados de exemplo
O arquivo **`bd/bd_acad.sql`** cria registros de **instrutores** e **membros** para testes.  
> Dica: depois de importar, já é possível navegar no CRUD sem precisar cadastrar tudo manualmente.

---

## 🛠️ Dicas e solução de problemas

- **Erro de conexão PDO**: confira `bd/conectaBD.php` (host/usuário/senha/banco) e se a extensão `pdo_mysql` está ativa.  
- **Imagens não aparecem**: no CRUD as fotos são BLOBs. Certifique‑se de que o campo foi enviado (limite de upload do PHP) e de que o *form* está com `enctype="multipart/form-data"`.  
- **Senha do `admin`**: se não souber qual é a senha do usuário de exemplo, cadastre um novo usuário pelo modal **Cadastro** ou troque a senha no banco com `password_hash(...)`.
- **Timezone**: ajuste `date.timezone` no `php.ini` se desejar formatação local de datas/horas.

---

## 📚 Créditos
Projeto acadêmico de **Matheus Yuri Franco Miguel** – PUC‑PR.  

---

## 📄 Licença
Uso educacional/ acadêmico.
