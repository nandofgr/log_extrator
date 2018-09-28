Escolha da linguagem:

A princípio essa solução seria mais dinamica e menos verbosa se utilizado pyton, mas como o requisito proposto era a de usar a linguagem que na qual tenho mais afinidade e o tempo foi um pouco curto minha escolha foi o PHP.

Solução proposta neste código:

A solução porposta para o desenvolvimento foi a de procurar padrãoes no arquivo de log, detectar palavras chaves, remover o conteúdo que não era releante e minerar os dados através de expressões regulares.

Ambiente e configuração:

Foi desenvomvido e testado nos ambientes com PHP 5.3 e 7.1.14.
1 - Para rodar o servidor utilize o prompt de comando do seu OS para navegar at'a pasta onde se encontra o código fonte.
2 - Rode o servidor PHP através do comando php -S localhost:8000 (certifique-se de que tenha alguma versão do php instalada).
3 - Acesse a funcionalidade no navegador digitando o nome do arquivo em que se encontra o codigo fonte.

Considerações sobre modificações:

Como não ficou muito claro do que se trata a index 'kills', se para mortes do player ou quantdade que ele matou, tomei a liberdade de considerar 'kills' como a quantidade de mortes do player e adicionei uma função para calcular a quantidade que ele matou, essa index é 'killers'.