<?php

namespace Wotz\LivewireForms\Console;

use Illuminate\Console\GeneratorCommand;

class NewFormCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'form:new {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new laravel livewire form';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */

    // take parent handle to overwrite info messages
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $formName = $this->argument('name');

        $this->info("Form was created '{$this->getDefaultNamespace($this->rootNamespace())}\\{$formName}'🥳");

        $this->call('form:controller', ['name' => $formName]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/form.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Forms';
    }
}
