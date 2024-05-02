    <?php
    session_start();
    include "../db_connexion.php";
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "GET"&& isset($_SESSION['username'])) {
        // Define pagination parameters
        $storiesPerPage = 10;
        // Current page number
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        // Calculate offset
        $offset = ($currentPage - 1) * $storiesPerPage;

        // Prepare SQL statement to get total number of stories (for pagination)
        $totalStmt = $conn->query("SELECT COUNT(*) FROM stories");
        $totalStories = $totalStmt->fetchColumn();

        // Prepare SQL statement with pagination
        $stmt = $conn->prepare("SELECT stories.*, COUNT(likes.like_id) AS like_count 
                                FROM stories 
                                LEFT JOIN likes ON stories.id = likes.story_id 
                                GROUP BY stories.id
                                ORDER BY stories.created_at DESC
                                LIMIT :offset, :limit");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $storiesPerPage, PDO::PARAM_INT);
        $stmt->execute();
        $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Transform fetched data into desired JSON structure
        $formattedStories = [];
        foreach ($stories as $story) {
            $formattedStories[] = array(
                'id' => $story['id'],
                'content' => $story['content'],
                'author' => $story['author'],
                'date' => $story['created_at'],
                'like_count' => $story['like_count']
            );
        }

        // Return JSON response including total number of stories
        $response = array(
            'total_stories' => $totalStories,
            'stories' => $formattedStories
        );

        http_response_code(200);
        echo json_encode($response);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Invalid action'));
        exit;
    }
