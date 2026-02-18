<?php
require_once __DIR__ . '/../../config/database.php';

class ImageController
{
    public function serve()
    {
        // Cache control (browser cache for 1 day)
        header("Cache-Control: public, max-age=86400");

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(404);
            exit;
        }

        try {
            $type = $_GET['type'] ?? 'artwork';
            $table = ($type === 'user') ? 'users' : 'artworks';

            // Validate table name to prevent SQL injection (though white-listing above does it)
            if (!in_array($table, ['users', 'artworks'])) {
                http_response_code(400);
                exit;
            }

            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT picture FROM $table WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['picture'])) {
                $data = $row['picture'];

                // Case 1: Base64 Image
                if (strpos($data, 'data:') === 0) {
                    $parts = explode(',', $data);
                    if (count($parts) >= 2) {
                        $header = $parts[0];
                        $body = $parts[1];

                        // Extract content type
                        if (preg_match('/data:(.*?);base64/', $header, $matches)) {
                            $contentType = $matches[1];
                        } else {
                            $contentType = 'image/jpeg'; // Default
                        }

                        $decoded = base64_decode($body);
                        header("Content-Type: " . $contentType);
                        header("Content-Length: " . strlen($decoded));
                        echo $decoded;
                        exit;
                    }
                }

                // Case 2: URL (http/https)
                if (filter_var($data, FILTER_VALIDATE_URL)) {
                    header("Location: " . $data);
                    exit;
                }

                // Case 3: Relative Path (uploads/...)
                $relativePath = ltrim($data, '/');
                header("Location: /" . $relativePath);
                exit;
            }
        } catch (Exception $e) {
            // Log error if needed
        }

        // 404 Not Found
        http_response_code(404);
        exit;
    }
}
