<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

/**
 * The teaser link of a home page article (no database needed)
 */
class ArticleTeaserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'https://cristinarutz.ch']);
    }

    private function teaser(array $attributes): string
    {
        return (new Article($attributes))->articleContent;
    }

    public function testOwnLinksStayInTheTab()
    {
        foreach (['/leistungen', 'https://cristinarutz.ch/leistungen', 'https://www.cristinarutz.ch/leistungen'] as $link)
        {
            $this->assertStringNotContainsString('target="_blank"', $this->teaser(['link' => $link]), $link);
        }
    }

    public function testOtherSitesOpenInANewTab()
    {
        $this->assertStringContainsString('target="_blank"', $this->teaser(['link' => 'https://www.baukulturhaus.ch']));
    }

    public function testBareMehrNamesItsTeaser()
    {
        $html = $this->teaser(['title' => 'Willkommen', 'link' => '/ueber-uns/team']);
        $this->assertStringContainsString('Mehr<span class="visually-hidden">: Willkommen</span>', $html);

        $html = $this->teaser(['title' => 'Willkommen', 'link' => '/ueber-uns/team', 'linkText' => 'Zum Team']);
        $this->assertStringContainsString('>Zum Team</a>', $html);
    }

    public function testLinkDescriptionIsReadButNotShown()
    {
        $html = $this->teaser(['link' => '/ueber-uns/team', 'linkDescription' => 'Unser Team & wir']);
        $this->assertStringContainsString('Mehr<span class="visually-hidden">: Unser Team &amp; wir</span></a>', $html);

        // It wins over the title
        $html = $this->teaser(['title' => 'Willkommen', 'link' => '/ueber-uns/team', 'linkDescription' => 'Zum Team']);
        $this->assertStringContainsString('Mehr<span class="visually-hidden">: Zum Team</span></a>', $html);
    }
}
