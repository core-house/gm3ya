<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Association;
use App\Models\AssociationMember;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create Safe
        $safe = Safe::create([
            'name' => 'Main Safe',
            'balance' => 50000,
            'description' => 'Main safe for the association',
        ]);

        // Create Clients
        $clients = [];
        $clients[] = Client::create(['name' => 'Ahmed Mohamed', 'phone' => '01012345678', 'address' => 'Cairo', 'rate' => 5]);
        $clients[] = Client::create(['name' => 'Mohamed Ali', 'phone' => '01098765432', 'address' => 'Giza', 'rate' => 4]);
        $clients[] = Client::create(['name' => 'Ali Hassan', 'phone' => '01123456789', 'address' => 'Alexandria', 'rate' => 5]);
        $clients[] = Client::create(['name' => 'Hassan Abdullah', 'phone' => '01156789012', 'address' => 'Cairo', 'rate' => 3]);
        $clients[] = Client::create(['name' => 'Said Mahmoud', 'phone' => '01187654321', 'address' => 'Giza', 'rate' => 4]);

        // Create Association
        $association = Association::create([
            'name' => 'First Association',
            'monthly_amount' => 1000,
            'members_count' => 5,
            'total_amount' => 5000,
            'start_date' => now(),
            'status' => 'active',
        ]);

        // Add members to association
        foreach ($clients as $index => $client) {
            AssociationMember::create([
                'association_id' => $association->id,
                'client_id' => $client->id,
                'turn_number' => $index + 1,
                'due_date' => now()->addMonths($index),
                'collection_status' => 'pending',
            ]);
        }

        echo "Demo data created successfully!\n";
    }
}

