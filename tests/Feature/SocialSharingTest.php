<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\User;
use App\Models\Service;
use App\Jobs\GenerateSocialImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SocialSharingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_displays_og_image_and_twitter_image_meta_tags_on_actualite_show_page()
    {
        // Arrange
        $service = Service::factory()->create(['nom' => 'Service Test']);
        $user = User::factory()->create(['name' => 'Auteur Test']);
        
        $actualite = Actualite::factory()->create([
            'titre' => 'Article de test pour partage social',
            'resume' => 'Ceci est un résumé de test pour les métadonnées sociales.',
            'texte' => 'Contenu complet de l\'article de test.',
            'image' => 'actualites/test-image.jpg',
            'is_published' => true,
            'published_at' => now(),
            'service_id' => $service->id,
            'user_id' => $user->id,
            'slug' => 'article-test-partage-social'
        ]);

        // Créer une image de test
        Storage::disk('public')->put('actualites/test-image.jpg', 'fake-image-content');

        // Act
        $response = $this->get(route('site.actualite.show', ['slug' => $actualite->slug]));

        // Assert
        $response->assertOk();
        
        // Vérifier les meta tags Open Graph
        $response->assertSee('<meta property="og:type" content="article">', false);
        $response->assertSee('<meta property="og:title" content="Article de test pour partage social">', false);
        $response->assertSee('<meta property="og:description"', false);
        $response->assertSee('<meta property="og:image"', false);
        $response->assertSee('<meta property="og:url"', false);
        
        // Vérifier les meta tags Twitter
        $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $response->assertSee('<meta name="twitter:title" content="Article de test pour partage social">', false);
        $response->assertSee('<meta name="twitter:image"', false);
        
        // Vérifier l'URL canonique
        $response->assertSee('<link rel="canonical"', false);
    }

    /** @test */
    public function it_uses_default_image_when_actualite_has_no_cover_image()
    {
        // Arrange
        $actualite = Actualite::factory()->create([
            'titre' => 'Article sans image',
            'resume' => 'Test sans image de couverture.',
            'image' => null,
            'is_published' => true,
            'published_at' => now(),
            'slug' => 'article-sans-image'
        ]);

        // Act
        $response = $this->get(route('site.actualite.show', ['slug' => $actualite->slug]));

        // Assert
        $response->assertOk();
        
        // Doit contenir l'image par défaut dans les meta tags
        $defaultImage = config('share.default_image');
        $response->assertSee($defaultImage, false);
    }

    /** @test */
    public function it_generates_absolute_urls_for_meta_tags()
    {
        // Arrange
        $actualite = Actualite::factory()->create([
            'titre' => 'Test URLs absolues',
            'image' => 'actualites/test-absolute.jpg',
            'is_published' => true,
            'published_at' => now(),
            'slug' => 'test-urls-absolues'
        ]);

        Storage::disk('public')->put('actualites/test-absolute.jpg', 'fake-content');

        // Act
        $response = $this->get(route('site.actualite.show', ['slug' => $actualite->slug]));

        // Assert
        $response->assertOk();
        
        // Les URLs dans les meta tags doivent être absolues
        $content = $response->getContent();
        
        // Vérifier que les URLs commencent par http://
        $this->assertStringContainsString('content="http', $content);
        $this->assertStringContainsString('href="http', $content);
        
        // Vérifier les patterns spécifiques
        preg_match('/property="og:url" content="([^"]+)"/', $content, $matches);
        $this->assertStringStartsWith('http', $matches[1] ?? '');
        
        preg_match('/property="og:image" content="([^"]+)"/', $content, $matches);
        $this->assertStringStartsWith('http', $matches[1] ?? '');
    }

    /** @test */
    public function it_includes_article_metadata_when_available()
    {
        // Arrange
        $service = Service::factory()->create(['nom' => 'Environnement']);
        $user = User::factory()->create(['name' => 'Jean Dupont']);
        
        $actualite = Actualite::factory()->create([
            'titre' => 'Article avec métadonnées complètes',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'published_at' => now(),
            'updated_at' => now()->addHour(),
            'is_published' => true,
            'slug' => 'article-metadonnees-completes'
        ]);

        // Act
        $response = $this->get(route('site.actualite.show', ['slug' => $actualite->slug]));

        // Assert
        $response->assertOk();
        
        // Vérifier les meta tags article
        $response->assertSee('property="article:published_time"', false);
        $response->assertSee('property="article:modified_time"', false);
        $response->assertSee('property="article:author" content="Jean Dupont"', false);
        $response->assertSee('property="article:section" content="Environnement"', false);
    }

    /** @test */
    public function it_queues_social_image_generation_job()
    {
        // Arrange
        Queue::fake();
        
        $actualite = Actualite::factory()->create([
            'titre' => 'Test job en queue'
        ]);

        // Act
        GenerateSocialImage::dispatch($actualite);

        // Assert
        Queue::assertPushed(GenerateSocialImage::class, function ($job) use ($actualite) {
            return $job->model->id === $actualite->id;
        });
    }

    /** @test */
    public function url_helper_converts_relative_paths_to_absolute_urls()
    {
        // Arrange
        $relativePath = 'images/test.jpg';

        // Act
        $absoluteUrl = \App\Support\UrlHelper::absolute($relativePath);

        // Assert
        $this->assertStringStartsWith('http', $absoluteUrl);
        $this->assertStringContainsString('images/test.jpg', $absoluteUrl);
    }

    /** @test */
    public function url_helper_handles_storage_urls_correctly()
    {
        // Arrange
        Storage::disk('public')->put('test/image.jpg', 'content');
        $storagePath = 'storage/test/image.jpg';

        // Act
        $absoluteUrl = \App\Support\UrlHelper::absolute($storagePath);

        // Assert
        $this->assertStringStartsWith('http', $absoluteUrl);
        $this->assertStringContainsString('test/image.jpg', $absoluteUrl);
    }

    /** @test */
    public function url_helper_returns_absolute_urls_unchanged()
    {
        // Arrange
        $absoluteUrl = 'https://example.com/image.jpg';

        // Act
        $result = \App\Support\UrlHelper::absolute($absoluteUrl);

        // Assert
        $this->assertEquals($absoluteUrl, $result);
    }

    /** @test */
    public function url_helper_generates_canonical_urls_for_actualites()
    {
        // Arrange
        $actualite = Actualite::factory()->create([
            'slug' => 'test-canonical-url'
        ]);

        // Act
        $canonicalUrl = \App\Support\UrlHelper::canonicalUrl($actualite);

        // Assert
        $this->assertStringStartsWith('http', $canonicalUrl);
        $this->assertStringContainsString('test-canonical-url', $canonicalUrl);
    }

    /** @test */
    public function social_configuration_has_correct_default_values()
    {
        // Act & Assert
        $this->assertEquals('images/default-share.jpg', config('share.default_image'));
        $this->assertEquals(1200, config('share.social_image.width'));
        $this->assertEquals(630, config('share.social_image.height'));
        $this->assertEquals(85, config('share.social_image.quality'));
        $this->assertEquals('jpg', config('share.social_image.format'));
    }

    /** @test */
    public function social_configuration_has_site_configuration()
    {
        // Act & Assert
        $this->assertNotNull(config('share.site.name'));
        $this->assertStringContainsString('Groupement des Ressources Naturelles', config('share.site.description'));
    }
}
