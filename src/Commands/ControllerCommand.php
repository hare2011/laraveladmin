<?php

namespace Runhare\Admin\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make empty admin controller';

    protected $model = null;
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->modelExists()) {
            $this->error('Model does not exists !');

            return false;
        }

        //$this->type = $this->parseName($this->getNameInput());

        parent::fire();
    }

    /**
     * Determine if the model is exists.
     *
     * @return bool
     */
    protected function modelExists()
    {
        $model = $this->ask('the controller model');

        $this->model = 'App\Model\\'.ucfirst($model);

        return class_exists($this->model);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        $DummyTitle = $this->ask('the page title');
        return str_replace(
            ['DummyModelNamespace', 'DummyModel','DummyTitle'],
            [$this->model, class_basename($this->model),$DummyTitle],
            $stub
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $directory = config('admin.directory');

        $namespace = ucfirst(basename($directory));
        return empty($namespace)?$rootNamespace."\Controllers":$rootNamespace."\\$namespace\Controllers";       
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', null, InputOption::VALUE_REQUIRED,
                'The eloquent model that should be use as controller data source.', ],
        ];
    }
}
