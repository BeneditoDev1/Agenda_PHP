<?php

    session_start();

    include_once("connection.php");
    include_once("url.php");

    $data = $_POST;

    // Modificações no banco    
    if(!empty($data)) {

        // Criar contato
        if($data["type"] === "create") {

            $name = $data["name"];
            $phone = $data["phone"];
            $observations = $data["observations"];

            $query = "INSERT INTO contact (name, phone, observations) VALUES (:name, :phone, :observations)";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);

            try {

               $stmt->execute();
               $_SESSION["msg"] = "Contato criado com sucesso!";

            } catch(PDOException $e) {
                // erro na conexão
                $erro = $e->getMessage();
                echo "Erro: $erro";
            }

        } else if($data["type"] === "edit") {

            $name = $data["name"];
            $phone = $data["phone"];
            $observations = $data["observations"];
            $id = $data["id"];

            $query = "UPDATE contact 
                      SET name = :name, phone = :phone, observations = :observations
                      WHERE id = :id ";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);
            $stmt->bindParam(":id", $id);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato atualizado com sucesso!";
 
             } catch(PDOException $e) {
                 // erro na conexão
                 $erro = $e->getMessage();
                 echo "Erro: $erro";
             }

        } else if($data['type'] === "delete") {

            $id = $data["id"];

            $query = "DELETE FROM contact WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato removido com sucesso!";
 
             } catch(PDOException $e) {
                 // erro na conexão
                 $erro = $e->getMessage();
                 echo "Erro: $erro";
             }
        }

        // Redirect Home
        header("Location:" . $BASE_URL . "../index.php");

    // Seleção de dados
    } else {
        $id;

        if(!empty($_GET)) {
            $id = $_GET["id"];
        }

        // Retorna o dado de um contato
        if(!empty($id)) {

            $query = "SELECT * FROM contact WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $contacts = $stmt->fetch();

         }else {

        // Retorna todos os contatos
            $contact = []; 

            $query = "SELECT * FROM contact";

            $stmt = $conn->prepare($query);

            $stmt->execute();
            $contact = $stmt->fetchAll();
        }
    }

    //Fechar conexão
    $conn = null;
    

    

