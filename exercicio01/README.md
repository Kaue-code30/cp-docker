# Exercicio 01 - NGINX + HTML

Exercício simples para rodar uma página estática com Docker.

## Arquivos

```
exercicio01/
├── Dockerfile
├── index.html
└── README.md
```

## Passo a passo

```bash
cd exercicio01
docker build -t lab01-web .
docker run --name lab01-container -p 8080:80 -v "${PWD}/index.html:/usr/share/nginx/html/index.html" -d lab01-web
```

Abra no navegador: `http://localhost:8080`

## Comandos rápidos

```bash
docker ps
docker logs lab01-container
docker stop lab01-container
docker rm lab01-container
```

## Observação

O `index.html` é montado via bind mount, então você pode editar o arquivo sem reconstruir a imagem.
