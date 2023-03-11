<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Modules\Grn\Models\Grn;
use App\Models\User;
use App\Models\Article;
use App\Models\ArticleComment;

class MailArticleCommentReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $article,$article_comment;

    // protected $sender,$site_setting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        ArticleComment $article_comment,
        Article $article
    ){
        $this->article_comment = $article_comment;
        $this->article = $article;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.article_comment_received')
            ->subject('Article Comment is Received.')
            ->with([
                'article_comment' => $this->article_comment,
                'article' => $this->article,
                // 'receiver' => $this->grn->receivedBy,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
