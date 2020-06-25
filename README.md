<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# Creating Customizable Stub Classes Using Artisan Commands

Laravel is an amazing web application framework with an elegant syntaxis, althogh Laravel allows us to create multiple classes (Controllers, Models, Events, Migrations, Seeders, etc) using Artisan commands sometimes we need to repeatedly create files that don´t have an Artisan command.

The Artisan console's make commands use "stub" files to generate the class files, stub are files that are populated with values based on your input. This repo is intented to show you how to create new customizable stub class files usign Artisan Commands. As an example I'll be creating a Service Class but you can create anything you want.

## Requirements
- A Laravel Project

## Process
### 1) Creating artisan command
Our first step is to create the artisan command that will allow us to create of class file. Run in console:

````
$ php artisan make:command NameOfYourCommand
`````

For this example I'll be teaching you how to create Service Class files. So in my case, my command shall be named CreateServiceCommand.

````
$ php artisan make:command CreateServiceCommand
`````

This will create a directory called Commands inside app/Console. Move to app/Console/Commands and open your command class. You should be seeing something like this:

````
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
````

This pice of code is the standard Laravel's code to create Artisan's commands, we'll come back to this file in a moment, now its time to create our custmizable stub file.

### 2) Creating Stub File
### 2.1) Creating stubs folder
#### Laravel 7
If you are using Laravel 7 you might be aware of a new feature called Stub Customization, if not, this feature is intented to help you save developing time by adding those required code lines in a template called stub. So if you modify these files, anytime you use the Artisan's make command you will get a file with the lines you have added.

In order to create the stubs folder, we'll need to execute the next command:
```
php artisan stub:publish
```
By doing so the stubs folder will be created in you project's base path. You can skip to instruction 2.2, see you there!

#### Laravel 6 or Below
If you are using Laravel 6 or a version below we'll need to manually create the stubs folder, I recommend to create the folder inside your project's base path but you can create it anywhere you please. Once you created the folder let's move to 2.2 step.

### 2.2) Creating Customizable Stub
Create a stub file inside your stub folder. **Stub files require a .stub extension**. In my case I'll be creating:
```` 
service.stub
````

Inside your stub file you'll place the desired templeate you are trying to accomplish. This will be my stub file's content:

`````
<?php

namespace {{ namespace }};

class {{ class }} 
{
    public function __construct() {

    }
}
`````

The things you'll be replacing must be inside {{  }}. Thus far we only have a stub file an a useless Artisan command, it's time to change that.

### 3) Adding Functionality To Artisan's Command
Go back to your Artisan's command file and delete the class content, we won't be needing it. You file we'll be looking something like this:
`````
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateServiceCommand extends Command
{
}

`````
Our Artisan's Command needs to capable of customizing stubs files and Command doesn't allow us to, so it necessary to replace it by extending **GeneratorCommand** instead:
````
<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateServiceCommand extends GeneratorCommand
{
}
````

Now let's add the methods that will make our Artisan's command work.
````
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateServiceCommand extends GeneratorCommand
{
    /**
     * The name of your command. 
     * This is how your Artisan's command shall be invoked.
     */
    protected $name = 'service:create'; 

    /**
     * A short description of the command's purpose.
     * You can see this working by executing
     * php artisan list
     */
    protected $description = 'Create a Service Class file';

    
    /**
     * Class type that is being created.
     * If command is executed successfully you'll receive a
     * message like this: $type created succesfully.
     * If the file you are trying to create already 
     * exists, you'll receive a message
     * like this: $type already exists!
     */
    protected $type = 'Service'; 

    /**
     * Specify your Stub's location.
     */
    protected function getStub()
    {
        return  base_path() . '/stubs/service.stub';
    }

    /**
     * The root location where your new file should 
     * be written to.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }
}
````

Let's see how the description of our Artisan's command looks, execute:
`````
php artisan help service:create
``````
You will get something like this:
`````
Description:
  Create a Service Class file

Usage:
  service:create <name>

Arguments:
  name                  The name of the class
`````

As you can see our command requires a parameter _name_, this is the name our new file shall take. Now it's time to create our first Artisan command created file.
`````
php artisan service:create MyServiceClass
`````
Sure enough a file was created into _app/Services_ with the exact format specified in the stub file! I really hope this is helpful to you, so you can save valuable time from creating repeatitive files and put hands on coding! 