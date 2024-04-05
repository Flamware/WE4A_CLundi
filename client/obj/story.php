<?php
class Story {
    public $story_id;
    public $content;
    public $author;
    public $date;

    public function __construct($id, $content, $author, $date) {
        $this->story_id = $id;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
    }
}