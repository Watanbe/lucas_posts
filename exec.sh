sudo apt update -y

sudo apt install git -y
sudo apt install nginx -y
sudo apt install mysql-client -y
sudo apt install curl -y

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

sudo apt install composer -y

sudo systemctl enable nginx
sudo systemctl enable php-fpm


sudo chmod  o+w /var/www
cd /var/www/
git clone https://github.com/Watanbe/lucas_posts.git
cd lucas_posts

cp .env.example .env
