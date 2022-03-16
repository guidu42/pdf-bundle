=======

# symfony-starter-v5
Drosalys-symfony-starter-v5 is a starter for Symfony 5. This project set up a generic structure that Drosalys usually work with.
Asset/Controller/Template are all separated between 3 Folder : 
```
Shared : Everything common for the back and the front
Front : Only stuff for Front
Back : Only stuff For Back
```

### Dev environnement needed :

```bash
    PHP 8.0^'
    node 14^'
```

### Installation process :


```
- composer install
- yarn
```

```
Configure your Database in a .env.local file :
DATABASE_URL="mysql://root@127.0.0.1:3306/starter_drosalys_v5?serverVersion=5.7"
```

Then create it with this command : 
```
symfony console d:d:c
```

Then execute migrations with this command :
```
symfony console d:m:m
```

### Changes :
This started start with 2 layout, One for the front : 
```
base-front.html.twig
```
A second for the back: 
```
base-back.html.twig
// Your user need to have ROLE_ADMIN to access the back's dashboard.
```

You can set ROLE_ADMIN to user just by executing this command :
```
symfony console app:set-admin exemple@gmail.com
```
A KnpMenu is already generated for the Back, you can find it in : Src/Menu/MenuBuilder.php

Changes have been made with the make:entity command, Now the generated Repository will have a new function : 
```
    public function getQbAll(): QueryBuilder
    {
        return $this->createQueryBuilder('cp');
    }
```
The biggest change is all about the command make:crud, when make:crud is used, it will generate for you the usually symfony Crud but with our new standard styles.
It will also generate for you all the traductions key needed and a working standard pagination for all your list.
```
After the execution of the command, your work is now to :
- Move the generate Controller into the Back Folder of Controllers (don't forget to change the namespace)
- Move the genrate folder template into the Back folder of template
- Change rendering route in your Controller, and template calls in your twig files.
- Add your translation in our traduction files (currently : translations/messages.fr.yaml)
- Do the stuff you want with the generated Form.
```

Some Entities are also generated :
```
The standard User Entity, Everything for register/login/logout is alredy set up.
```

```
CustomPage Entity, Offert the possibilty for an Admin to create Custom Pages for his Website.
- The back setup is already done for this entity.
```

The GDPR modules (cookies) is also already configurated for the starter.


This starter also comes with some modules : 
```
PHP :
        "drosalys-web/object-extensions": "^1.2",
        "friendsofsymfony/ckeditor-bundle": "^2.3",
        "knplabs/knp-menu": "^3.2",
        "knplabs/knp-menu-bundle": "^3.1",
        "knplabs/knp-paginator-bundle": "^5.6",
        
JS : 
        "sass": "^1.39.2",
        "sass-loader": "^12.0.0",
        "ts-loader": "^9.0.0",
        "typescript": "^4.4.2",
        "@fortawesome/fontawesome-pro": "^5.15.4",
        "@popperjs/core": "^2.10.1",
        "bootstrap": "^5.1.1",
        "ckeditor": "^4.12.1",
        "tiny-slider": "^2.9.3"
```

### Coming in V2 :
- Move generated Crud files directly in Back Folder.

