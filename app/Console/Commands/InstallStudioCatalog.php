<?php

namespace App\Console\Commands;

use App\Services\InstallArtworkCatalog;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class InstallStudioCatalog extends Command
{
    protected $signature = 'studio:catalog-install {--dry-run : Revisar las obras que se añadirían sin guardar cambios} {--force : Permitir la instalación en producción}';

    protected $description = 'Instala las pinturas y esculturas versionadas sin modificar obras existentes';

    public function handle(InstallArtworkCatalog $installer): int
    {
        $dryRun = (bool) $this->option('dry-run');
        if (app()->isProduction() && ! $dryRun && ! $this->option('force')) {
            $this->error('En producción usa studio:catalog-install --force. Para revisar sin guardar, usa --dry-run.');

            return self::FAILURE;
        }
        try {
            $result = $installer->install($dryRun);
        } catch (ValidationException $exception) {
            $this->error('El catálogo versionado no cumple el formato esperado.');
            foreach ($exception->errors() as $field => $messages) {
                $this->line($field.': '.implode(' ', $messages));
            }

            return self::FAILURE;
        } catch (QueryException) {
            $this->error('No se pudo guardar el catálogo. Comprueba las migraciones y la conexión a la base de datos. La operación se ha revertido.');

            return self::FAILURE;
        } catch (\RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
        $this->table(['Resultado', 'Cantidad'], [
            [$dryRun ? 'Obras que se añadirían' : 'Obras añadidas', $result['created']],
            ['Pinturas nuevas', $result['paintings']],
            ['Esculturas nuevas', $result['sculptures']],
            ['Obras existentes conservadas', $result['skipped']],
        ]);
        $this->info($dryRun ? 'Revisión terminada: no se han guardado cambios.' : 'Catálogo instalado. Puedes revisarlo en /pinturas, /esculturas y en el panel.');

        return self::SUCCESS;
    }
}
