<?php
// app/controllers/ProfileController.php - Gestion profil utilisateur
require_once '../app/models/UserModel.php';

class ProfileController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    // ============================================
    // NOUVELLES MÉTHODES US8 - PAGES PRINCIPALES
    // ============================================
    
    /**
     * US8 - Page profil principal
     */
    public function index() {
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/login');
            exit;
        }
        
        // Récupérer les données utilisateur
        $user = $this->getUserProfile($_SESSION['user_id']);
        
        // Afficher la page
        $title = "Mon Profil - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/profile/index.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * US8 - Page gestion véhicule
     */
    public function vehicle() {
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/login');
            exit;
        }
        
        // Récupérer les données utilisateur et véhicules
        $user = $this->getUserProfile($_SESSION['user_id']);
        $vehicles = $this->getUserVehicles($_SESSION['user_id']);
        
        // Afficher la page
        $title = "Mon Véhicule - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/profile/vehicle.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * US8 - Page préférences
     */
    public function preferences() {
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/login');
            exit;
        }
        
        // Récupérer les données utilisateur et préférences
        $user = $this->getUserProfile($_SESSION['user_id']);
        $preferences = $this->getUserPreferences($_SESSION['user_id']);
        
        // Afficher la page
        $title = "Mes Préférences - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/profile/preferences.php';
        include '../app/views/layouts/footer.php';
    }
    
    // ============================================
    // MÉTHODES DE DONNÉES US8
    // ============================================
    
    /**
     * US8 - Récupérer le profil utilisateur
     */
    public function getUserProfile($user_id) {
        try {
            require_once '../app/config/config.php';
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $stmt = $pdo->prepare("
                SELECT id_utilisateur, pseudo, nom, prenom, email, 
                       telephone, credit, date_creation, note_moyenne
                FROM utilisateur 
                WHERE id_utilisateur = ?
            ");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si pas d'utilisateur trouvé, retourner des valeurs par défaut
            if (!$user) {
                return [
                    'id_utilisateur' => $user_id,
                    'pseudo' => 'Utilisateur',
                    'nom' => '',
                    'prenom' => '',
                    'email' => '',
                    'telephone' => '',
                    'credit' => 0,
                    'date_creation' => date('Y-m-d'),
                    'note_moyenne' => 5.0
                ];
            }
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Erreur getUserProfile: " . $e->getMessage());
            return [
                'id_utilisateur' => $user_id,
                'pseudo' => 'Utilisateur',
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'telephone' => '',
                'credit' => 0,
                'date_creation' => date('Y-m-d'),
                'note_moyenne' => 5.0
            ];
        }
    }

    /**
     * US8 - Récupérer les véhicules de l'utilisateur
     */
    public function getUserVehicles($user_id) {
        try {
            require_once '../app/config/config.php';
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Pour l'instant, retourner un tableau vide
            // La table véhicule sera développée dans les prochaines étapes
            return [];
            
        } catch (Exception $e) {
            error_log("Erreur getUserVehicles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * US8 - Récupérer les préférences de l'utilisateur
     */
    public function getUserPreferences($user_id) {
        try {
            require_once '../app/config/config.php';
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $stmt = $pdo->prepare("
                SELECT fumeur, animal, musique, discussion, autres_preferences
                FROM preference 
                WHERE id_utilisateur = ?
            ");
            $stmt->execute([$user_id]);
            $prefs = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si pas de préférences, retourner des valeurs par défaut
            if (!$prefs) {
                return [
                    'role_prefere' => 'both',
                    'rayon_recherche' => 20,
                    'ville_principale' => '',
                    'fumeur' => false,
                    'animal' => false,
                    'musique' => false,
                    'discussion' => false,
                    'climatisation' => false,
                    'bagages' => false,
                    'notif_email' => true,
                    'notif_reservation' => true,
                    'notif_rappels' => true,
                    'notif_messages' => true,
                    'autres_preferences' => ''
                ];
            }
            
            return $prefs;
            
        } catch (Exception $e) {
            error_log("Erreur getUserPreferences: " . $e->getMessage());
            return [
                'role_prefere' => 'both',
                'rayon_recherche' => 20,
                'ville_principale' => '',
                'fumeur' => false,
                'animal' => false,
                'musique' => false,
                'discussion' => false,
                'climatisation' => false,
                'bagages' => false,
                'notif_email' => true,
                'notif_reservation' => true,
                'notif_rappels' => true,
                'notif_messages' => true,
                'autres_preferences' => ''
            ];
        }
    }

    /**
     * US8 - Mettre à jour le rôle utilisateur (AJAX)
     */
    public function updateRole() {
        try {
            // Récupérer les données JSON
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!isset($data['role']) || !in_array($data['role'], ['passenger', 'driver', 'both'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Rôle invalide']);
                return;
            }
            
            $role = $data['role'];
            $user_id = $_SESSION['user_id'];
            
            // Pour l'instant, juste sauvegarder en session
            // Plus tard on pourra ajouter une colonne "role" dans la table utilisateur
            $_SESSION['user_role'] = $role;
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Rôle mis à jour avec succès',
                'role' => $role
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur updateRole: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    /**
     * US8 - Ajouter un véhicule (placeholder pour étape 2)
     */
    public function addVehicle() {
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Pour l'instant, juste un message
            $_SESSION['info_message'] = "Fonctionnalité d'ajout de véhicule en cours de développement (Étape 2 US8)";
            header('Location: /ecoride/public/profile/vehicle');
            exit;
        }
        
        // Si GET, rediriger vers la page véhicule
        header('Location: /ecoride/public/profile/vehicle');
        exit;
    }

    /**
     * US8 - Mettre à jour les préférences (placeholder pour étape 3)
     */
    public function updatePreferences() {
        try {
            if (!AuthController::isLoggedIn()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Non connecté']);
                return;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Pour l'instant, juste confirmer la réception
                $_SESSION['success_message'] = "Préférences sauvegardées ! (Fonctionnalité complète en cours de développement)";
                header('Location: /ecoride/public/profile/preferences');
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Préférences mises à jour avec succès'
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur updatePreferences: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }
    
    // ============================================
    // MÉTHODES UPLOAD PHOTO EXISTANTES
    // ============================================
    
    /**
     * Upload photo de profil
     */
    public function uploadPhoto() {
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Afficher la page d'upload
            $title = "Changer ma photo - EcoRide";
            include '../app/views/layouts/header.php';
            include '../app/views/profile/upload-photo.php';
            include '../app/views/layouts/footer.php';
        } else {
            // Traitement de l'upload
            $this->handlePhotoUpload();
        }
    }
    
    /**
     * Traitement de l'upload photo
     */
    private function handlePhotoUpload() {
        header('Content-Type: application/json');
        
        try {
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
    
    // ============================================
    // MÉTHODES PRIVÉES UTILITAIRES
    // ============================================
    
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