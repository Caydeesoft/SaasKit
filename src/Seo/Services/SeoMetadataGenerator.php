<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Seo\Services;

use InvalidArgumentException;
use Illuminate\Support\Str;
use Caydeesoft\SaasKit\Seo\Contracts\SeoMetadataGeneratorInterface;
use Caydeesoft\SaasKit\Seo\DTOs\SeoMetadataData;

final class SeoMetadataGenerator implements SeoMetadataGeneratorInterface
{
    public function generate(SeoMetadataData $data): array
    {
        $siteName = $this->clean($data->siteName ?: (string) config('saas-kit.seo.site_name', 'SaaS Kit'));
        $title = $this->title($data->title, $siteName);
        $description = Str::limit($this->clean($data->description), 160, '...');
        $canonical = $this->canonicalUrl($data->url);
        $robots = $this->clean($data->robots ?: (string) config('saas-kit.seo.robots', 'index,follow'));
        $keywords = array_slice(array_values(array_unique($data->keywords)), 0, 12);
        $image = $data->image ? $this->canonicalUrl($data->image) : null;

        $metadata = [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'meta' => [
                ['name' => 'description', 'content' => $description],
                ['name' => 'robots', 'content' => $robots],
            ],
            'open_graph' => [
                ['property' => 'og:type', 'content' => $this->clean($data->type ?: 'website')],
                ['property' => 'og:title', 'content' => $title],
                ['property' => 'og:description', 'content' => $description],
                ['property' => 'og:url', 'content' => $canonical],
                ['property' => 'og:site_name', 'content' => $siteName],
            ],
            'twitter' => [
                ['name' => 'twitter:card', 'content' => (string) config('saas-kit.seo.twitter_card', 'summary_large_image')],
                ['name' => 'twitter:title', 'content' => $title],
                ['name' => 'twitter:description', 'content' => $description],
            ],
            'json_ld' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $title,
                'description' => $description,
                'url' => $canonical,
            ],
        ];

        if ($keywords !== []) {
            $metadata['keywords'] = $keywords;
            $metadata['meta'][] = ['name' => 'keywords', 'content' => implode(', ', $keywords)];
        }

        if ($image !== null) {
            $metadata['image'] = $image;
            $metadata['open_graph'][] = ['property' => 'og:image', 'content' => $image];
            $metadata['twitter'][] = ['name' => 'twitter:image', 'content' => $image];
            $metadata['json_ld']['image'] = $image;
        }

        return $metadata;
    }

    private function title(string $title, string $siteName): string
    {
        $cleanTitle = Str::limit($this->clean($title), 60, '...');
        $suffix = $this->clean((string) config('saas-kit.seo.title_suffix', $siteName));

        if ($suffix === '' || str_contains($cleanTitle, $suffix)) {
            return $cleanTitle;
        }

        return Str::limit($cleanTitle . ' | ' . $suffix, 60, '...');
    }

    private function canonicalUrl(string $url): string
    {
        $cleanUrl = $this->clean($url);

        if (filter_var($cleanUrl, FILTER_VALIDATE_URL) !== false) {
            return $cleanUrl;
        }

        $baseUrl = rtrim((string) config('app.url', ''), '/');

        if ($baseUrl !== '' && str_starts_with($cleanUrl, '/')) {
            return $baseUrl . $cleanUrl;
        }

        throw new InvalidArgumentException('SEO URLs must be absolute URLs or root-relative paths with app.url configured.');
    }

    private function clean(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($value)) ?? '');
    }
}
