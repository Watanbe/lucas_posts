sudo apt update -y

sudo apt install git -y
sudo apt install nginx -y
sudo apt install mysql-client -y
sudo apt install curl -y

sudo add-apt-repository ppa:ondrej/php

sudo apt install php -y
sudo apt install php-intl -y
sudo apt install php-ctype -y
sudo apt install php-curl -y
sudo apt install php-dom -y
sudo apt install php-fileinfo -y
sudo apt install php-mbstring -y
sudo apt install php-opcache -y
sudo apt install php-pdo -y
sudo apt install php-tokenizer -y
sudo apt install php-xml -y
sudo apt install php-zip -y
sudo apt install php-fpm -y
sudo apt install php-mysql -y
sudo apt install unzip p7zip -y

sudo apt install php8.3 php8.3-intl php8.3-curl php8.3-mbstring php8.3-xml php8.3-zip php8.3-fpm php8.3-mysql unzip p7zip-full -y


sudo apt install composer -y

sudo systemctl enable nginx
sudo systemctl enable php-fpm


sudo chmod  o+w /var/www
cd /var/www/
git clone https://github.com/Watanbe/lucas_posts.git
cd lucas_posts

cp .env.example .env

sudo openssl req -x509 -nodes -newkey rsa:4096 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt -sha256 -days 365
