<?php

namespace Esign\QueryFilters\Console;

use Illuminate\Console\GeneratorCommand;

class FilterMakeCommand extends GeneratorCommand
{
    protected $name = 'make:filter';
    protected $description = 'Create a new filter class';
    protected $type = 'Filter';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/filter.stub');
    }

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Models\Filters';
    }
}
