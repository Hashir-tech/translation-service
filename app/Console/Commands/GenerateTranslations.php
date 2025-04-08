<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;
use App\Models\Tag;

class GenerateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:generate {count=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        Tag::factory()->count(10)->create();
        Translation::factory()->count($count)->create()->each(function($translations) use($bar){
            $tags = Tag::inRandomOrder()->limit(rand(1, 3))->get();
            $translations->tags()->sync($tags);
            $bar->advance();
        });
        $bar->finish();
    }
}
