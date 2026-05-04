# Sistema de Gerenciamento de Hóspedes 

Projeto acadêmico desenvolvido durante o curso de Análise e Desenvolvimento de Sistemas.

## Sobre o Projeto

Este sistema foi desenvolvido para a disciplina de **Desenvolvimento de Sistemas Web** utilizando **PHP Nativo**. O objetivo é fornecer uma plataforma para hotéis ou pousadas gerenciarem o fluxo de hóspedes e o controle de suas estadias (hospedagens), permitindo o cadastro, consulta, atualização e exclusão de registros.

O foco da aplicação está na organização de dados dinâmicos e na persistência das informações em um servidor de banco de dados relacional.

## Principais Funcionalidades

* **Gestão de Hóspedes:** Cadastro detalhado com validação de CPF e data de nascimento.
* **Controle de Hospedagem:** Registro de informações de viagem, como país de origem, previsão de estadia e companhias aéreas utilizadas.
* **Consultas Dinâmicas:** Busca de hóspedes e estadias através do CPF com preenchimento automático de campos para edição.
* **CRUD Completo:** Interfaces dedicadas para listar, editar e excluir tanto hóspedes quanto registros de hospedagem.
* **Interface Responsiva:** Estilização moderna com temas escuros e transições suaves para melhor experiência do usuário.

## Tecnologias Utilizadas

* **Linguagem Back-end:** PHP (Nativo)
* **Front-end:** HTML5, CSS3, JavaScript
* **Banco de Dados:** MySQL 
* **Paradigma:** Desenvolvimento Web Estruturado com integração PDO.

## Licença

Este projeto está licenciado sob os termos da licença MIT.  
Consulte o arquivo [LICENSE](LICENSE.txt) para mais informações.

## Estrutura de Arquivos

O projeto está organizado para separar as responsabilidades de visualização e lógica:

* `index.php`: Painel principal com menu de navegação.
* `cadastroHospede.php` / `cadastroControle.php`: Formulários de inserção de dados.
* `attHospede.php` / `attHospedagem.php`: Telas de busca e atualização.
* `listarHospede.php` / `listarHospedagem.php`: Visualização em tabelas de todos os registros.
* `excluir.php`: Módulo de remoção de hóspedes e vínculos.
* `css/`: Pasta contendo toda a identidade visual do sistema (estilos específicos para cada módulo).
* `js/mensagem.js`: Scripts para interações de interface, como o desaparecimento automático de alertas.

## Configuração do Ambiente

Para rodar este projeto localmente, siga estes passos:

1.  Instale um servidor local (XAMPP, WAMP ou Laragon).
2.  Inicie os serviços do **Apache** e **MySQL**.
3.  Crie um banco de dados no seu `phpMyAdmin` chamado `bdhotel`.
4.  Importe o arquivo `bdhotel.sql` que se encontra na raiz do projeto para criar a estrutura de tabelas necessária.
5.  Clone ou copie a pasta do projeto para o diretório público do seu servidor (ex: `htdocs` ou `www`).
6.  Acesse no navegador: `http://localhost/nome-da-sua-pasta/index.php`.

## Autor

* **Vinícios Weide Ebling** - [vinicioswe2005@gmail.com](mailto:vinicioswe2005@gmail.com)
