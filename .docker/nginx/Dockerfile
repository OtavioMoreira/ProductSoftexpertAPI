# Use a imagem base do Nginx
FROM nginx:latest

# Instale o nano
RUN apt-get update && apt-get install -y nano

# Copie o arquivo de configuração do Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Defina o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html
