<?php

namespace Database\Factories;

use App\Models\Verification;
use App\Models\VerificationFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VerificationFile>
 */
class VerificationFileFactory extends Factory
{
    public function definition(): array
    {
        $fileType = fake()->randomElement([
            VerificationFile::TYPE_FRONT,
            VerificationFile::TYPE_BACK,
            VerificationFile::TYPE_SELFIE,
        ]);

        return [
            'verification_id' => Verification::factory(),
            'file_type' => $fileType,
            'disk' => 's3',
            'path' => 'verifications/'.fake()->uuid().'/'.$fileType.'.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => fake()->numberBetween(250_000, 4_000_000),
            'uploaded_at' => now(),
        ];
    }

    public function front(): static
    {
        return $this->state(fn (): array => ['file_type' => VerificationFile::TYPE_FRONT]);
    }

    public function back(): static
    {
        return $this->state(fn (): array => ['file_type' => VerificationFile::TYPE_BACK]);
    }

    public function selfie(): static
    {
        return $this->state(fn (): array => ['file_type' => VerificationFile::TYPE_SELFIE]);
    }
}
