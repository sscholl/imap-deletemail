# imap-deletemail
Delete old emails from multiple IMAP accounts

## Setup
Copy and add configuration to file config.php
````
cp config.php.dist config.php
vim config.php
crontab -e
````

crontab:
``
0 6 * * *          php /path/to/delete.php
``
