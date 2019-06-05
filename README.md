# backupper
#### Back upping tool
### Notice the database backup currently only works on mysql databases 
Backupper is a command line based tool to back up you'r laravel project

```
composer require to1/backupper
```
will install the package to the project.

After the package was installed successfully you can use the these commands.

This command will backup all the project folders/files except "vendor" which is a folder excluded by default

```
  php artisan to1:backup
 ```
  
  
  or you can specify a path to backup 
  
 ```
  php artisan to1:backup storage
```
  these commands will generate a zip file on the root of the project.
  
  If you want to backup your database as well you have to add the database option
  
  ```
  php artisan to1:backup --database=true
  
  ```
  This will generate a .gz file with you sql inside.
  
  
  But you can also backup only your database
  
  ```
  php artisan to1:backup --database=only
  
  ```

  
  
