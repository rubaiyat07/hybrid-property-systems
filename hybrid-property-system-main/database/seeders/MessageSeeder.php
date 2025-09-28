<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($users->count() > 1) {
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $users->first()->id,
                    'content' => 'Hello, this is a test message.',
                ]);
            }
        }
    }
}
