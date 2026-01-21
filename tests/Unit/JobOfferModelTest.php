<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\JobOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class JobOfferModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uses_constants_for_statuses()
    {
        $this->assertEquals('draft', JobOffer::STATUS_DRAFT);
        $this->assertEquals('active', JobOffer::STATUS_ACTIVE);
        $this->assertEquals('paused', JobOffer::STATUS_PAUSED);
        $this->assertEquals('expired', JobOffer::STATUS_EXPIRED);
        $this->assertEquals('closed', JobOffer::STATUS_CLOSED);
    }

    /** @test */
    public function it_uses_constants_for_sources()
    {
        $this->assertEquals('internal', JobOffer::SOURCE_INTERNAL);
        $this->assertEquals('partner', JobOffer::SOURCE_PARTNER);
        $this->assertEquals('external', JobOffer::SOURCE_EXTERNAL);
    }

    /** @test */
    public function it_can_check_if_offer_can_be_applied_to()
    {
        $activeOffer = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => now()->addDays(7)
        ]);

        $expiredOffer = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => now()->subDays(1)
        ]);

        $draftOffer = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_DRAFT
        ]);

        $this->assertTrue($activeOffer->canBeAppliedTo());
        $this->assertFalse($expiredOffer->canBeAppliedTo());
        $this->assertFalse($draftOffer->canBeAppliedTo());
    }

    /** @test */
    public function it_can_check_if_offer_should_be_expired()
    {
        $shouldExpire = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => now()->subDays(1)
        ]);

        $shouldNotExpire = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => now()->addDays(1)
        ]);

        $this->assertTrue($shouldExpire->shouldBeExpired());
        $this->assertFalse($shouldNotExpire->shouldBeExpired());
    }

    /** @test */
    public function it_can_auto_expire_offers()
    {
        $offer = JobOffer::factory()->create([
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => now()->subDays(1)
        ]);

        $this->assertTrue($offer->autoExpireIfNeeded());
        $this->assertEquals(JobOffer::STATUS_EXPIRED, $offer->fresh()->status);
    }

    /** @test */
    public function it_provides_status_labels()
    {
        $offer = JobOffer::factory()->create(['status' => JobOffer::STATUS_ACTIVE]);
        $this->assertEquals('✅ Active', $offer->status_label);

        $offer->update(['status' => JobOffer::STATUS_DRAFT]);
        $this->assertEquals('📝 Brouillon', $offer->status_label);
    }

    /** @test */
    public function it_provides_source_labels()
    {
        $offer = JobOffer::factory()->create(['source' => JobOffer::SOURCE_INTERNAL]);
        $this->assertEquals('🏢 Interne', $offer->source_label);

        $offer->update(['source' => JobOffer::SOURCE_PARTNER]);
        $this->assertEquals('🤝 Partenaire', $offer->source_label);
    }

    /** @test */
    public function it_securely_handles_document_urls()
    {
        Storage::fake('public');
        
        // Test avec un fichier qui n'existe pas
        $offer = JobOffer::factory()->create(['document_appel_offre' => 'nonexistent.pdf']);
        $this->assertNull($offer->document_appel_offre_url);

        // Test avec un fichier qui existe
        Storage::disk('public')->put('documents/test.pdf', 'fake content');
        $offer->update(['document_appel_offre' => 'documents/test.pdf']);
        $this->assertNotNull($offer->document_appel_offre_url);
    }

    /** @test */
    public function it_validates_source_in_scope()
    {
        $this->expectException(\InvalidArgumentException::class);
        JobOffer::bySource('invalid_source')->get();
    }

    /** @test */
    public function it_validates_status_in_scope()
    {
        $this->expectException(\InvalidArgumentException::class);
        JobOffer::byStatus('invalid_status')->get();
    }

    /** @test */
    public function it_provides_validation_rules()
    {
        $rules = JobOffer::validationRules();
        
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('status', $rules);
        $this->assertArrayHasKey('source', $rules);
        $this->assertContains('required', explode('|', $rules['title']));
    }

    /** @test */
    public function it_provides_validation_messages()
    {
        $messages = JobOffer::validationMessages();
        
        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('status.in', $messages);
    }

    /** @test */
    public function it_provides_status_options()
    {
        $statuses = JobOffer::getStatuses();
        
        $this->assertArrayHasKey(JobOffer::STATUS_DRAFT, $statuses);
        $this->assertArrayHasKey(JobOffer::STATUS_ACTIVE, $statuses);
        $this->assertEquals('Brouillon', $statuses[JobOffer::STATUS_DRAFT]);
    }
}
