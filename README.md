Site base dos debates públicos dentro do ambiente do Pensando o Direito
===================

Esse repositório contém os descritores para instalação do ambiente base das plataformas de debate público. A estrutura de publicação dos arquivos buscou seguir algumas sugestões do Bedrock Stack (http://roots.io/wordpress-stack/). Até onde é possível, usamos boas práticas pra publicação e organização dos códigos. Algumas tecnologias que estamos utilizando:

* Apache/PHP/MySQL
* WordPress
* Plugin Delibera
* Vagrant/VirtualBox
* Composer

Para começar, você deverá ter o VirtualBox e o Vagrant instalado na sua máquina. Não que isso seja um condicionante, você também poderá montar o seu ambiente "na mão", mas o Vagrant automatiza muito da montagem do ambiente para você.

Instale o Vagrant e antes de levantar a box, instale também o plugin triggers que permite executar um comando de sistema antes e depois de levantar a box. Isso porque foram disponibilizados dois comandos para direcionar a porta 80 para o caso de você não ter permissões para mapear portas baixas:

```
$ vagrant plugin install vagrant-triggers
```

Com o Vagrant e o plugin instalados, clone esse repositório na sua máquina local, e dentro do diretório que acaba de baixar, execute o comando "vagrant up". Isso fará com que o vagrant baixe a "box" que estamos utilizando no projeto e faça o provisionamento dela, ou seja, execute alguns comandos que estão no arquivo Vagrantfile, na raiz do respositório.

Como estamos usando o WordPress multisites, precisaremos ocupar a porta 80 padrão pra montagem da nossa plataforma. O Vagrant tentará levantar a plataforma nessa porta. Se você já tem um webserver ocupando essa porta, desative-o enquanto estiver trabalhando na plataforma de debates públicos.

O Vagrant também terá fazer um direcionamento de portas da 80 pra 8080, para o caso de falha do levantamento de portas baixas por conta de permissões. Para isso, ele pedirá sua senha de usuário pra fazer um "sudo".

Caso você não queira usar o Vagrant, e montará toda sua infra na mão, não tem problema. Use os scripts de config e db na raiz desse diretório para montar sua infra de servidor Apache e MySQL. Depois, execute o comando "composer update" para baixar todas as dependências de projeto necessárias. Se você não tem o composer instalado na sua máquina, baixe-o daqui (https://getcomposer.org/).

Com isso, se você acessar em seu navegador a url http://localhost, você já deve ver a tela iniciar da plataforma de debates.

<h4>Contribuindo com o código</h4>

Caso você queira fazer alguma contribuição, é recomendado você "forkar" o repositório no github. Para isso, você precisará ter um usuário nessa plataforma. Feito o "fork", você terá uma cópia do repositório na sua máquina com uma URL de origem diferente, por exemplo: http://github.com/marcoamarelo/marcocivil-tema. Com esse diretório, você deverá configurar a origin do repositório que você tem baixado na sua máquina para o seu "fork". Um exemplo de comando pra fazer isso é:

```
$ git remote set-url origin git@github.com:USERNAME/REPOSITORY2.git
```

Dessa forma, você poderá fazer todas as alterações necessárias no seu respositório e depois solicitar um pull request para o projeto.

Em caso de dúvidas ou contribuições, escreva para marco.konopacki@mj.gov.br
