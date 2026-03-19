# Exercicio 02 - PHP + MySQL

Exercício simples com aplicação PHP conectada ao MySQL em containers separados.

## Arquivos

```
exercicio02/
├── Dockerfile
├── app/
│   └── index.php
└── README.md
```

## Passo a passo

1. Criar volume:

```bash
docker volume create mysql-volume
```

2. Subir MySQL:

```bash
docker run --name mysql-lab02 \
  -e MYSQL_ROOT_PASSWORD=root_password \
  -e MYSQL_DATABASE=lab02_db \
  -e MYSQL_USER=lab02_user \
  -e MYSQL_PASSWORD=lab02_pass \
  -v mysql-volume:/var/lib/mysql \
  -d mysql:5.7
```

3. Build e run da aplicação:

```bash
cd exercicio02
docker build -t lab02-php .
docker run --name lab02-container -p 8081:80 --link mysql-lab02:mysql-lab02 -d lab02-php
```

Abra no navegador: `http://localhost:8081`

## Comandos rápidos

```bash
docker ps
docker logs lab02-container
docker logs mysql-lab02
docker stop lab02-container mysql-lab02
docker rm lab02-container mysql-lab02
```

## Observação

Os dados ficam no volume `mysql-volume`, então continuam mesmo após reiniciar containers.
