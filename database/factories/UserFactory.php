<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countryIds = Country::pluck('_id')->toArray();
        $countryId = $this->faker->randomElement($countryIds);

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->uniqueEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'date_of_birth' => $this->faker->date(),
            'status' => 1,
            'address_1' => $this->faker->address,
            'address_2' => $this->faker->secondaryAddress,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'country_id' => $countryId instanceof ObjectId ? $countryId->__toString() : $countryId,

        ];
    }

    protected function uniqueEmail(): string
    {
        do {
            $email = $this->faker->unique()->safeEmail();
        } while (\App\Models\User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
