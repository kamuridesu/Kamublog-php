# Kamublog
[![CodeFactor](https://www.codefactor.io/repository/github/kamuri-chan/kamublog/badge)](https://www.codefactor.io/repository/github/kamuri-chan/kamublog)

A simple blog with admin panel in PHP with MaterializeCss and CKEditor.

It's not done yet, but if you want to use it anyway, clone this reposhitory, download [Materialize](https://materializecss.com/getting-started.html) and put on the / folder. Then, download [CKEditor 4](https://ckeditor.com/ckeditor-4/download/) and put it on /admin/posts.

To create the posts table, run the following SQL query: 
```sql
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
  `post_author` varchar(255) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_slug` varchar(255) NOT NULL UNIQUE,
  `post_image` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `post_name` varchar(255) NOT NULL,
  PRIMARY KEY(`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
And for the users table:
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

For the .htaccess file, this is working:
```RewriteEngine on
RewriteBase /Kamublog
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^post/([^/]+) functions/routing.php [NC]```
