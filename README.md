# aws-phishing-lab
Lab for test purposes, using a ec2 instance to a RDS database, simulating a a phishing attack. 

Observação: esse documento terá considerações gerais, não regras minuciosas. Colocar os arquivos, como php, e também os links de apoio, mas deixar o mais organizado e otimizado possível.
Esse manual inclusive servirá para mim também quando eu precisar lembrar.

Create a EC2 t2.micro with AMI Linux image

salvar o .pem (e informar que estou utilizando o moba para acesso, mas que pode utilizar qualquer um)

Create RDS instance



.............................
notas provisorias

rds sera chamado de base
máquina publica será chamada de machine

Key pair name também vou chamar machine

Security group name machine

escolher uma subnet publica

escolher a imagem
Amazon Linux 2 AMI (HVM) - Kernel 5.10, SSD Volume Type
pois a
Amazon Linux 2023 AMI
dá erro de Server refused our key
Disconnected: No supported authentication methods available (server sent: publickey,gssapi-keyex,gssapi-with-mic)

...........

configurações da instancia:

Instance type t2.micro

Criar new key pair 
type RSA 
format: .pem

deixar criar um 'launch-wizard-X, marcado em 
Allow SSH traffic from

O resto deixar Default

Launch instance

criar sessão
no moba

Session > SSH

Remote Host - colocar ip publico gerado pela AWS
Specify username - ec2-user

aba Advanced SSH setings:
marcar user private Key > e nesse campo puxar onde está a .pem
OK

....................


CRIAR BANCO DE DADOS

fonte:
https://docs.aws.amazon.com/pt_br/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateDBInstance.html

a principio, usar tutorial acima, mas selecionar MariaDB
adaptar o tutorial, deixar só o que precisa mesmo, pois tem muita coisa sobrando

no diretorio /var/www criar o diretorio connect (que é o que vai se conectar na base RDS)

no tutorial acima na sessão "Conectar o servidor Web Apache à instância de banco de dados"
pegar conteudo e colocar em um arquivo chamado dbinfo.connect (touch dbinfo.connect ou >dbinfo.connect) dentro de www/connect

o php é similar a isso:

<?php

define('DB_SERVER', 'db_instance_endpoint');
define('DB_USERNAME', 'tutorial_user');
define('DB_PASSWORD', 'master password');
define('DB_DATABASE', 'sample');

?>
                


CONECTAR INSTANCIA EC2 À BASE MARIADB

fonte:
https://docs.aws.amazon.com/pt_br/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html

sudo dnf install -y httpd php php-mysqli mariadb

sudo systemctl start httpd

sudo systemctl enable httpd

usar os comandos da sessão "Para definir as permissões de arquivos para o servidor na web Apache"

mysql -h ENDPOINT -P 3306 -u admin -p
(in endpoint insert the endpoint, something like base.c1ucsq6uqdba.us-west-1.rds.amazonaws.com)

create database NOME_DATABASE;

............
outra documentação auxiliar
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_ConnectToMariaDBInstance.html
...........

INSERIR OS ARQUIVOS DE FRONT E CONEXÃO COM O BANCO


no diretorio /var/www/html inserir o arquivo index.php, que no caso agora é o loguin.php.


no index, no seguinte trecho colocar o nome do arquivo
...
<div>

<?php include "../cadastro/dbinfo.connect"; ?>

<?php
...

show databases;
use NOME_DATABASE;
show tables;
select * form VICTIM;

https://cloudkatha.com/how-to-install-apache-web-server-on-amazon-linux-2/
nesse link esse comando ajudou, quando minhas mudanças pareciam não surtir efeito no php:
sudo systemctl reload httpd.service (Force Apache Web Server to refresh configuration files)
