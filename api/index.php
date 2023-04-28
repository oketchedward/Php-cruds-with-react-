<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once 'DbConnect.php';
    $database = new DatabaseConnection();
    $conn = $database->getConnection();

    $response = array();
    $method = $_SERVER['REQUEST_METHOD'];

     switch($method) {
        case "GET":
            $sql = "SELECT * FROM users";
            $path = explode('/', $_SERVER['REQUEST_URI']);
            if(isset($path[3]) &&  is_numeric($path[3])){
                $sql .= " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $path[3]); 
                $stmt->execute();
                $users = $stmt->fetch(PDO::FETCH_ASSOC);
            }else {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
           
        echo json_encode($users);
        break; 

            case "POST":
                $imageFile = $_FILES['image'];
                $imageFileName = $imageFile['name'];
                $imageFileType = $imageFile['type'];
                $imageFileTmpName = $imageFile['tmp_name'];

                // Upload image to server
                $targetDir = 'images/';
                $targetFile = $targetDir . basename($imageFileName);
                move_uploaded_file($imageFileTmpName, $targetFile);

                // Insert data into database
                $user = $_POST;
                if ($user) {
                $sql = "INSERT INTO users (Username, email, mobile, image, created_at) VALUES ( :Username, :email, :mobile, :image, :created_at)";
                $stmt = $conn->prepare($sql);
                $created_at = date('Y-m-d');
                $stmt->bindParam(':Username', $user['username']);
                $stmt->bindParam(':email',  $user['email']);
                $stmt->bindParam(':mobile', $user['mobile']);
                $stmt->bindParam(':image', $targetFile);
                $stmt->bindParam(':created_at', $created_at);
                if($stmt->execute()){
                    $response = ['status' => 1, 'message' =>  'Record created successfully'];
                }else {
                    $response = ['status' => 0, 'message' =>  'Failed to create Record'];
                }
            }
                echo json_encode($response);
                break;       
                
                case "PUT": 
                    $putdatafp = fopen("php://input", "r"); // Open the stream
                
                    // Read the data from the stream
                    $putdata = fread($putdatafp, 1024); // Change the buffer size (1024) to your desired value
                
                    fclose($putdatafp); // Close the stream
                
                    // Parse the multipart form-data
                    $boundary = substr($putdata, 0, strpos($putdata, "\r\n")); // Extract the boundary
                    $parts = array_slice(explode($boundary, $putdata), 1); // Split the data into parts
                
                    $data = array(); // Array to store field values
                    $file = array(); // Array to store file data
                
                    foreach ($parts as $part) {
                        // Split the part into headers and body
                        $part = trim($part);
                        $headerAndBody = explode("\r\n\r\n", $part, 2);
                        $headers = $headerAndBody[0];
                        $body = isset($headerAndBody[1]) ? $headerAndBody[1] : '';
                
                        // Parse the headers to extract the field name and filename (if present)
                        preg_match('/Content-Disposition:.*\s+name="([^"]+)"(?:;\s+filename="([^"]+)")?/', $headers, $matches);
                        $name = isset($matches[1]) ? $matches[1] : '';
                        $filename = isset($matches[2]) ? $matches[2] : '';
                
                        // If filename is present, it's a file part
                        if (!empty($filename)) {
                            // Store the file data
                            $file['name'] = $filename;
                            $file['type'] = mime_content_type(__DIR__ . '/images/' . $filename);// Get the file's content type
                            $file['size'] = strlen($body);
                            $file['data'] = $body;
                        } else {
                            // Store the field value
                            $data[$name] = $body;
                        }
                    }
                
                    // Access the field values and file data as needed
                    $id = isset($data['id']) ? $data['id'] : '';
                    $username = isset($data['username']) ? $data['username'] : '';
                    $email = isset($data['email']) ? $data['email'] : '';
                    $mobile = isset($data['mobile']) ? $data['mobile'] : '';
                    $image = isset($file['data']) ? $file['data'] : '';
                    $imageFilename = isset($file['name']) ? $file['name'] : '';
                    $imageType = isset($file['type']) ? $file['type'] : '';
                    $imageSize = isset($file['size']) ? $file['size'] : '';
                
                    // Perform actions with the extracted data
                    // For example, you can update a database, store the uploaded file, etc.
                    // Note: This is just an example, please modify it based on your requirements
                
                    $sql = "UPDATE users SET Username=?, email=?, mobile=?, image=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(1, $username);
                    $stmt->bindParam(2, $email);
                    $stmt->bindParam(3, $mobile);
                    $stmt->bindParam(4, $imageFilename);
                    $stmt->bindParam(5, $id);
                    if($stmt->execute()){
                        $response = ['status' => 1, 'message' =>  'Record Updated successfully'];
                    } else {
                        $response = ['status' => 0, 'message' =>  'Failed to Update Record'];
                    }

                    // Output the received data
                
                    break;
                
                
        case "DELETE": 
            $sql = "DELETE FROM users WHERE id = :id";
            $path = explode('/', $_SERVER['REQUEST_URI']);
            
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $path[3]); 
                $result = $stmt->execute();

                if($result){
                    $response = ['status' => 1, 'message' =>  'Record Deleted successfully'];
                }else {
                    $response = ['status' => 0, 'message' =>  'Failed to Delete Record'];
                }
                echo json_encode($response);
                break;
    }

?>
