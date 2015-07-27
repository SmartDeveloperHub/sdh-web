## Smart Developer Hub Web

# How to add dashboards
To add a new dashboard, create a new file inside "/resources/views/dashboards/" directory with ".blade.php" extension.
The "/resources/views/empty.blade.php" file explains how this file must be filled and can be used as a template.

# How to execute it

**Requirements (Windows & Mac)**

Vagrant installed

2GB Memory Ram

**Requirements (Linux)**

Docker

2GB Memory Ram

**Usage Windows**

0. Execute vagrant up

**Usage Linux**

0. Build image: docker build -t sdhub/sdh-web .
1. Run data container: docker run --name sdh-web-data --entrypoint /bin/echo sdhub/sdh-web Data-only container for sdh-web
3. Run app container: docker run -d -p 80:80 -p 443:443 -v /vagrant:/var/www/html -v /var/lib/mysql --volumes-from sdh-web-data sdhub/sdh-web

# Installed software
- Laravel: http://localhost:8080/ and https://localhost:4430/
- Phpmyadmin: http://localhost:8080/phpmyadmin and https://localhost:4430/phpmyadmin (database configuration is the given with .env.sample file, same password for root)

# Copyright
All rights reserved © 2015. Center Open Middleware