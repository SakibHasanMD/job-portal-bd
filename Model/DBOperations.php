<?php
include('../Config/DBconnection.php');


function getJobListings($conn)
{
    $sql = "SELECT j.*, c.company_name 
            FROM jobs j
            JOIN company_profiles c ON j.company_id = c.company_id
            ORDER BY j.published_date DESC";
    $result = mysqli_query($conn, $sql);

    return $result;
}


function getSavedJobs($conn, $seeker_id)
{
    $sql = "SELECT j.*, c.company_name 
            FROM saved_jobs s
            JOIN jobs j ON s.job_id = j.job_id
            JOIN company_profiles c ON j.company_id = c.company_id
            WHERE s.seeker_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seeker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}


function getAppliedJobs($conn, $seeker_id)
{
    $sql = "SELECT j.*, c.company_name, a.status, a.application_date 
            FROM applications a
            JOIN jobs j ON a.job_id = j.job_id
            JOIN company_profiles c ON j.company_id = c.company_id
            WHERE a.seeker_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seeker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
function getJobDetails($conn, $job_id)
{
    $stmt = $conn->prepare("SELECT j.*, c.company_name, c.website FROM jobs j 
        JOIN company_profiles c ON j.company_id = c.company_id WHERE j.job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getJobSeekerProfile($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT * FROM job_seeker_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateJobSeekerProfile($conn, $user_id, $data)
{
    $stmt = $conn->prepare("UPDATE job_seeker_profiles 
        SET full_name=?, address=?, phone=?, career_objective=?, education=?, looking_for=?, available_for=?, 
            expected_salary=?, preferred_job_category=?, skills=?, experience=?, date_of_birth=?, gender=?, 
            marital_status=?, nationality=?, current_location=?, linkedin_profile=?, portfolio=?, photo=?, resume=? 
        WHERE user_id=?");

    $stmt->bind_param(
        "ssssssssssssssssssssi",
        $data['full_name'],
        $data['address'],
        $data['phone'],
        $data['career_objective'],
        $data['education'],
        $data['looking_for'],
        $data['available_for'],
        $data['expected_salary'],
        $data['preferred_job_category'],
        $data['skills'],
        $data['experience'],
        $data['date_of_birth'],
        $data['gender'],
        $data['marital_status'],
        $data['nationality'],
        $data['current_location'],
        $data['linkedin'],
        $data['portfolio'],
        $data['photo'],
        $data['resume'],
        $user_id
    );

    return $stmt->execute();
}

function postJob($conn, $company_id, $job_title, $requirements, $responsibilities, $benefits, $skills, $location, $vacancy_count, $job_category, $age_requirement, $experience, $salary, $employment_type, $gender, $application_deadline, $status)
{
    $stmt = $conn->prepare("
        INSERT INTO jobs (company_id, job_title, requirements, responsibilities, benefits, skills, location, vacancy_count, job_category, age_requirement, experience, salary, employment_type, gender, application_deadline, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issssssisssssss", $company_id, $job_title, $requirements, $responsibilities, $benefits, $skills, $location, $vacancy_count, $job_category, $age_requirement, $experience, $salary, $employment_type, $gender, $application_deadline, $status);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getCompanyProfile($conn, $company_id)
{
    $query = "SELECT * FROM company_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function saveCompanyProfile($conn, $company_id, $name, $industry, $address, $business_description, $website, $email, $phone)
{
    if (getCompanyProfile($conn, $company_id)) {
        // Profile exists
        $query = "UPDATE company_profiles SET company_name = ?, industry = ?, address = ?, business_description = ?, website = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $name, $industry, $address, $business_description, $website, $email, $phone, $company_id);
    } else {
        //insert a new one
        $query = "INSERT INTO company_profiles (user_id, company_name, industry, address, business_description, website, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssssss", $company_id, $name, $industry, $address, $business_description, $website, $email, $phone);
    }

    return $stmt->execute();
}
function getCompanyJobs($conn, $company_id)
{
    $sql = "SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    return $stmt->get_result();
}


function getCompanyIdByUserId($conn, $user_id)
{
    $query = "SELECT company_id FROM company_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['company_id'] ?? null;
}

function getApplicationsByCompanyId($conn, $company_id)
{
    $query = "
        SELECT a.application_id, a.status, a.cover_letter, a.application_date,
               j.job_title, 
               s.full_name, s.email, s.phone, s.linkedin_profile, s.portfolio, s.seeker_id
        FROM applications a
        JOIN jobs j ON a.job_id = j.job_id
        JOIN job_seeker_profiles s ON a.seeker_id = s.seeker_id
        WHERE j.company_id = ?
        ORDER BY a.application_date DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    return $stmt->get_result();
}

function updateApplicationStatus($conn, $application_id, $status)
{
    $query = "UPDATE applications SET status = ? WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $application_id);
    return $stmt->execute();
}

function getCompanyApplications($conn, $company_id)
{
    $sql = "
        SELECT a.*, j.job_title, u.full_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.job_id 
        JOIN users u ON a.seeker_id = u.user_id 
        WHERE j.company_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    return $stmt->get_result();
}


?>