<?php
    session_start();
    $errorMsg = "";
    $config = parse_ini_file('/var/www/private/db-config.ini');
            $conn = new mysqli(
                $config['servername'],
                $config['username'], 
                $config['password'], 
                $config['dbname']);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    function getAllMembers(){
        global $conn;
        // Retrieve all the rows from the members table
        $query = $conn->prepare("SELECT * FROM member");
        $query->execute();
    
        $result = $query->get_result();
    
        return $result;
    }

    function deleteMember($memberId){
        global $conn;
        // Prepared statement
        $query = $conn->prepare("DELETE FROM member WHERE member_id=?");
        // Bind the parameter
        $query->bind_param('i', $memberId); // Only bind one parameter
    
        if (!$query->execute()){
            echo 'Error: ' . $query->error;
            $conn->close();
            return false; // Return false on failure
        }
    
        $conn->close();
        return true; // Return true on success
    }

    function getAllProducts(){
        global $conn;
        // Retrieve all the rows from the members table
        $query = $conn->prepare("SELECT * FROM product");
        $query->execute();
    
        $result = $query->get_result();
    
        return $result;
    }

    function updateProduct($productId, $price, $stock){
        global $conn;
        // Prepared statement
        $query = $conn->prepare("UPDATE product SET price=?, stock=? WHERE product_id=?");
        // Bind the parameter
        $query->bind_param('dii', $price, $stock, $productId); // Only bind one parameter
    
        if (!$query->execute()){
            echo 'Error: ' . $query->error;
            $conn->close();
            return false; // Return false on failure
        }
    
        $conn->close();
        return true; // Return true on success
    }


 ?>