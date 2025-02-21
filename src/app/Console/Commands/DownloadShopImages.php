<?php

namespace App\Console\Commands;

use App\Models\Shop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class DownloadShopImages extends Command
{
    protected $signature = 'shops:download-images';
    protected $description = 'Download shop images from URLs and store locally';

    public function handle()
    {
        $directory = public_path('images/shops');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $shops = Shop::all();
        $bar = $this->output->createProgressBar(count($shops));

        $this->info('Downloading shop images...');
        $bar->start();

        foreach ($shops as $shop) {
            try {

                $originalUrl = $shop->image_url;

                $filename = 'shop_' . $shop->id . '_' . Str::slug($shop->name) . '.jpg';
                $filepath = 'images/shops/' . $filename;

                $response = Http::get($originalUrl);

                if ($response->successful()) {
                    // 画像を保存
                    file_put_contents(
                        public_path($filepath),
                        $response->body()
                    );


                    $shop->update([
                        'image_url' => $filepath
                    ]);

                    $this->line("\nSuccessfully downloaded image for: " . $shop->name);
                } else {
                    $this->error("\nFailed to download image for: " . $shop->name);
                }
            } catch (\Exception $e) {
                $this->error("\nError processing " . $shop->name . ": " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAll images have been processed!");
    }
}
