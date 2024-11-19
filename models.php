<?php
function fetchApplicants($pdo, $search = null) {
    $sql = "SELECT * FROM applicants";
    if ($search) {
        $sql .= " WHERE CONCAT_WS(' ', first_name, last_name, email, phone, address, job_title, skills, status) LIKE ?";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search ? ["%$search%"] : []);
    return [
        'message' => 'Applicants fetched successfully.',
        'statusCode' => 200,
        'querySet' => $stmt->fetchAll()
    ];
}

function insertApplicant($pdo, $data) {
    try {
        $sql = "INSERT INTO applicants (first_name, last_name, email, phone, address, job_title, skills, status, added_by) 
                VALUES (:first_name, :last_name, :email, :phone, :address, :job_title, :skills, :status, :added_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return ['message' => 'Applicant added successfully.', 'statusCode' => 200];
    } catch (PDOException $e) {
        return ['message' => 'Error: ' . $e->getMessage(), 'statusCode' => 400];
    }
}

function updateApplicant($pdo, $id, $data) {
    try {
        $sql = "UPDATE applicants SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, 
                address = :address, job_title = :job_title, skills = :skills, status = :status, added_by = :added_by 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $data['id'] = $id;
        $stmt->execute($data);
        return ['message' => 'Applicant updated successfully.', 'statusCode' => 200];
    } catch (PDOException $e) {
        return ['message' => 'Error: ' . $e->getMessage(), 'statusCode' => 400];
    }
}

function deleteApplicant($pdo, $id) {
    try {
        $sql = "DELETE FROM applicants WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return [
            'message' => 'Applicant deleted successfully.',
            'statusCode' => 200
        ];
    } catch (PDOException $e) {
        return [
            'message' => 'Error deleting applicant: ' . $e->getMessage(),
            'statusCode' => 400
        ];
    }
}

?>