<?php
namespace Triadev\Es\Console\Commands\Alias;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Illuminate\Console\Command;
use Log;

/**
 * Class Create
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Alias
 */
class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:alias:create
                            {index : Index}
                            {alias : Alias}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an alias.';

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
            "The alias is created: %s | %s | %s",
            $index,
            $alias,
            $version
        ));

        try {
            $scElasticsearchAlias->addAlias($index, $alias, $version);

            Log::info(sprintf(
                "The alias was created: %s | %s | %s",
                $index,
                $alias,
                $version
            ));
        } catch (AliasFoundException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error(sprintf(
                "The alias was not created: %s | %s | %s | %s",
                $index,
                $alias,
                $version,
                $e->getMessage()
            ));
        }
    }
}
