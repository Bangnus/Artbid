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
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT picture FROM artworks WHERE id = :id");
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
                // Ensure no double slash
                $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
                $relativePath = ltrim($data, '/');
                header("Location: " . $appUrl . '/' . $relativePath);
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
