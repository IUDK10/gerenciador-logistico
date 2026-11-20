# Gerenciador Logístico - APO (Fundamentos de Programação para Internet)

Aplicação Web desenvolvida como parte da avaliação da Atividade Prática Orientada (APO) na UNIPAR EAD para a disciplina de Fundamentos de Programação para Internet.

## 🛠️ Tecnologias Utilizadas
- **Backend:** PHP 8
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Servidor Web Local:** XAMPP (Apache)

## 📦 Funcionalidades Implementadas
- **Cadastros Base (IDs Únicos):**
  - Cadastro de Produtos (Nome, Peso, Valor)
  - Cadastro de Endereços (Rua/Logradouro, Cidade, CEP - Origem e Destino)
  - Cadastro de Transportadores (Razão Social, CPF/CNPJ, Veículo/Placa)
- **Módulo de Emissão de Frete (Relacionamento 1 para N):**
  - Associação de 1 transportador responsável a múltiplos produtos selecionados no mesmo frete.
  - Vínculo direto com endereço de origem e endereço de destino.
- **Relatório Diário:**
  - Exportação automática em formato CSV contendo todos os fretes emitidos na data atual.

## 🚀 Como Executar o Projeto
1. Instale o **XAMPP** e inicie o serviço **Apache**.
2. Copie a pasta do projeto para o diretório `C:\xampp\htdocs\logistica`.
3. Abra o navegador e acesse: `http://localhost/logistica`.