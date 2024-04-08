<?php
class Comment
{
    public $comment_id;
    public $story_id;
    public $parent_comment_id;
    public $content;
    public $author;
    public $created_at;
    public $like_count;

    public function __construct($comment_id, $story_id, $parent_comment_id, $content, $author, $created_at, $like_count = 0)
    {
        $this->comment_id = $comment_id;
        $this->story_id = $story_id;
        $this->parent_comment_id = $parent_comment_id;
        $this->content = $content;
        $this->author = $author;
        $this->created_at = $created_at;
        $this->like_count = $like_count;
    }
}
?>

