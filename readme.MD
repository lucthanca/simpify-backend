# SimiCart Shopify Mobile Dashboard
## Installation
### Prerequisites
* nginx
* php7.4
* composer 1.x
* mariadb 10.3
### Step
* at root of project, run `composer install`, and wait for composer installation complete
* create a database
* after composer installation completely, then run command
```bash
  php bin/magento setup:install \
  --base-url="<base_url>" \
  --db-host="<db_host>" \
  --db-name="<db_name>" \
  --db-user="<db_username>" \
  --db-password="<db_password>" \
  --admin-firstname="<admin_firstname>" \
  --admin-lastname="<admin_lastname>" \
  --admin-email="<admin@email.com>" \
  --admin-user="<admin_username>" \
  --admin-password="<admin_password>" \
  --use-rewrites="1" \
  --backend-frontname="admin" \
  --use-secure=1 \
  --use-secure-admin=1
```
* after command run ok
* Go to <base_url>/admin
* navigate to `Store / Configurations / SimiCart / Simpify ` and config shopify api key and api secret
