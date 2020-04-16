<?php

namespace App\Console\Commands;

use App\Brand;
use App\Category;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tests\Feature\coupon;

class CouponTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call("migrate:reset");
        Artisan::call("migrate");
        $this->addUser();
        $this->addCategory();
        $this->addBrands();
        $this->info('installed !');
    }

    public function addBrands()
    {
        $brand = [
            [
                "name" => "digikala", "description" => "digikala", "website" => "https://digikala.com", "category_id" => 1
            ], [
                "name" => "bamilo", "description" => "bamilo", "website" => "https://bamilo.com", "category_id" => 1
            ], [
                "name" => "tapci", "description" => "tapci", "website" => "https://tapci.com", "category_id" => 2
            ], [
                "name" => "snapFood", "description" => "snapFood", "website" => "https://snapFood.com", "category_id" => 3
            ], [
                "name" => "delino", "description" => "delino", "website" => "https://delino.com", "category_id" => 3
            ], [
                "name" => "aloPiak", "description" => "aloPiak", "website" => "https://aloPiak.com", "category_id" => 2]
        ];
        foreach ($brand as $item) {
            (new Brand($item))->save();
        }
        $this->info("Brands Add");
    }

    public function addCategory()
    {
        $category = [
            ["name" => "فروشگاه اینترنتی"],
            ["name" => "حمل نقل"],
            ["name" => "خدمات رفاهی"],
        ];
        foreach ($category as $item) {
            (new Category($item))->save();
        }
        $this->info("Category Add");
    }

    public function addUser()
    {
        $users = [
            [
                'name' => "admin",
                "type" => "admin",
                "email" => 'admin@site.com',
                'password' => 'password@admin'
            ],
            [
                'name' => "member",
                "type" => "member",
                "email" => 'member@site.com',
                'password' => 'password@member'
            ]
        ];
        foreach ($users as $user) {
            (new RegisterController())->create($user);
        }
        $this->info("admin login : admin@site.com | admin password : password@admin");
        $this->info("member login : member@site.com | member password : password@member");
    }
}
