<?php

namespace AA\Controller;

use AA\Library\Phalcon\Http\JsonResponse;
use AA\Services\SiteStatsService;
use Phalcon\Assets\Asset\Js;

/**
 * @RoutePrefix('/api/stats')
 */
class StatsController extends BaseController
{
    /**
     * @Post(
     *     '/fetch-single'
     * )
     */
    public function singleStatsAction()
    {
        $url = $this->request->getPost('url');

        if (!$url) {
            return new JsonResponse(['error' => 'No url provided']);
        }

        /** @var SiteStatsService $siteStats */
        $siteStats = $this->getDI()->getSiteStatsService();
        try {
            $metrics = $siteStats->getSiteMetrics($url);

            $stats = [];
            foreach ($metrics as $metric) {
                $stats = array_merge($stats, $metric->toArray());
            }

            return new JsonResponse($stats);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }

    /**
     * @Get(
     *     '/fetch-multi'
     * )
     */
    public function bulkStatsAction()
    {
        $urls = $this->request->getPost('urls');

        if (empty($urls)) {
            return new JsonResponse(['error' => 'No urls provided']);
        }

        if (count($urls) > 5) {
            return new JsonResponse(['error' => 'Maximum amount of urls reached']);
        }

        /** @var SiteStatsService $siteStats */
        $siteStats = $this->getDI()->getSiteStatsService();

        try {
            $metrics = $siteStats->getMultipleSiteMetrics($urls);

            $stats = [
                'averge' => [],
                'site'   => []
            ];

            foreach ($metrics['averge'] as $metric) {
                $stats['averge'] = array_merge($stats['averge'], $metric->toArray());
            }

            $siteStats = [];
            foreach ($metrics['site'] as $site => $siteMetrics) {
                $siteStats[$site] = [];
                foreach ($siteMetrics as $metricName => $metric) {
                    $siteStats[$site][$metricName] = $metric->getValue();
                }
            }

            $stats['site'] = $siteStats;

            return new JsonResponse($stats);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}