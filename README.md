Url Shortener
=============

Installation
-----------
Rename config.dist.php to config.php and edit as you like.
If You want to user SQLite, you have to rename db.dist.sqlite to db.sqlite.
If you use mysql write your mysql suer etc into the config.php and import db.sql.

For nginx, use this configuration:
```
location / {
    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php;
    }
}

```

Maybe you have to correct the RewriteBase in the .htaccess file.

Admin
-----
To access the administration panel, goto to http://example.com/admin/ (don't forget trailing slash!)
When you want to protect the admin panel from unauthorized access, comment out the last lines in the .htaccess and correct the path to your .htpasswd
