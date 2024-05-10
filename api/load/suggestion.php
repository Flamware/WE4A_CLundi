    <?php
    /**
     * Load stories
     * Method: GET
     * Parameters: page, most_liked, most_commented, tags
     * Source : Estouan Gachelin
     *
     * This file loads stories from the database
     * It returns the stories in a JSON format
     * The stories are ordered based on the type of request
     * The stories can be filtered by tags
     */

    session_start();
    include '../db_connexion.php'; // Database connection
    global $conn;

    // Function to fetch paginated stories
    function fetchPaginatedStories($page, $storiesPerPage) {
        global $conn;

        // Calculate offset for pagination
        $offset = ($page - 1) * $storiesPerPage;

        $sql = ""; // SQL query initialization
            $bindValues = []; // Array to store bound values
            // Determine which SQL query to use based on type
        if (isset($_GET['most_liked'])||!isset($_GET['most_commented'])&&!isset($_GET['tags'])) {
            $sql = "SELECT stories.*, COUNT(likes.id) AS like_count 
                FROM stories 
                LEFT JOIN likes ON stories.id = likes.story_id 
                GROUP BY stories.id 
                ORDER BY like_count DESC 
                LIMIT ? OFFSET ?";

            $bindValues = [$storiesPerPage, $offset]; // Bind limit and offset
        } elseif (isset($_GET['most_commented'])) {
            // SQL query to fetch most commented stories
            $sql = "SELECT stories.*, 
                   COUNT(DISTINCT comments.id) AS comment_count,
                   COUNT(DISTINCT likes.id) AS like_count
            FROM stories 
            LEFT JOIN comments ON stories.id = comments.story_id 
            LEFT JOIN likes ON stories.id = likes.story_id 
            GROUP BY stories.id 
            ORDER BY comment_count DESC 
            LIMIT ? OFFSET ?";

            $bindValues = [$storiesPerPage, $offset]; // Bind limit and offset
        } elseif (isset($_GET['tags'])) {
            // Extract tags from GET parameter
            $tagString = isset($_GET['tags']) ? $_GET['tags'] : null;
            $tags = $tagString ? explode(',', $tagString) : [];
            $tags = array_map('trim', $tags);

            if (empty($tags)) {
                return []; // If no tags, return an empty array
            }

            // Build LIKE clauses for each tag
            $likeClauses = [];
            foreach ($tags as $tag) {
                $likeClauses[] = "stories.content LIKE ?";
                $bindValues[] = '%' . $tag . '%'; // Add corresponding bound value
            }

            // Construct SQL with LIKE clauses and pagination
            $sql = "SELECT stories.*, COUNT(likes.id) AS like_count 
                FROM stories 
                LEFT JOIN likes ON stories.id = likes.story_id 
                WHERE " . implode(' OR ', $likeClauses) . "
                GROUP BY stories.id 
                ORDER BY like_count DESC 
                LIMIT ? OFFSET ?";

            // Bind limit and offset for pagination
            $bindValues[] = $storiesPerPage;
            $bindValues[] = $offset;
        }

        if ($sql === "") {
            return []; // Return empty if no valid query
        }

        // Prepare the SQL query
        $stmt = $conn->prepare($sql);

        // Bind all positional parameters in order
        foreach ($bindValues as $index => $value) {
            $stmt->bindValue($index + 1, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return fetched stories
    }

    function fetchCommentsByStoryId($storyId) {
        global $conn;

        // SQL query to fetch comments and count the likes for each comment
        $stmt = $conn->prepare(
            "SELECT comments.*, COUNT(likes.id) AS like_count 
             FROM comments 
             LEFT JOIN likes ON comments.id = likes.comment_id 
             WHERE comments.story_id = :story_id 
             GROUP BY comments.id 
             ORDER BY comments.created_at ASC"
        );
        $stmt->bindParam(':story_id', $storyId, PDO::PARAM_INT); // Bind the story ID
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return comments with like count
    }

    // Check the request method and user authentication
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $storiesPerPage = 10; // Stories per page
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Current page

    // Fetch total stories for pagination purposes
        $totalStmt = $conn->query("SELECT COUNT(DISTINCT stories.id) FROM stories");
        $totalStories = $totalStmt->fetchColumn(); // Total number of stories

    // Fetch paginated stories based on type, page, and search
        $stories = fetchPaginatedStories($currentPage, $storiesPerPage);

    // Create the response with stories and comments
        $storiesWithComments = array_map(function($story) {
            $comments = fetchCommentsByStoryId($story['id']); // Fetch comments
            return array(
                'id' => $story['id'],
                'content' => $story['content'],
                'author' => $story['author'],
                'date' => $story['created_at'],
                'like_count' => $story['like_count'],
                'story_image' => $story['story_image'] ?? null,
                'comments' => $comments
            );
        }, $stories);

        $response = array(
            'success' => true,
            'total_stories' => $totalStories,
            'stories' => $storiesWithComments,
            'current_page' => $currentPage,
            'stories_per_page' => $storiesPerPage
        );

        http_response_code(200); // OK status
        echo json_encode($response);
    } else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
    }
    ?>