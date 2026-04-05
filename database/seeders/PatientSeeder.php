<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::create([
            'name' => 'Beteg1',
            'social_security_number' => '200000001',
            'birth_date' => '1993-02-10',
            'country' => 'Hungary',
            'city' => 'Budapest',
            'postal_code' => '1111',
            'street_address' => 'Fo utca 1.',
            'phone_number' => '+36301111111',
            'email' => 'patient1@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg2',
            'social_security_number' => '200000002',
            'birth_date' => '1988-07-24',
            'country' => 'Hungary',
            'city' => 'Szeged',
            'postal_code' => '6722',
            'street_address' => 'Kossuth Lajos sgt. 12.',
            'phone_number' => '+36302222222',
            'email' => 'patient2@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg3',
            'social_security_number' => '200000003',
            'birth_date' => '1997-11-05',
            'country' => 'Hungary',
            'city' => 'Debrecen',
            'postal_code' => '4025',
            'street_address' => 'Piac utca 8.',
            'phone_number' => '+36303333333',
            'email' => 'patient3@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg4',
            'social_security_number' => '200000004',
            'birth_date' => '1979-01-30',
            'country' => 'Hungary',
            'city' => 'Pecs',
            'postal_code' => '7621',
            'street_address' => 'Rakoczi ut 15.',
            'phone_number' => '+36304444444',
            'email' => 'patient4@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg5',
            'social_security_number' => '200000005',
            'birth_date' => '2001-05-18',
            'country' => 'Hungary',
            'city' => 'Gyor',
            'postal_code' => '9021',
            'street_address' => 'Arpad ut 3.',
            'phone_number' => '+36305555555',
            'email' => 'patient5@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg6',
            'social_security_number' => '200000006',
            'birth_date' => '1984-12-09',
            'country' => 'Hungary',
            'city' => 'Kecskemet',
            'postal_code' => '6000',
            'street_address' => 'Dozsa Gyorgy ut 22.',
            'phone_number' => '+36306666666',
            'email' => 'patient6@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg7',
            'social_security_number' => '200000007',
            'birth_date' => '1998-04-27',
            'country' => 'Hungary',
            'city' => 'Miskolc',
            'postal_code' => '3525',
            'street_address' => 'Szechenyi ut 40.',
            'phone_number' => '+36307777777',
            'email' => 'patient7@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg8',
            'social_security_number' => '200000008',
            'birth_date' => '1976-09-13',
            'country' => 'Hungary',
            'city' => 'Nyiregyhaza',
            'postal_code' => '4400',
            'street_address' => 'Bethlen Gabor ut 2.',
            'phone_number' => '+36308888888',
            'email' => 'patient8@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);
    }
}
