<?php
class Story {
    public $id;
    public $content;
    public $author;
    public $date;
    public $like_count;

    public function __construct($id, $content, $author, $date, $like_count = 0) {
        $this->id = $id;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        $this->like_count = $like_count;
    }
}