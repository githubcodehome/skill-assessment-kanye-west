<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuoteService
{
    public function get(int $count = 5, int $total = 0, int $chunk = 20, array $data = [], int $level = 8): array
    {
        $chunk = min($count, $chunk);
        [$c, $result] = $this->getResult($count, $data, $total, $chunk, $level);
        $result = Arr::where($result, fn($value, $key) => $key < $c);
        sort($result);
        return $result;
    }

    /**
     * @param mixed $chunk
     */
    protected function getResult(int $count, array $data, int $total, int $chunk, int $level): array
    {
        $url = config('app.api_kanye_url');
        $quotes = $this->getQuotes($chunk, $url, $count);
        $quotes = array_unique(array_merge($quotes, $data));
        $c = $total > 0 ? $total : $count;
        if (count($quotes) < $c && $level > 0) {
            $quotes = $this->get(
                count: $c - count($quotes),
                total: $c,
                data: $quotes,
                level: $level - 1
            );
        }
        $result = array_values(array_unique($quotes));
        return [$c, $result];
    }

    protected function getQuotes(int $chunk, string $url, int $count): array
    {
        $quotes = [];
        try {
            for ($page = 0; $page < (int)($count / $chunk); $page++) {
                $response = Http::retry(3, 100)->pool(function (Pool $pool) use ($chunk, $url) {
                    for ($i = 0; $i < $chunk; $i++) {
                        $pool->get($url);
                    }
                });

                foreach ($response as $item) {
                    if (!method_exists($item, 'ok')) {
                        continue;
                    }
                    if ($item->ok()) {
                        $quotes[] = $item->json()['quote'] ?? null;
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::error('Request error: ', (array)$exception);
            throw new ('Request error: ' . $exception->getMessage());
        }

        return $quotes;
    }

    public function addFavorite(string $text): void
    {
        $data = ['text' => $text, 'user_id' => Auth::id()];
        Favorite::firstOrCreate($data, $data);
    }

    public function getFavorites()
    {
        return Favorite::where('user_id', Auth::id())->orderBy('id', 'desc')->get(['id', 'text', 'user_id']);
    }

    public function deleteFavorite(Favorite $favorite): void
    {
        Favorite::where('id', $favorite->id)->delete();
    }
}
