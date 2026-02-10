<?php

namespace Phira\Artbid\controllers_controllers_backend_controller_auth;

use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

class PasswordResetController {
    
    private static function getDbConnection() {
        try {
            // Use existing database configuration
            require_once __DIR__ . '/../../../../config/database.php';
            return dbConnect();
        } catch (\Exception $e) {
            error_log("Database connection failed: " . $e->getMessage());
            return null;
        }
    }
    
    public static function sendResetLink($email) {
        $pdo = self::getDbConnection();
        if (!$pdo) {
            return ['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้'];
        }
        
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return ['success' => false, 'message' => 'ไม่พบอีเมลนี้ในระบบ'];
            }
            
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token
            $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $token, $expiresAt, date('Y-m-d H:i:s')]);
            
            // Send reset email using existing email configuration
            $resetLink = "http://$_SERVER[HTTP_HOST]/Artbid/reset-password?token=$token";
            
            // Use PHPMailer like your existing email system
            require __DIR__ . '/../../../vendor/autoload.php';
            
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'artbid.site@gmail.com';
                $mail->Password = 'bqdu whbp szyd xzta';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                $mail->setFrom('artbid.site@gmail.com', 'Artbid.site');
                $mail->addAddress($email);
                
                $mail->isHTML(true);
                $mail->Subject = 'รีเซ็ตรหัสผ่าน Artbid';
                $mail->Body = "
                    <h3>รีเซ็ตรหัสผ่าน</h3>
                    <p>สวัสดีคุณ {$user['username']},</p>
                    <p>คลิกปุ่มด้านล่างเพื่อรีเซ็ตรหัสผ่านของคุณ:</p>
                    <a href='$resetLink' style='
                        display:inline-block;
                        background:#ff6b35;
                        color:white;
                        padding:10px 20px;
                        text-decoration:none;
                        border-radius:5px;
                        margin:20px 0;
                    '>รีเซ็ตรหัสผ่าน</a>
                    <p>ลิงก์นี้จะหมดอายุใน 1 ชั่วโมง</p>
                    <p>หากคุณไม่ได้ขอรีเซ็ตรหัสผ่าน กรุณาละเว้นอีเมลนี้</p>
                ";
                
                $mail->send();
                
            } catch (MailerException $e) {
                error_log("Email sending failed: " . $mail->ErrorInfo);
                // Continue with success message even if email fails
            }
            
            return ['success' => true, 'message' => 'ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว'];
            
        } catch (\Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'];
        }
    }
    
    public static function verifyToken($token) {
        $pdo = self::getDbConnection();
        if (!$pdo) {
            return ['valid' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้'];
        }
        
        try {
            $stmt = $pdo->prepare("
                SELECT pr.user_id, pr.expires_at 
                FROM password_resets pr 
                WHERE pr.token = ? AND pr.used = FALSE AND pr.expires_at > NOW()
            ");
            $stmt->execute([$token]);
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$reset) {
                return ['valid' => false, 'message' => 'ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุ'];
            }
            
            return ['valid' => true, 'user_id' => $reset['user_id']];
            
        } catch (\Exception $e) {
            error_log("Token verification error: " . $e->getMessage());
            return ['valid' => false, 'message' => 'เกิดข้อผิดพลาดในการตรวจสอบลิงก์'];
        }
    }
    
    public static function resetPassword($token, $newPassword) {
        $pdo = self::getDbConnection();
        if (!$pdo) {
            return ['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้'];
        }
        
        try {
            $pdo->beginTransaction();
            
            // Verify token again
            $verifyResult = self::verifyToken($token);
            if (!$verifyResult['valid']) {
                $pdo->rollBack();
                return ['success' => false, 'message' => $verifyResult['message']];
            }
            
            $userId = $verifyResult['user_id'];
            
            // Hash new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update user password
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);
            
            // Mark token as used
            $stmt = $pdo->prepare("UPDATE password_resets SET used = TRUE WHERE token = ?");
            $stmt->execute([$token]);
            
            $pdo->commit();
            
            return ['success' => true, 'message' => 'รีเซ็ตรหัสผ่านสำเร็จ'];
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Password reset error: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'];
        }
    }
}
