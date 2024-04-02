    <?php
    // Define classes for Story and Comment
    class Story {
        public $id; // Define id property
        public $content;
        public $author;
        public $date;

        // Updated constructor to set id property
        public function __construct($id, $content, $author, $date) {
            $this->id = $id;
            $this->content = $content;
            $this->author = $author;
            $this->date = $date;
        }
    }
    class Comment {
        public $id;
        public $storyId;
        public $parentCommentId;
        public $content;
        public $author;
        public $date;
        public function __construct($id, $content, $author, $date, $storyId, $parentCommentId) {
            $this->id = $id;
            $this->content = $content;
            $this->author = $author;
            $this->date = $date;
            $this->storyId = $storyId;
            $this->parentCommentId = $parentCommentId;
        }
    }

    // Load stories from the server
    function submitStory($story) {
        // post request to the server
        $url = 'http://localhost/submit-story.php';
        $data = array('story' => $story);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        // Create a stream context
        $context = stream_context_create($options);
        // Make the request and get the response
        $result = file_get_contents($url, false, $context);
        //if the request failed show an error message
        if ($result === FALSE) {
            $error = 'Impossible de partager votre post, veuillez réessayer.';
            echo $error;
        }
    }


    // Load comments from the server
    function loadComments() {
        // get request to the server
        $url = 'http://localhost/load-comments.php';
        $data = array('action' => 'loadComments');
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        // Create a stream context
        $context = stream_context_create($options);
        // Make the request and get the response
        $result = file_get_contents($url, false, $context);
        //if the request failed show an error message
        if ($result === FALSE) {
            $error = 'Impossible de charger les commentaires, veuillez réessayer.';
            echo $error;
        }
        return json_decode($result);
    }

    // Get comments by story ID
    function getCommentsByStoryId($comments, $storyId) {
        $filteredComments = array_filter($comments, function($comment) use ($storyId) {
            return $comment->storyId == $storyId;
        });
        return array_values($filteredComments); // Reset array keys
    }

    // Handle story submission
    function loadStories() {
        // get request to the server
        $url = 'http://localhost/load-stories.php';
        $data = array('action' => 'loadStories');
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        // Create a stream context
        $context = stream_context_create($options);
        // Make the request and get the response
        $result = file_get_contents($url, false, $context);
        //if the request failed show an error message
        if ($result === FALSE) {
            $error = 'Impossible de charger les stories, veuillez réessayer.';
            echo $error;
        }
        return json_decode($result);
    }

    // Load stories and comments
    $stories = loadStories();
    $comments = loadComments();

    ?>

    <!-- HTML template -->
    <div>
        <header>
            <link rel="stylesheet" href="../css/main.css"> <!-- Link to styles.css -->
            <?php include '../component/header.php'; ?> <!-- Include header view -->
        </header>

        <section id="stories-container">
            <?php foreach ($stories as $story): ?>
                <div class="story">
                    <!-- Display story content -->
                    <p><?= $story->content ?></p>
                    <!-- Display comments -->
                    <?php $storyComments = getCommentsByStoryId($comments, $story->id); ?>
                    <?php foreach ($storyComments as $comment): ?>
                        <div class="comment">
                            <p><?= $comment->content ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>

        <section id="submit-story">
            <form action="" method="post">
                <label for="story">Votre post :</label>
                <textarea name="story" id="story" rows="4" required></textarea>
                <button type="submit">Partager</button>
            </form>
        </section>

        <footer>
        <?php include '../component/footer.php'; ?>
        </footer>
    </div>

    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $story = $_POST['story'];
        submitStory($story);
    }
    ?>
