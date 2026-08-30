<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    protected $signature = 'make:domain
        {module : Nama module/domain, mis. Portfolio}
        {--dto= : Nama DTO, default: {Module}Data}
        {--fields= : Field DTO, mis. "title:string,summary:?string"}
        {--service= : Nama Service, default: {Module}Service}
        {--model= : Nama Model Eloquent, default: {Module}Item}
        {--with-model : Sekaligus generate model + migration (Laravel default)}';

    protected $description = 'Generate paket Domain (folder, DTO, Service, opsional Model + Migration)';

    public function handle()
    {
        $module  = $this->normalizeModule($this->argument('module'));
        $dto     = Str::studly($this->option('dto') ?: ($this->moduleBase($module) . 'Data'));
        $service = Str::studly($this->option('service') ?: ($this->moduleBase($module) . 'Service'));
        $model   = Str::studly($this->option('model') ?: ($this->moduleBase($module) . 'Item'));
        $fields  = $this->option('fields') ?? '';

        // Pastikan folder utama ada
        foreach (['DTOs', 'Services', 'Actions', 'Queries'] as $f) {
            File::ensureDirectoryExists(app_path("Domain/{$module}/{$f}"));
        }

        // DTO
        $this->callSilent('make:dto', [
            'name'      => $dto,
            '--module'  => $module,
            '--fields'  => $fields,
            '--from-request' => true,
        ]);

        // Service
        $this->callSilent('make:service', [
            'name'     => $service,
            '--module' => $module,
            '--dto'    => $dto,
            '--model'  => $model,
        ]);

        // Optional: Model + Migration
        if ($this->option('with-model')) {
            Artisan::call('make:model', [
                'name' => $model,
                '-m'   => true,
            ]);
            $this->line(Artisan::output());
        }

        $this->info("✅ Domain {$module} siap. DTO: {$dto}, Service: {$service}" . ($this->option('with-model') ? ", Model: {$model} (dengan migration)" : ''));
        return self::SUCCESS;
    }

    private function moduleBase(string $module): string
    {
        $parts = explode('\\', $module);
        return end($parts) ?: 'Domain';
    }

    private function normalizeModule(string $module): string
    {
        $module = str_replace(['/', '\\'], '\\', $module);
        $parts = array_map(fn($p) => Str::studly($p), array_filter(explode('\\', $module)));
        return implode('\\', $parts) ?: 'Shared';
    }
}
