<?php

namespace AA\Services;

use AA\Library\Html\Parser;
use AA\Library\Http\HttpClient;
use AA\Library\Model\Url;
use AA\Library\SimpleHtml\ParserInterface;
use AA\Library\SimpleHtml\SimpleHtml;

class SiteStatsService
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var SimpleHtml
     */
    protected $htmlParser;

    /**
     * @var MetricsService
     */
    protected $metricService;

    /**
     * SiteStatsService constructor.
     *
     * @param HttpClient     $httpClient
     * @param SimpleHtml     $htmlParser
     * @param MetricsService $metricsService
     */
    public function __construct(HttpClient $httpClient, SimpleHtml $htmlParser, MetricsService $metricsService)
    {
        $this->httpClient = $httpClient;
        $this->htmlParser = $htmlParser;
        $this->metricService = $metricsService;
    }

    /**
     * @param $url
     *
     * @return \AA\Library\Metrics\MetricInterface[]
     */
    public function getSiteMetrics($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('Invalid URL');
        }

        // init site specific metrics
        $this->initSiteMetrics($url);

        $this->metricService->getMetric('page_load', $url)->start();
        $response = $this->httpClient->request('get', $url);
        $this->metricService->getMetric('page_load', $url)->stop();

        $this->metricService->getMetric('status_code', $url)->updateValue($response->getStatusCode());

        $parser = $this->htmlParser->parse($response->getBody());

        $this->loadLinkStats($parser, $url);
        $this->loadImageStats($parser, $url);
        $this->loadContentStats($response->getBody(), $url);

        return $this->metricService->getGroupMetrics($url);
    }

    /**
     * @param array $urls
     *
     * @return array
     */
    public function getMultipleSiteMetrics(array $urls)
    {
        $metrics = [
            'averge' => [
                $this->metricService->createMetric('averge', 'averge_word_count'),
                $this->metricService->createMetric('averge', 'averge_title_lenght'),
                $this->metricService->createMetric('averge', 'averge_page_load'),
            ],
            'site' => []
        ];

        foreach ($urls as $url) {
            $siteMetrics = $this->getSiteMetrics($url);

            $siteWordCount = $siteMetrics['word_count']->getValue();
            $siteTitleLenght = $siteMetrics['title_length']->getValue();
            $sitePageLoad = $siteMetrics['page_load']->getValue();

            $this->metricService->getMetric('averge_word_count')->updateValue($siteWordCount);
            $this->metricService->getMetric('averge_title_lenght')->updateValue($siteTitleLenght);
            $this->metricService->getMetric('averge_page_load')->updateValue($sitePageLoad);

            $metrics['site'][$url] = $siteMetrics;
        }

        return $metrics;
    }

    /**
     * @param ParserInterface $parser
     * @param                 $hostUrl
     */
    protected function loadLinkStats(ParserInterface $parser, $hostUrl)
    {
        $hostUrlModel = new Url($hostUrl);

        $urls = [
            'internal' => [],
            'external' => [],
        ];

        $links = $parser->find('a');

        foreach ($links as $link) {
            $url = $link->getAttribute('href');

            // relative links
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $url = $hostUrlModel->getScheme() . '://' . $hostUrlModel->getHost() . $url;
            }

            $url = new Url($url);

            if ($hostUrlModel->isSameHost($url->getUrl())) {
                array_push($urls['internal'], $url->getUrl());

                continue;
            }

            array_push($urls['external'], $url->getUrl());
        }

        $uniqeInternal = array_unique($urls['internal']);
        $uniqeExternal = array_unique($urls['external']);

        $this->metricService->getMetric('uniqe_internal_links', $hostUrl)->updateValue(count($uniqeInternal));
        $this->metricService->getMetric('uniqe_external_links', $hostUrl)->updateValue(count($uniqeExternal));
    }

    /**
     * @param ParserInterface $parser
     * @param                 $hostUrl
     */
    protected function loadImageStats(ParserInterface $parser, $hostUrl)
    {
        $hostUrlModel = new Url($hostUrl);

        $images = [];

        $links = $parser->find('img');

        foreach ($links as $img) {
            $url = $img->getAttribute('src');

            // relative links
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $url = $hostUrlModel->getScheme() . '://' . $hostUrlModel->getHost() . $url;
            }

            $url = new Url($url);

            array_push($images, $url->getUrl());
        }

        $uniqeImages = array_unique($images);
        $this->metricService->getMetric('uniqe_images', $hostUrl)->updateValue(count($uniqeImages));
    }

    /**
     * @param string $html
     * @param        $url
     */
    protected function loadContentStats(string $html, $url)
    {
        preg_match('|<title>(.*?)</title>|', $html, $matches);

        $title = $matches[1] ?? '';


        preg_match('/<body[ A-Za-z="\'_-]+>(.*?)<\/body>/Us', $html, $matches);
        // clean up inline js
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $matches[1]);
        // clean up inline styles
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $html);

        $text = trim(strip_tags($html));

        $this->metricService->getMetric('word_count', $url)->updateValue(str_word_count($text));
        $this->metricService->getMetric('title_length', $url)->updateValue(strlen($title));
    }

    /**
     * @param $url
     */
    protected function initSiteMetrics($url)
    {
        $this->metricService->createMetric('time', 'page_load', null, $url);
        $this->metricService->createMetric('count', 'uniqe_images', 0, $url);
        $this->metricService->createMetric('count', 'uniqe_internal_links', 0, $url);
        $this->metricService->createMetric('count', 'uniqe_external_links', 0, $url);
        $this->metricService->createMetric('count', 'word_count', 0, $url);
        $this->metricService->createMetric('count', 'title_length', 0, $url);
        $this->metricService->createMetric('value', 'status_code', 0, $url);
    }


}