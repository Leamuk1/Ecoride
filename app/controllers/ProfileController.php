<?php
// app/controllers/ProfileController.php - Gestion profil utilisateur
require_once '../app/models/UserModel.php';

class ProfileController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Upload photo de profil
     */
    public function uploadPhoto() {
        header('Content-Type: application/json');
        
        try {
            // Vérifier que l'utilisateur est connecté
            if (!AuthController::isLoggedIn()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vous devez être connecté'
                ]);
                return;
            }
            
            // Vérifier qu'un fichier a été uploadé
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun fichier reçu ou erreur d\'upload'
                ]);
                return;
            }
            
            $file = $_FILES['photo'];
            $userId = $_SESSION['user_id'];
            
            // Validation du fichier
            $validation = $this->validateUploadedFile($file);
            if (!$validation['valid']) {
                echo json_encode([
                    'success' => false,
                    'message' => $validation['message']
                ]);
                return;
            }
            
            // Créer le dossier uploads si nécessaire
            $uploadDir = '../public/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Générer un nom de fichier unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'profile_' . $userId . '_' . time() . '.' . strtolower($extension);
            $filePath = $uploadDir . $fileName;
            
            // Supprimer l'ancienne photo si elle existe
            $currentUser = $this->userModel->getUserById($userId);
            if ($currentUser && !empty($currentUser['photo_profil'])) {
                $oldFile = $uploadDir . $currentUser['photo_profil'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            // Déplacer le fichier uploadé
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Redimensionner l'image si nécessaire
                $this->resizeImage($filePath, 300, 300);
                
                // Mettre à jour la base de données
                $result = $this->userModel->updateProfilePhoto($userId, $fileName);
                
                if ($result) {
                    // Mettre à jour la session
                    $_SESSION['user_photo'] = $fileName;
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Photo mise à jour avec succès',
                        'photo_url' => '/ecoride/public/uploads/profiles/' . $fileName
                    ]);
                } else {
                    // Supprimer le fichier si l'update DB a échoué
                    unlink($filePath);
                    
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de la mise à jour en base'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement du fichier'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Erreur upload photo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erreur technique lors de l\'upload'
            ]);
        }
    }
    
    /**
     * Supprimer photo de profil
     */
    public function removePhoto() {
        header('Content-Type: application/json');
        
        try {
            if (!AuthController::isLoggedIn()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vous devez être connecté'
                ]);
                return;
            }
            
            $userId = $_SESSION['user_id'];
            $currentUser = $this->userModel->getUserById($userId);
            
            if ($currentUser && !empty($currentUser['photo_profil'])) {
                // Supprimer le fichier physique
                $filePath = '../public/uploads/profiles/' . $currentUser['photo_profil'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Mettre à jour la base de données
                $result = $this->userModel->updateProfilePhoto($userId, null);
                
                if ($result) {
                    // Mettre à jour la session
                    unset($_SESSION['user_photo']);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Photo supprimée avec succès'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de la mise à jour en base'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Aucune photo à supprimer'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Erreur suppression photo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erreur technique lors de la suppression'
            ]);
        }
    }
    
    /**
     * Valider le fichier uploadé
     */
    private function validateUploadedFile($file) {
        // Taille maximum : 5 MB
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return [
                'valid' => false,
                'message' => 'La photo ne doit pas dépasser 5 MB'
            ];
        }
        
        // Types MIME autorisés
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return [
                'valid' => false,
                'message' => 'Format non supporté. Utilisez JPG, PNG ou WEBP'
            ];
        }
        
        // Vérifier que c'est bien une image
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return [
                'valid' => false,
                'message' => 'Le fichier n\'est pas une image valide'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Redimensionner l'image
     */
    private function resizeImage($filePath, $maxWidth, $maxHeight) {
        try {
            $imageInfo = getimagesize($filePath);
            if (!$imageInfo) return false;
            
            list($width, $height, $type) = $imageInfo;
            
            // Calculer les nouvelles dimensions
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            if ($ratio >= 1) return true; // Pas besoin de redimensionner
            
            $newWidth = round($width * $ratio);
            $newHeight = round($height * $ratio);
            
            // Créer l'image source
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($filePath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($filePath);
                    break;
                case IMAGETYPE_WEBP:
                    $source = imagecreatefromwebp($filePath);
                    break;
                default:
                    return false;
            }
            
            if (!$source) return false;
            
            // Créer l'image de destination
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            
            // Préserver la transparence pour PNG et WEBP
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
                imagealphablending($destination, false);
                imagesavealpha($destination, true);
                $transparent = imagecolorallocatealpha($destination, 0, 0, 0, 127);
                imagefill($destination, 0, 0, $transparent);
            }
            
            // Redimensionner
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Sauvegarder
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($destination, $filePath, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($destination, $filePath, 9);
                    break;
                case IMAGETYPE_WEBP:
                    imagewebp($destination, $filePath, 90);
                    break;
            }
            
            // Nettoyer la mémoire
            imagedestroy($source);
            imagedestroy($destination);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur redimensionnement image: " . $e->getMessage());
            return false;
        }
    }
}