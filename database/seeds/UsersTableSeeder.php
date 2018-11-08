<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('App\User');

        //  Create Users
        for ($x = 1; $x <= 100; ++$x) {
            $gender = array('male', 'female')[$faker->numberBetween(0, 1)];
            DB::table('users')->insert([
                'first_name' => $faker->firstName($gender),
                'last_name' => $faker->lastName,
                'gender' => $gender,
                'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'bio' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'address' => $faker->address,
                'phone_ext' => $faker->numberBetween(260, 270),
                'phone_num' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'additional_email' => $faker->companyEmail,
                'username' => $faker->userName,
                'password' => '$2y$10$GUKtWT.VV7Iip6ZSDd2ac.QWtmSfCls0uMIApgtKdVk1o6hDIrCeu',
                'verified' => 1,
                'company_branch_id' => $faker->numberBetween(1, 1),
                'position' => $faker->jobTitle,
                'country' => $faker->country,
                'city' => $faker->city,
                'accessibility' => $faker->name,
                'avatar' => $faker->imageUrl($width = 100, $height = 100),
            ]);
        }

        //  Assign Staff Members
        for ($x = 1; $x <= 100; ++$x) {
            DB::table('user_directory')->insert([
                'user_id' => $x,
                'owning_branch_id' => $faker->numberBetween(1, 1),
                'owning_company_id' => $faker->numberBetween(1, 1),
                'type' => 'staff',
            ]);
        }

        //  Create Company
        for ($x = 1; $x <= 100; ++$x) {
            DB::table('companies')->insert([
                'name' => $faker->company,
                'description' => $faker->catchPhrase,
                'city' => $faker->city,
                'state_or_region' => $faker->state,
                'address' => $faker->address,
                'industry' => array('Software Development', 'Digital Marketing', 'Consulting', 'Advertising', 'Events')[$faker->numberBetween(0, 4)],
                'type' => array('Private', 'Parastatal', 'Government')[$faker->numberBetween(0, 2)],
                'website_link' => $faker->url,
                'profile_doc_url' => $faker->url,
                'phone_id' => $faker->numberBetween(1, 100),
                'email' => $faker->companyEmail,
                'logo_url' => $faker->imageUrl($width = 100, $height = 100),
            ]);
        }

        //  Assign Branches to companies
        for ($x = 1; $x <= 100; ++$x) {
            DB::table('company_branches')->insert([
                'name' => array('Headquaters', 'Main Branch')[$faker->numberBetween(0, 1)],
                'destination' => $faker->city,
                'company_id' => $x,
            ]);
        }

        //  Assign Clients to the first 5 Companies
        for ($x = 1; $x <= 50; ++$x) {
            $companyId = $faker->numberBetween(1, 5);

            DB::table('company_directory')->insert([
                'company_id' => $x,
                'owning_branch_id' => \App\Company::find($companyId)->branches()->first()->id,
                'owning_company_id' => $companyId,
                'type' => 'client',
            ]);
        }

        //  Assign Contractors to the first 5 Companies
        for ($x = 51; $x <= 100; ++$x) {
            $companyId = $faker->numberBetween(1, 5);

            DB::table('company_directory')->insert([
                'company_id' => $x,
                'owning_branch_id' => \App\Company::find($companyId)->branches()->first()->id,
                'owning_company_id' => $companyId,
                'type' => 'contractor',
            ]);
        }
    }
}
