<?php declare(strict_types=1);

namespace App\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\ThirdParty\CommentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import comments from third-party app';

    /**
     * Execute the console command.
     *
     * @param CommentService $commentService
     *
     * @return void
     */
    public function handle(CommentService $commentService): void
    {
        $comments = $commentService->getComments();
        $bar = $this->output->createProgressBar(count($comments));

        foreach ($comments as $commentData) {
            if (null === Post::find($commentData['post_id'])) {
                Log::error(
                    sprintf(
                        'Comment with id %s has not post id %s!',
                        $commentData['id'],
                        $commentData['post_id']
                    )
                );
                continue;
            }

            /** @var Comment $comment */
            $comment = Comment::find($commentData['id']);

            if (null !== $comment && !$comment->update($commentData)) {
                Log::error(sprintf('Comment with id %d was not updated!', $comment->id));
                continue;
            } elseif (!(new Comment($commentData))->save()) {
                Log::error(sprintf('Comment with id %d was not saved!', $commentData['id']));
                continue;
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
