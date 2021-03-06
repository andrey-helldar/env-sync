<?php

namespace Helldar\EnvSync\Services;

use Helldar\Support\Concerns\Makeable;
use Symfony\Component\Finder\Finder as SymfonyFinder;

final class Finder
{
    use Makeable;

    protected $exclude_dirs = ['vendor', 'node_modules', '.idea', '.git', '.github', 'tests'];

    protected $names = ['*.php', '*.json', '*.yml', '*.yaml', '*.twig'];

    protected $contains = ['env(', 'getenv('];

    protected $instance;

    protected $files = [];

    public function __construct(SymfonyFinder $finder)
    {
        $this->instance = $finder;
    }

    /**
     * @param  string|string[]  $path
     *
     * @return array
     */
    public function get($path): array
    {
        $this->search($path);

        return $this->files();
    }

    /**
     * @param  string|string[]  $path
     */
    protected function search($path): void
    {
        foreach ($this->find($path) as $file) {
            $this->push($file->getRealPath());
        }
    }

    /**
     * @param  string|string[]  $path
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function find($path): SymfonyFinder
    {
        return $this->instance->in($path)->files()
            ->exclude($this->exclude_dirs)
            ->name($this->names)
            ->contains($this->contains);
    }

    protected function push(string $path): void
    {
        $this->files[] = $path;
    }

    protected function files(): array
    {
        return $this->files;
    }
}
