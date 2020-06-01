<?php declare(strict_types=1);

namespace App\Commands;

use App\Models\Post;
use App\Models\User;
use App\ThirdParty\PostService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts from third-party app';

    /**
     * Execute the console command.
     *
     * @param PostService $postService
     *
     * @return void
     */
    public function handle(PostService $postService): void
    {
        $posts = $postService->getPosts();
        $bar = $this->output->createProgressBar(count($posts));

        foreach ($posts as $postData) {
            if (null === User::find($postData['user_id'])) {
                Log::error(sprintf('Post with id %s has not user id %s!', $postData['id'], $postData['user_id']));
                continue;
            }

            /** @var Post $post */
            $post = Post::find($postData['id']);

            if (null !== $post && !$post->update($postData)) {
                Log::error(sprintf('Post with id %d was not updated!', $post->id));
                continue;
            } elseif (!(new Post($postData))->save()) {
                Log::error(sprintf('Post with id %d was not saved!', $postData['id']));
                continue;
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
