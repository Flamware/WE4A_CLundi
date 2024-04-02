<?php
class Comment {
    public $id;
    public $storyId;
    public $parentCommentId;
    public $content;
    public $author;
    public $date;

    public function __construct($id, $storyId, $parentCommentId, $content, $author, $date) {
        $this->id = $id;
        $this->storyId = $storyId;
        $this->parentCommentId = $parentCommentId;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
    }
}