# aws-phishing-lab
Lab for test purposes, using a ec2 instance to a RDS database, simulating a a phishing attack. 

Observations:
This document will have general considerations, not minucious rules.
Put the files, like the php, and also the support links, but make it the most organizated and optimized as possible.
Indeed, this tutorial will serve for myself, when I need to remember to use the tecnologies

Create a EC2 t2.micro with AMI Linux image

save the .pem (I am using the moba for access, but you can utilize anyone tool for connect, like putty)

Create RDS instance

.............................

provisory notes

rds will be called: 'base'
public machine will be called: 'machine'

Key pair name também vou chamar machine

Security group name machine

escolher uma subnet publica

escolher a imagem
Amazon Linux 2 AMI (HVM) - Kernel 5.10, SSD Volume Type
pois a
Amazon Linux 2023 AMI
dá erro de Server refused our key
Disconnected: No supported authentication methods available (server sent: publickey,gssapi-keyex,gssapi-with-mic)

.............................

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

.............................


CRIAR BANCO DE DADOS

fonte:
https://docs.aws.amazon.com/pt_br/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateDBInstance.html

At principle, use tutorial above 
But in "6. Engine Options", select MariaDB instead MySQL.

adaptar o tutorial, deixar só o que precisa mesmo, pois tem muita coisa sobrando
adaptações:

Na seção 9.Settings (Configurações), defina estes valores:

DB instance identifier (Identificador da instância de banco de dados): digite 'base'.
Master username (Nome de usuário principal): digite admin.
Auto generate a password (Gerar uma senha automaticamente): desabilite a opção.
Master password (Senha principal): digite a senha admin123
Confirm password (Confirmar senha) – confirme a senha admin123.

At Item 14 -  Additional configuration, don't need to create the Sample database

moba connection:
Session
SSH
REmote Host: put the IP
username: ec2-user
Use Private Key: upload the .pem you generated
ok


CONECTAR INSTANCIA EC2 À BASE MARIADB

fonte:
https://docs.aws.amazon.com/pt_br/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html

sudo yum install -y httpd php php-mysqli mariadb

sudo systemctl start httpd

sudo systemctl enable httpd

usar os comandos da sessão "Para definir as permissões de arquivos para o servidor na web Apache"

no diretorio /var/www criar o diretorio connect (que é o que vai se conectar na base RDS)

mysql -h ENDPOINT -P 3306 -u admin -p
(in endpoint insert the endpoint, something like base.c1ucsq6uqdba.us-west-1.rds.amazonaws.com)

create database VICTIM_DATABASE;


put the connect file inside the ../connect folter
the content is something like this:


na rule da instancia, em inbound rules, liberar http anywhere ipv4

.............................

outra documentação auxiliar
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_ConnectToMariaDBInstance.html

.............................

INSERIR OS ARQUIVOS DE FRONT E CONEXÃO COM O BANCO


no diretorio /var/www/html inserir o arquivo index.php, que no caso agora é o loguin.php.


no index, no seguinte trecho colocar o nome do arquivo
...
<div>

<?php include "../connect/dbinfo.connect"; ?>

<?php
...

show databases;
use VICTIM_DATABASE;
show tables;
select * form VICTIM;

https://cloudkatha.com/how-to-install-apache-web-server-on-amazon-linux-2/
nesse link esse comando ajudou, quando minhas mudanças pareciam não surtir efeito no php:
sudo systemctl reload httpd.service (Force Apache Web Server to refresh configuration files)



.....

por fim, destruir os recursos, ou seja a base e a máquina criadas
e também os security groups da máquina e da RDS, vão ter nomes como launch-wizard-X e ec2-rds-X (pra deletar, tem que tirar as rules de cada securiy group, que apontam para outra maquina)
e eliminar também o key pair
