<?php


namespace AA\Controller;

use AA\Library\Phalcon\Http\JsonResponse;
use AA\Services\SiteStatsService;

/**
 * @RoutePrefix('/api/example')
 */
class ExampleController extends BaseController
{
    /**
     * @Get(
     *     '/fetch-single'
     * )
     */
    public function singleStatsAction()
    {
        $url = 'https://agencyanalytics.com/';

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
        $urls = [
            'https://agencyanalytics.com/features',
            'https://agencyanalytics.com/feature/seo-reporting-software',
            'https://agencyanalytics.com/integrations',
            'https://agencyanalytics.com/pricing',
            'https://agencyanalytics.com/test'
        ];

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
