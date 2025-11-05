# Black Braune - Sistema MVC PHP

Sistema de gestão do movimento Black Braune para revitalização comercial de Nova Friburgo.

## 🏗️ Estrutura do Projeto

```
black_braune/
├── app/
│   ├── controllers/        # Controllers do sistema
│   │   ├── HomeController.php
│   │   ├── DashboardController.php
│   │   └── ParceirosController.php
│   ├── views/             # Views (templates)
│   │   ├── layouts/       # Layouts base
│   │   │   ├── main.php   # Layout site principal
│   │   │   └── dashboard.php # Layout dashboard
│   │   ├── home/          # Views da home
│   │   ├── dashboard/     # Views do dashboard
│   │   └── parceiros/     # Views de parceiros
│   ├── models/           # Models (em desenvolvimento)
│   └── core/             # Classes core do sistema
│       ├── Router.php    # Sistema de roteamento
│       └── Controller.php # Controller base
├── config/
│   └── config.php        # Configurações do sistema
├── assets/               # Assets estáticos (CSS, JS, imagens)
├── public/              # Arquivos públicos
├── .htaccess           # Configuração Apache
└── index.php           # Arquivo principal
```

## 🚀 Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/salarini-e/blackbraune.git
cd blackbraune
```

2. **Configure o servidor web**
   - Aponte o DocumentRoot para a pasta do projeto
   - Certifique-se de que mod_rewrite está habilitado

3. **Configure as URLs**
   - Edite `config/config.php` e ajuste `BASE_URL` para sua URL local
   - Exemplo: `http://localhost/black_braune/`

## 🛣️ Rotas Disponíveis

### Site Principal
- `/` - Página inicial (index.html convertido)
- `/home` - Página inicial

### Dashboard Administrativo
- `/dashboard` - Dashboard principal
- `/parceiros` - Lista de parceiros
- `/parceiros/cadastro` - Formulário de cadastro
- `/parceiros/editar/{id}` - Formulário de edição
- `/parceiros/criar` - Processa cadastro (POST)
- `/parceiros/atualizar/{id}` - Processa edição (POST)
- `/parceiros/deletar/{id}` - Remove parceiro
- `/parceiros/visualizar/{id}` - Visualiza parceiro (JSON)

## 🏛️ Arquitetura MVC

### Controllers
- **HomeController**: Gerencia a página inicial
- **DashboardController**: Gerencia o dashboard administrativo
- **ParceirosController**: Gerencia CRUD de parceiros

### Views
- **Layouts**: Templates base para site e dashboard
- **Views específicas**: Templates para cada página

### Models
- Em desenvolvimento - será implementado posteriormente

## 🎨 Design System

O projeto mantém o design system original com:
- **Cores principais**: #FFC53A (amarelo), #000000 (preto), #E6A201 (dourado)
- **Fonte**: Poppins (Google Fonts)
- **Efeitos**: Glass morphism com backdrop-filter
- **Responsivo**: Mobile-first design

## 🔧 Funcionalidades Implementadas

### ✅ Sistema de Roteamento
- URLs amigáveis
- Parâmetros dinâmicos
- Redirecionamentos

### ✅ Controllers
- Controller base com métodos utilitários
- Gestão de views e layouts
- Flash messages para feedback

### ✅ Views
- Sistema de layouts
- Separação de conteúdo
- Variáveis dinâmicas

### ✅ Segurança
- Headers de segurança
- Sanitização de URLs
- Proteção de arquivos sensíveis

## 🔄 Próximos Passos

1. **Models**: Implementar camada de dados
2. **Database**: Configurar banco de dados
3. **Authentication**: Sistema de login
4. **API**: Endpoints para AJAX
5. **Validation**: Validação robusta de formulários

## 📝 Uso

### Desenvolvimento Local

1. Inicie um servidor local:
```bash
php -S localhost:8000
```

2. Acesse as URLs:
   - Site: `http://localhost:8000/`
   - Dashboard: `http://localhost:8000/dashboard`

### Adicionando Novas Páginas

1. **Criar Controller**:
```php
// app/controllers/NovoController.php
class NovoController extends Controller {
    public function index() {
        $this->viewWithLayout('novo/index', $data, 'main');
    }
}
```

2. **Criar View**:
```php
// app/views/novo/index.php
<h1>Nova Página</h1>
```

3. **Adicionar Rota** (opcional):
```php
// Em Router.php - método setupRoutes()
'nova-pagina' => ['controller' => 'NovoController', 'method' => 'index']
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob licença MIT. Veja o arquivo LICENSE para mais detalhes.