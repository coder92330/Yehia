<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTourguide;
use App\Models\Tourguide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()->count(10)->create()->each(function ($event) {
            $event->orders()->saveMany(Order::factory()->count(1)->make())->each(function ($order) {
                $order->tourguides()->save(Tourguide::find(1), ['status' => 'approved', 'agent_status' => 'approved']);
            });
        });

        Event::factory()->count(100)->create()->each(function ($event) {
            $event->orders()->saveMany(Order::factory()->count(1)->make())->each(function ($order) {
                $order->tourguides()->save(Tourguide::find(1), ['status' => 'pending', 'agent_status' => 'pending']);
            });
        });
    }
}
