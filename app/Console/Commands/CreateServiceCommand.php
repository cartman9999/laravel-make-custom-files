<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateServiceCommand extends GeneratorCommand
{
    /**
     * The name of your command. 
     * This replaces $signature.
     */
    protected $name = 'service:create'; 

    /**
     * Description of Commands purpose.
     */
    protected $description = 'Create a Service Class file';

    
    /**
     * Class type that is being created.
     */
    protected $type = 'Service'; 

    /**
     * Stub's location.
     */
    protected function getStub()
    {
        return  base_path() . '/stubs/service.stub';
    }

    /**
     * The root location where the file should be written to.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }
}
