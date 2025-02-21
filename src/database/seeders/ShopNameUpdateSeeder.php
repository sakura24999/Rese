<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;

class ShopNameUpdateSeeder extends Seeder
{
    private $kanjiMapping = [
        '仙' => ['セン', 'せん'],
        '人' => ['ジン', 'じん'],
        '牛' => ['ギュウ', 'ぎゅう'],
        '助' => ['スケ', 'すけ'],
        '戦' => ['セン', 'せん'],
        '慄' => ['リツ', 'りつ'],
        '志' => ['シ', 'し'],
        '摩' => ['マ', 'ま'],
        '屋' => ['ヤ', 'や'],
        '鳥' => ['トリ', 'とり'],
        '雨' => ['ウ', 'う'],
        '築' => ['チク', 'ちく'],
        '地' => ['チ', 'ち'],
        '色' => ['イロ', 'いろ'],
        '合' => ['アイ', 'あい'],
        '晴' => ['ハル', 'はる'],
        '海' => ['ウミ', 'うみ'],
        '三' => ['サン', 'さん'],
        '子' => ['コ', 'こ'],
        '八' => ['ハチ', 'はち'],
        '戒' => ['カイ', 'かい'],
        '福' => ['フク', 'ふく'],
        '翔' => ['ショウ', 'しょう'],
        '経' => ['ケイ', 'けい'],
        '緯' => ['イ', 'い'],
        '漆' => ['ウルシ', 'うるし'],
        '木' => ['キ', 'き'],
        '船' => ['フネ', 'ふね'],
        '極' => ['キョク', 'きょく'],
        '北' => ['キタ', 'きた']
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Starting ShopNameUpdateSeeder');

        $shops = Shop::all();
        Log::info('Found shops:', ['count' => $shops->count()]);

        foreach ($shops as $shop) {
            $name = $shop->name;
            $name_kana = $name;
            $name_hira = $name;

            foreach ($this->kanjiMapping as $kanji => [$kata, $hira]) {
                $name_kana = str_replace($kanji, $kata, $name_kana);
                $name_hira = str_replace($kanji, $hira, $name_hira);
            }

            if (preg_match('/^[ァ-ヶー]+$/u', $name)) {
                $name_kana = $name;
                $name_hira = mb_convert_kana($name, 'HVc', 'UTF-8');
            } elseif (preg_match('/^[ぁ-んー]+$/u', $name)) {
                $name_kana = mb_convert_kana($name, 'KVC', 'UTF-8');
            }

            Log::info("Converting shop name", [
                'id' => $shop->id,
                'original' => $name,
                'before_kana' => $shop->name_kana,
                'before_hira' => $shop->name_hira,
                'after_kana' => $name_kana,
                'after_hira' => $name_hira
            ]);

            try {
                $updated = $shop->update([
                    'name_kana' => $name_kana,
                    'name_hira' => $name_hira
                ]);

                Log::info("Update result:", [
                    'id' => $shop->id,
                    'success' => $updated
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to update shop:", [
                    'id' => $shop->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $updatedShops = Shop::all();
        foreach ($updatedShops as $shop) {
            Log::info("Final shop data:", [
                'id' => $shop->id,
                'name' => $shop->name,
                'name_kana' => $shop->name_kana,
                'name_hira' => $shop->name_hira
            ]);
        }

        Log::info('Finished ShopNameUpdateSeeder');
    }
}
