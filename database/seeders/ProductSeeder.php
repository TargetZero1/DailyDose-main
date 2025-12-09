<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name'=>'Chocolate Cranberry Mini Cake','category'=>'Cake','price'=>86000,'image'=>'img/ChocolateCranberryMiniCakes.jpg','stock'=>10],
            ['name'=>'Brownie Fudge Swirl Ice Cream','category'=>'Icecream','price'=>95000,'image'=>'img/browniefudgeswirlicecream.jpg','stock'=>10],
            ['name'=>'Puff Pastry Fruit Tarts','category'=>'Pastry','price'=>75000,'image'=>'img/puffpastryfruittarts.jpg','stock'=>10],
            ['name'=>'Mini Gluten Cheesecake','category'=>'Cake','price'=>70000,'image'=>'img/MinjGlutenCheeseCake.jpg','stock'=>10],
            ['name'=>'Greek Yoghurt Chocolate Pancakes','category'=>'Pancake','price'=>87000,'image'=>'img/greekyoghurtchocolatepancake.jpg','stock'=>10],
            ['name'=>'Chai Spiced Ice Cream Parfait','category'=>'Icecream','price'=>90000,'image'=>'img/chaispicedicecreamparfait.jpg','stock'=>10],
            ['name'=>'Raspberry Choux au Craquelin','category'=>'Pastry','price'=>83000,'image'=>'img/Raspberry Choux au Craquelin.jpg','stock'=>10],
            ['name'=>'Raspberry Pistacio Eton Mess','category'=>'Icecream','price'=>95000,'image'=>'img/Raspberry Pistacio Eton Mess.jpg','stock'=>10],
            ['name'=>'Classy Fluffy Pancakes','category'=>'Pancake','price'=>80000,'image'=>'img/classyfluffypancakes.jpg','stock'=>10],
            ['name'=>'No-Churn Oreo Ice Cream','category'=>'Icecream','price'=>10000,'image'=>'img/nochurnoreoicecream.jpg','stock'=>10],
            ['name'=>'Fluffy Japanese Pancakes','category'=>'Pancake','price'=>80000,'image'=>'img/Fluffy Japanese Pancakes.jpg','stock'=>10],
            ['name'=>'Ice Cream Cookie Sandwich','category'=>'Icecream','price'=>98000,'image'=>'img/icecreamcookiwsandwich.jpg','stock'=>10],
        ];

        foreach($items as $i){
            Product::updateOrCreate(
                ['name'=>$i['name']],
                [
                    'slug' => str_replace(' ', '-', strtolower($i['name'])),
                    'description' => $i['name'],
                    'price' => $i['price'],
                    'category' => $i['category'],
                    'image' => $i['image'],
                    'stock' => $i['stock'],
                ]
            );
        }
    }
}
