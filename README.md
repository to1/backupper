# backupper
#### Back upping tool
### Notice the database backup currently only works on mysql databases 
Backupper is a command line based tool to back up you'r laravel project

```
composer require to1/backupper
```
will install the package to the project.

After the package was installed successfully you can use the these commands.

```
  php artisan to1:backup
 ```
  
  
This command will backup all the project folders/files except "vendor" which is a folder excluded by default.

There is a backupper config file with an array "exclude" it will not archive the folders that you specify on the array currently "vendor" folder is excluded by default, if you need to add folder that you do not want to backup just add them on the config array.


  
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

  
  
