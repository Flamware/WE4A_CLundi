<?php
class Story {
    public $id;
    public $content;
    public $author;
    public $date;

    public function __construct($id, $content, $author, $date) {
        $this->id = $id;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
    }
}