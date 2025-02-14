<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsors = array(

            array(
                'name' => 'British Council',
                'display_name' => 'British Council',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Inter-American Development Bank',
                'display_name' => 'Inter-American Development Bank',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Pricewater house Coopers',
                'display_name' => 'Pricewater house Coopers',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        );
        foreach ($sponsors as $sponsor){
            $createdSponspor = new Sponsor();
            $createdSponspor->name = $sponsor['name'];
            $createdSponspor->display_name = $sponsor['display_name'];
            $createdSponspor->created_at = Carbon::now();
            $createdSponspor->updated_at = Carbon::now();
            $createdSponspor->save();
            $createdSponspor->Programs()->sync([1,2,3,4,5]);

        }

        DB::table('sponsors')->insert($sponsors);
    }
}
