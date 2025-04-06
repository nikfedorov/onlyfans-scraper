<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Search extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:search';

    /**
     * The console command description.
     */
    protected $description = 'Perform a search for OnlyFans accounts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // get the query
        $query = text(
            label: 'Query',
            hint: 'Enter your search query',
            placeholder: 'e.g. model',
            required: true,
        );

        /** @var array<int, array<int, string>> $accounts */
        $accounts = Account::search($query)
            ->take(10)
            ->get()
            ->map(function (Account $account) {
                return [
                    (string) $account->username,
                    (string) $account->name,
                    (string) $account->likes,
                    (string) $account->bio_excerpt,
                ];
            })
            ->toArray();

        // show accounts as table
        table(
            headers: ['Username', 'Name', 'Likes', 'Bio'],
            rows: $accounts,
        );

        return Command::SUCCESS;
    }
}
