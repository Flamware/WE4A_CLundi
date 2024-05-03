<?php
class Story {
    public $id;
    public $content;
    public $author;
    public $date;
    public $like_count;
    public $story_image;

    public function __construct($id, $content, $author, $date, $like_count = 0, $story_image = null) {
        $this->id = $id;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        $this->like_count = $like_count;
        $this->story_image = $story_image;
    }
}