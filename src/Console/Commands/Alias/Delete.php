<?php
namespace Triadev\Es\Console\Commands\Alias;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Illuminate\Console\Command;
use Log;

/**
 * Class Delete
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Alias
 */
class Delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:alias:delete
                            {index : Index}
                            {alias : Alias}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an alias.';

    /**
     * Execute the console command.
     *
     * @param ScElasticsearchAliasContract $scElasticsearchAlias
     */
    public function handle(ScElasticsearchAliasContract $scElasticsearchAlias)
    {
        $index = $this->argument('index');
        $alias = $this->argument('alias');
        $version = $this->argument('version');

        Log::info(sprintf(
            "The alias is deleted: %s | %s | %s",
            $index,
            $alias,
            $version
        ));

        try {
            $scElasticsearchAlias->deleteAlias($index, $alias, $version);

            Log::info(sprintf(
                "The alias was deleted: %s | %s | %s",
                $index,
                $alias,
                $version
            ));
        } catch (AliasNotFoundException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error(sprintf(
                "The alias was not deleted: %s | %s | %s | %s",
                $index,
                $alias,
                $version,
                $e->getMessage()
            ));
        }
    }
}
