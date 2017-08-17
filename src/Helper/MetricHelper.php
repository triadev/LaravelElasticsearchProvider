<?php
namespace Triadev\Es\Helper;

use Triadev\PrometheusExporter\Facade\PrometheusExporterFacade;

/**
 * Class MetricHelper
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\Es\Helper
 */
class MetricHelper
{
    /**
     * Get request start time
     *
     * @return float
     */
    public static function getRequestStartTime() : float
    {
        return microtime(true);
    }

    /**
     * Get request end time in milliseconds
     *
     * @param float $startTime
     * @return int
     */
    public static function getRequestEndTimeInMilliseconds(float $startTime) : int
    {
        return round((microtime(true) - $startTime) * 1000.0);
    }

    /**
     * Set request duration histogram
     *
     * @param int $durationInMilliseconds
     * @param string $handler
     */
    public static function setRequestDurationHistogram(int $durationInMilliseconds, string $handler)
    {
        if (env('APP_ENV') != 'testing' && php_sapi_name() !== 'cli') {
            PrometheusExporterFacade::setHistogram(
                'query_duration_milliseconds',
                'Get the request duration for an elasticsearch query.',
                $durationInMilliseconds,
                'elasticsearch',
                [
                    'handler'
                ],
                [
                    $handler
                ]
            );
        }
    }
}
