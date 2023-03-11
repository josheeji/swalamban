<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Modules\Grn\Models\Grn;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Contact;

class MailArticleCommentForwarded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $article,$article_comment;

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
        return $this->view('emails.article_comment_forward')
            ->subject('Article Comment is forwarded.')
            ->with([
                'article_comment' => $this->article_comment,
                'article'         => $this->article,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
