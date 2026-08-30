<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service
        {name : Nama Service, mis. PortfolioService}
        {--module= : Nama module/domain, mis. Portfolio}
        {--dto= : Nama DTO yang dipakai, mis. PortfolioData}
        {--model= : Nama Model Eloquent utama, mis. PortfolioItem (opsional)}';

    protected $description = 'Generate Service class di Domain Layer';

    public function handle()
    {
        $name   = Str::studly($this->argument('name'));
        $module = $this->normalizeModule($this->option('module') ?? 'Shared');
        $dto    = $this->option('dto') ? Str::studly($this->option('dto')) : null;
        $model  = $this->option('model') ? Str::studly($this->option('model')) : null;

        $dir  = app_path("Domain/{$module}/Services");
        $file = "{$dir}/{$name}.php";

        if (file_exists($file)) {
            $this->error("❌ Service {$name} sudah ada di Domain/{$module}/Services");
            return self::FAILURE;
        }

        File::ensureDirectoryExists($dir);

        $imports = [];
        if ($dto)   $imports[] = "use App\\Domain\\{$module}\\DTOs\\{$dto};";
        if ($model) $imports[] = "use App\\Models\\{$model};";
        $importsBlk = $imports ? implode("\n", $imports) . "\n\n" : '';

        $body = $this->defaultBody($dto, $model);

        $ns = "App\\Domain\\{$module}\\Services";
        $tpl = <<<PHP
        <?php

        namespace {$ns};

        {$importsBlk}class {$name}
        {
        {$body}
        }
        PHP;

        file_put_contents($file, preg_replace('/^[ \t]+$/m', '', trim($tpl)) . PHP_EOL);
        $this->info("✅ Service {$name} dibuat: Domain/{$module}/Services/{$name}.php");
        return self::SUCCESS;
    }

    private function defaultBody(?string $dto, ?string $model): string
    {
        $dtoType   = $dto   ? $dto   : 'mixed';
        $modelType = $model ? $model : 'mixed';

        $create = <<<PHP
            /** Contoh create */
            public function create({$dtoType} \$dto){$this->ret($modelType)}
            {
                // TODO: implementasi logika bisnis (slug unik, upload, normalisasi, dsb.)
                // return {$this->retReturn($modelType)};
            }
        PHP;

        $update = <<<PHP

            /** Contoh update */
            public function update({$modelType} \$entity, {$dtoType} \$dto){$this->ret($modelType)}
            {
                // TODO: implementasi update
                // return \$entity;
            }
        PHP;

        $delete = <<<PHP

            /** Contoh delete */
            public function delete({$modelType} \$entity): void
            {
                // TODO: hapus file terkait kalau perlu
                // \$entity->delete();
            }
        PHP;

        return $create . $update . $delete;
    }

    private function ret(string $type): string
    {
        return $type === 'mixed' ? '' : (": {$type}");
    }

    private function retReturn(string $type): string
    {
        return $type === 'mixed' ? '' : "new {$type}()";
    }

    private function normalizeModule(string $module): string
    {
        $module = str_replace(['/', '\\'], '\\', $module);
        $parts = array_map(fn($p) => Str::studly($p), array_filter(explode('\\', $module)));
        return implode('\\', $parts) ?: 'Shared';
    }
}
