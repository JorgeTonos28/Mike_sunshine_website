<?php

namespace App\Console\Commands;

use App\Services\InstallLaunchContent as Installer;
use App\Support\LaunchContent;
use Illuminate\Console\Command;

class InstallLaunchContent extends Command
{
    protected $signature = 'studio:launch-install {--force : Aplicar el contenido autorizado en producción} {--dry-run : Mostrar el contenido previsto sin modificar la base}';

    protected $description = 'Publica las tres políticas y carga el contacto confirmado de Mike una sola vez';

    public function handle(Installer $installer): int
    {
        if (app()->isProduction() && ! $this->option('dry-run') && ! $this->option('force')) {
            $this->error('En producción usa --force; puedes revisar antes con --dry-run.');

            return self::FAILURE;
        }
        $applied = $installer->install((bool) $this->option('dry-run'));
        if (! $applied) {
            $this->info('Este lanzamiento ya está instalado. Se conservan las ediciones posteriores del panel.');

            return self::SUCCESS;
        }
        $this->table(['Dato', 'Contenido'], collect(LaunchContent::settings())->map(fn ($value, $key) => [$key, $value ?? '(sin enlace)'])->values()->all());
        $this->info($this->option('dry-run')
            ? 'Se publicarían Privacidad, Términos y Cookies y se cargarían estos contactos. No se ha guardado nada.'
            : 'Contacto cargado y tres páginas legales publicadas. La versión anterior queda respaldada en site_releases.');

        return self::SUCCESS;
    }
}
