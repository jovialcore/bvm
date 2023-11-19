<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
           
        //     'name' => 'Michael Patt ',
        //     'email' => 'random@example.com',
        //     'role' => 'admin',
        //     'password' => bcrypt('jovialcore'),
        // ]);

        Transaction::create([
            'amount' => 200,
            'payer' => 'chidiebere chukwudi',
            'due_on' => '2023-11-20 00:00:00',
            'paid_on' => '2023-11-19 17:18:18',
            'vat' => '3.5',
            'is_vat_inclusive' => true,
            'transaction_type' => 'full_payment',
            'status' => 'paid',
            'user_id' => '9aa69d1f-cb82-4b63-a2ef-fdb74f536277',
            'description' => 'This transaction is about the payment that you made that didin\'t go through'
        ]);
    }
}
