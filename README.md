# backupper
#### Back upping tool

Backupper is a command line based tool to back up you'r laravel project

Running this command will backup all you project except "vendor" which is a folder excluded by default

```
  php artisan to1:backup
 ```
  
  
  or you can specify a path to backup 
  
 ```
  php artisan to1:backup storage
```
  these commands will generate a zip file on the root of your project.
  
  if you want to backup your database as well you have to add the database option
  
  ```
  php artisan to1:backup --database=true
  
  ```
  This will generate a .gz file with you sql inside.\
  
  
  But you can also backup only your database
  
  ```
  php artisan to1:backup --database=only
  
  ```

  
  
