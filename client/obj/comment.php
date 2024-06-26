<?php
class Comment
{
    public $id;
    public $story_id;
    public $parent_comment_id;
    public $content;
    public $author;
    public $created_at;
    public $like_count;
    public $comment_image;

    public function __construct($id, $story_id, $parent_comment_id = null, $content, $author, $created_at, $like_count = 0, $comment_image = null)
    {
        $this->id = $id;
        $this->story_id = $story_id;
        $this->parent_comment_id = $parent_comment_id;
        $this->content = $content;
        $this->author = $author;
        $this->created_at = $created_at;
        $this->like_count = $like_count;
        $this->comment_image = $comment_image;
    }
}
?>

