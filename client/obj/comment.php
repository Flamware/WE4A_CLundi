<?php
class Comment
{
    public $comment_id;
    public $story_id;
    public $parent_comment_id;
    public $content;
    public $author;
    public $created_at;

    public function __construct($comment_id, $story_id, $parent_comment_id, $content, $author, $created_at)
    {
        $this->comment_id = $comment_id;
        $this->story_id = $story_id;
        $this->parent_comment_id = $parent_comment_id;
        $this->content = $content;
        $this->author = $author;
        $this->created_at = $created_at;
    }
}
?>

