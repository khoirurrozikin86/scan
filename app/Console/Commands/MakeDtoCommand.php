<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDtoCommand extends Command
{
    protected $signature = 'make:dto 
        {name : Nama DTO, mis. PortfolioData}
        {--module= : Nama module/domain, mis. Portfolio}
        {--fields= : Daftar field, mis. "title:string,summary:?string,tags:?string,thumb:?file,order:?int"}
        {--from-request : Tambahkan method static fromRequest(array $data)}';

    protected $description = 'Generate Data Transfer Object (DTO) di Domain Layer';

    public function handle()
    {
        $name   = Str::studly($this->argument('name'));
        $module = $this->normalizeModule($this->option('module') ?? 'Shared');
        $fields = $this->parseFields($this->option('fields') ?? '');

        $dir  = app_path("Domain/{$module}/DTOs");
        $file = "{$dir}/{$name}.php";

        if (File::exists($file)) {
            $this->error("❌ DTO {$name} sudah ada di Domain/{$module}/DTOs");
            return self::FAILURE;
        }

        File::ensureDirectoryExists($dir);

        [$imports, $props, $ctor, $fromReq] = $this->buildParts($fields, (bool)$this->option('from-request'));

        $ns = "App\\Domain\\{$module}\\DTOs";
        $tpl = <<<PHP
        <?php

        namespace {$ns};

        {$imports}class {$name}
        {
            public function __construct(
        {$props}
            ) {}
        {$fromReq}}
        PHP;

        File::put($file, preg_replace('/^[ \t]+$/m', '', trim($tpl)) . PHP_EOL);
        $this->info("✅ DTO {$name} dibuat: Domain/{$module}/DTOs/{$name}.php");
        return self::SUCCESS;
    }

    private function normalizeModule(string $module): string
    {
        // dukung nested module: "Portfolio/Admin" => "Portfolio\\Admin"
        $module = str_replace(['/', '\\'], '\\', $module);
        $parts = array_map(fn($p) => Str::studly($p), array_filter(explode('\\', $module)));
        return implode('\\', $parts) ?: 'Shared';
    }

    private function parseFields(string $fieldsOpt): array
    {
        // hasil: [['name'=>'title','type'=>'string','nullable'=>false], ...]
        $map = [];
        if (trim($fieldsOpt) === '') return $map;

        foreach (explode(',', $fieldsOpt) as $chunk) {
            [$name, $type] = array_pad(explode(':', trim($chunk), 2), 2, '?string');
            $nullable = str_starts_with($type, '?');
            $type = ltrim($type, '?');

            // normalisasi tipe
            $type = match (strtolower($type)) {
                'int', 'integer'   => 'int',
                'bool', 'boolean'  => 'bool',
                'array'           => 'array',
                'file', 'uploadedfile', 'upload' => '\\Illuminate\\Http\\UploadedFile',
                'float', 'double'  => 'float',
                'string', 'text'   => 'string',
                'mixed'           => 'mixed',
                default           => $type ?: 'string',
            };

            $map[] = [
                'name'     => Str::snake($name),
                'type'     => $type,
                'nullable' => $nullable,
            ];
        }
        return $map;
    }

    private function buildParts(array $fields, bool $withFromRequest): array
    {
        $imports = [];
        foreach ($fields as $f) {
            if ($f['type'] === '\\Illuminate\\Http\\UploadedFile') {
                $imports['UploadedFile'] = "use Illuminate\\Http\\UploadedFile;";
            }
        }
        $importsBlock = implode("\n", $imports);
        if ($importsBlock !== '') $importsBlock .= "\n\n";

        $props = [];
        foreach ($fields as $f) {
            $type = $f['type'];
            $nullable = $f['nullable'];
            $typeDecl = ($nullable && $type !== 'mixed') ? "?{$type}" : $type;
            if ($type === 'mixed') $typeDecl = 'mixed';
            $props[] = "        public {$typeDecl} \${$f['name']}";
        }
        $propsBlock = implode(",\n", $props);

        $fromReqBlock = '';
        if ($withFromRequest) {
            $ctorMap = [];
            foreach ($fields as $f) {
                $key = $f['name'];
                $pref = "{$key}: ";
                if ($f['type'] === '\\Illuminate\\Http\\UploadedFile') {
                    $ctorMap[] = "                {$pref}(\$data['{$key}'] ?? null)";
                } else {
                    $ctorMap[] = "                {$pref}(\$data['{$key}'] ?? null)";
                }
            }
            $ctorLines = implode(",\n", $ctorMap);

            $fromReqBlock = <<<PHP

            public static function fromRequest(array \$data): self
            {
                return new self(
        {$ctorLines}
                );
            }
        PHP;
        }

        return [$importsBlock, $propsBlock, '', $fromReqBlock];
    }
}
