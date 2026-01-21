<?php

namespace Database\Factories;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    protected $model = JobOffer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement([
                JobOffer::TYPE_FULL_TIME,
                JobOffer::TYPE_PART_TIME,
                JobOffer::TYPE_CONTRACT,
                JobOffer::TYPE_INTERNSHIP
            ]),
            'location' => $this->faker->city(),
            'department' => $this->faker->word(),
            'source' => $this->faker->randomElement([
                JobOffer::SOURCE_INTERNAL,
                JobOffer::SOURCE_PARTNER,
                JobOffer::SOURCE_EXTERNAL
            ]),
            'partner_name' => $this->faker->optional()->company(),
            'status' => $this->faker->randomElement([
                JobOffer::STATUS_DRAFT,
                JobOffer::STATUS_ACTIVE,
                JobOffer::STATUS_PAUSED
            ]),
            'application_deadline' => $this->faker->optional()->dateTimeBetween('now', '+3 months'),
            'requirements' => $this->faker->randomElements([
                'Diplôme universitaire requis',
                'Expérience de 3 ans minimum',
                'Maîtrise de l\'anglais',
                'Compétences en informatique',
                'Capacité de travail en équipe'
            ], $this->faker->numberBetween(2, 4)),
            'criteria' => $this->faker->optional()->randomElements([
                'Formation spécialisée',
                'Certification professionnelle',
                'Expérience internationale'
            ], $this->faker->numberBetween(1, 3)),
            'benefits' => $this->faker->optional()->paragraph(),
            'salary_min' => $this->faker->optional()->numberBetween(30000, 60000),
            'salary_max' => $this->faker->optional()->numberBetween(60000, 100000),
            'salary_negotiable' => $this->faker->boolean(30),
            'positions_available' => $this->faker->numberBetween(1, 5),
            'contact_email' => $this->faker->safeEmail(),
            'contact_phone' => $this->faker->optional()->phoneNumber(),
            'is_featured' => $this->faker->boolean(20),
            'views_count' => $this->faker->numberBetween(0, 1000),
            'applications_count' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the job offer is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobOffer::STATUS_ACTIVE,
        ]);
    }

    /**
     * Indicate that the job offer is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobOffer::STATUS_DRAFT,
        ]);
    }

    /**
     * Indicate that the job offer is from a partner.
     */
    public function partner(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => JobOffer::SOURCE_PARTNER,
            'partner_name' => $this->faker->company(),
        ]);
    }

    /**
     * Indicate that the job offer is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the job offer is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobOffer::STATUS_ACTIVE,
            'application_deadline' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }
}
