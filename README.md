# EletroTech

## 1. Visão Geral
EletroTech é um sistema web desenvolvido em PHP para gestão de eletricistas, produtos, metas e ordens de serviço. O sistema utiliza MySQL como banco de dados, HTML, CSS (com Bootstrap 5) e JavaScript puro para a interface. O ambiente de desenvolvimento é local, utilizando MAMP com Apache.

## 2. Estrutura de Pastas
- **banco/**: Scripts de conexão e manipulação do banco de dados.
- **Biblioteca-HTML-CSS/**: Componentes HTML e CSS reutilizáveis, incluindo botões, cards, headers, modais, tabelas e layouts.
- **Biblioteca-JS/**: Componentes e funções JavaScript reutilizáveis.
- **Biblioteca-PHP/**: Funções PHP utilitárias, autenticação, manipulação de banco, exceções e modelos.
- **Services/**: Serviços PHP responsáveis pelas ações de CRUD e regras de negócio dos módulos.
- **Styles/**: Arquivos CSS compartilhados e específicos de cada módulo.
- **Views/**: Telas do sistema separadas por módulo, com seus respectivos arquivos PHP, CSS e ícones.
- **index.php**: Página inicial do sistema.
- **logout.php**: Encerramento de sessão do usuário.

## 3. Banco de Dados

### 3.1 Tabelas

- **tabela_usuarios**: Usuários do sistema.
	- `id_user` (INT, PK): Identificador do usuário.
	- `usuario` (VARCHAR): Nome de login.
	- `senha` (VARCHAR): Senha criptografada.

- **tabela_eletricistas**: Cadastro de eletricistas.
	- `id_eletri` (INT, PK): Identificador do eletricista.
	- `cpf` (VARCHAR): CPF do eletricista.
	- `nome` (VARCHAR): Nome completo.
	- `data_contratacao` (DATE): Data de contratação.
	- `data_demissao` (DATE, NULL): Data de demissão (nulo se ativo).

- **tabela_produtos**: Produtos e materiais.
	- `id_prod` (INT, PK): Identificador do produto.
	- `nome` (VARCHAR): Nome do produto.
	- `vlr_unitario` (DECIMAL): Valor unitário.
	- `qtd_estoque` (INT): Quantidade em estoque.

- **tabela_metas**: Metas mensais por eletricista.
	- `id` (INT, PK): Identificador da meta.
	- `eletricista_meta` (INT, FK): Referência ao eletricista.
	- `mes_meta` (VARCHAR): Mês/ano da meta.
	- `vlr_meta` (DECIMAL): Valor da meta.

- **tabela_ordens_servico**: Ordens de serviço realizadas.
	- `id_ordServ` (INT, PK): Identificador da ordem.
	- `eletricista_os` (INT, FK): Eletricista responsável.
	- `data_os` (DATE): Data da ordem de serviço.

- **tabela_os_materiais**: Materiais utilizados em ordens de serviço.
	- `id_os` (INT, FK): Ordem de serviço.
	- `id_produto` (INT, FK): Produto utilizado.
	- `qtd_utilizada` (INT): Quantidade utilizada.

### 3.2 Views SQL

- **viewmetaseletri**
	- **Query**: Join entre `tabela_metas` e `tabela_eletricistas` para exibir metas associadas a cada eletricista.
	- **Propósito**: Facilitar consultas de metas por eletricista e mês.

- **view_materiais_ord**
	- **Query**: Join entre `tabela_ordens_servico`, `tabela_eletricistas`, `tabela_os_materiais` e `tabela_produtos` para detalhar materiais utilizados em cada ordem de serviço.
	- **Propósito**: Relatórios de consumo de materiais por ordem e eletricista.

## 4. Módulos do Sistema

### 4.1 Usuários

#### Arquivos
- **Services/loginService.php**: Autenticação de usuários.
- **Services/userEditService.php**: Edição de dados do usuário.
- **Services/userDeleteService.php**: Exclusão de usuários.
- **Views/view-login/loginView.php**: Tela de login.
- **Views/view-users/users.php**: Listagem e gerenciamento de usuários.

#### Regras de Negócio
1. Usuário deve autenticar para acessar o sistema.
2. CRUD completo: cadastro, edição, exclusão e listagem.
3. Senhas armazenadas de forma segura.

#### Endpoints (Formulários e Actions)
- **loginService.php**: POST (`usuario`, `senha`) — Valida credenciais e inicia sessão.
- **userEditService.php**: POST (`id_user`, campos editáveis) — Atualiza dados do usuário.
- **userDeleteService.php**: POST (`id_user`) — Remove usuário do sistema.

#### Problemas Conhecidos e Soluções Aplicadas
- Função PHP declarada dentro de loop causava erro fatal de redeclaração. **Solução:** Funções movidas para fora do loop.

### 4.2 Eletricistas

#### Arquivos
- **Services/eletricistaCadService.php**: Cadastro de eletricistas.
- **Services/eletricistaEditService.php**: Edição de eletricistas.
- **Services/eletricistaDeleteService.php**: Exclusão (demissão) de eletricistas.
- **Services/eletricistaAtivService.php**: Reativação de eletricistas.
- **Views/view-eletricista/eletricistaView.php**: Tela de gerenciamento de eletricistas.

#### Regras de Negócio
1. CRUD completo de eletricistas.
2. Controle de status ativo/demitido via data_demissao.
3. Não permitir duplicidade de CPF.

#### Endpoints (Formulários e Actions)
- **eletricistaCadService.php**: POST (`cpf`, `nome`, `data_contratacao`) — Cadastra novo eletricista.
- **eletricistaEditService.php**: POST (`id_eletri`, campos editáveis) — Edita dados do eletricista.
- **eletricistaDeleteService.php**: POST (`id_eletri`) — Marca eletricista como demitido.
- **eletricistaAtivService.php**: POST (`id_eletri`) — Reativa eletricista demitido.

#### Problemas Conhecidos e Soluções Aplicadas
- Tag script com src e conteúdo interno quebrava carregamento do JS. **Solução:** Separação correta entre scripts inline e externos.

### 4.3 Produtos

#### Arquivos
- **Services/produtosCadService.php**: Cadastro de produtos.
- **Services/produtosEditService.php**: Edição de produtos.
- **Services/produtosZerarService.php**: Zeragem de estoque.
- **Views/view-produtos/produtosView.php**: Tela de produtos.

#### Regras de Negócio
1. Cadastro, edição e listagem de produtos.
2. Listagem separada para produtos com e sem estoque.
3. Zeragem de estoque disponível.

#### Endpoints (Formulários e Actions)
- **produtosCadService.php**: POST (`nome`, `vlr_unitario`, `qtd_estoque`) — Cadastra produto.
- **produtosEditService.php**: POST (`id_prod`, campos editáveis) — Edita produto.
- **produtosZerarService.php**: POST (`id_prod`) — Zera estoque do produto.

#### Problemas Conhecidos e Soluções Aplicadas
- Classe CSS `icon` duplicada com valores diferentes causava tamanho incorreto. **Solução:** Unificação da declaração.
- Regra CSS global para `img` sobrescrevia ícones. **Solução:** Especificidade ajustada para não afetar ícones.

### 4.4 Metas

#### Arquivos
- **Services/metasCadService.php**: Cadastro de metas.
- **Services/metasEditService.php**: Edição de valor da meta.
- **Services/metasDeleteService.php**: Exclusão de metas.
- **Views/view-metas/metasView.php**: Tela de metas.

#### Regras de Negócio
1. CRUD completo de metas.
2. Unicidade: apenas uma meta por eletricista e mês.
3. Após cadastro, apenas o valor pode ser editado.
4. Filtro por eletricista e mês.

#### Endpoints (Formulários e Actions)
- **metasCadService.php**: POST (`eletricista_meta`, `mes_meta`, `vlr_meta`) — Cadastra meta.
- **metasEditService.php**: POST (`id_meta`, `vlr_meta`) — Edita valor da meta.
- **metasDeleteService.php**: POST (`id`) — Exclui meta.

#### Problemas Conhecidos e Soluções Aplicadas
- Filtro de metas não persistia seleção após redirect. **Solução:** Persistência implementada via session ou GET.

### 4.5 Ordens de Serviço

#### Arquivos
- **Services/ordensServicoCadService.php**: Cadastro de ordens de serviço.
- **Views/view-ordens-servico/ordensServicoView.php**: Tela de ordens de serviço.

#### Regras de Negócio
1. Apenas criação e listagem de ordens de serviço.
2. Não permite edição ou exclusão.
3. Débito automático de estoque via transação MySQL.
4. Seleção de múltiplos materiais por ordem.

#### Endpoints (Formulários e Actions)
- **ordensServicoCadService.php**: POST (`eletricista_os`, `data_os`, materiais utilizados) — Cadastra ordem de serviço e debita estoque.

#### Problemas Conhecidos e Soluções Aplicadas
- Modais não fechavam ao clicar fora por uso de `display: block`. **Solução:** Alterado para `display: flex`.
- Fatal error de PHP cortava HTML antes do script JS ser carregado, fazendo funções JS parecerem indefinidas. **Solução:** Correção da ordem de carregamento e tratamento de erros.

## 5. Padrões de Código Adotados

### 5.1 PHP
- Funções sempre declaradas fora de loops.
- Transações MySQL para operações que envolvem múltiplas queries.

### 5.2 JavaScript
- Funções centralizadas (ex: `closeAll`) para manipulação de modais e componentes.
- Separação entre scripts inline e externos.

### 5.3 CSS
- Uso de `display: flex` para modais.
- CSS centralizado em `Styles/styleComp.css` para componentes compartilhados.
- Evitar duplicidade de classes e regras globais que afetem componentes específicos.


## 6. Pendências e Melhorias Sugeridas
1. Adicionar logs de auditoria para ações críticas.
2. Melhorar tratamento de erros e mensagens ao usuário.
3. Implementar testes automatizados para os módulos principais.
4. Refatorar componentes JS para maior reutilização.
5. Adicionar exportação de relatórios em PDF/Excel.

