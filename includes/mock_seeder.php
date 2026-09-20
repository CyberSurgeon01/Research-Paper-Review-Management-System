<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['seeded'])) {
    $_SESSION['author'] = [
        'A001' => ['author_id' => 'A001', 'author_name' => 'Alice Author', 'email' => 'alice@example.com', 'affiliation' => 'Tech University', 'phone_number' => '1234567890', 'password' => 'author123'],
        'A002' => ['author_id' => 'A002', 'author_name' => 'Bob Writer', 'email' => 'bob@example.com', 'affiliation' => 'Science Institute', 'phone_number' => '0987654321', 'password' => 'author123']
    ];
    $_SESSION['reviewer'] = [
        'R001' => ['reviewer_id' => 'R001', 'reviewer_name' => 'Dr. Bob Reviewer', 'email' => 'bob.r@example.com', 'expertise' => 'Machine Learning', 'designation' => 'Senior Researcher', 'affiliation' => 'AI Labs', 'phone_number' => '1112223333', 'password' => 'reviewer123'],
        'R002' => ['reviewer_id' => 'R002', 'reviewer_name' => 'Dr. Carol Critic', 'email' => 'carol@example.com', 'expertise' => 'Data Science', 'designation' => 'Professor', 'affiliation' => 'Data Univ', 'phone_number' => '4445556666', 'password' => 'reviewer123']
    ];
    $_SESSION['administrator'] = [
        'ADMIN1' => ['admin_id' => 'ADMIN1', 'admin_name' => 'Charlie Admin', 'email' => 'admin@example.com', 'password' => 'admin123', 'role' => 'Super Admin']
    ];
    $_SESSION['research_field'] = [
        'F001' => ['field_id' => 'F001', 'field_name' => 'Artificial Intelligence', 'description' => 'AI, ML, and deep learning research.'],
        'F002' => ['field_id' => 'F002', 'field_name' => 'Data Science', 'description' => 'Big data analytics and statistics.'],
        'F003' => ['field_id' => 'F003', 'field_name' => 'Cybersecurity', 'description' => 'Network security, cryptography, and threat analysis.'],
        'F004' => ['field_id' => 'F004', 'field_name' => 'Quantum Computing', 'description' => 'Quantum algorithms, qubits, and hardware.'],
        'F005' => ['field_id' => 'F005', 'field_name' => 'Bioinformatics', 'description' => 'Computational biology and genomics.'],
        'F006' => ['field_id' => 'F006', 'field_name' => 'Blockchain Technology', 'description' => 'Decentralized systems and smart contracts.'],
        'F007' => ['field_id' => 'F007', 'field_name' => 'Internet of Things (IoT)', 'description' => 'Connected devices and edge computing.'],
        'F008' => ['field_id' => 'F008', 'field_name' => 'Software Engineering', 'description' => 'Agile, DevOps, and software architecture.'],
        'F009' => ['field_id' => 'F009', 'field_name' => 'Computer Vision', 'description' => 'Image processing and pattern recognition.'],
        'F010' => ['field_id' => 'F010', 'field_name' => 'Human-Computer Interaction', 'description' => 'UI/UX, usability, and accessible design.']
    ];
    $_SESSION['research_paper'] = [
        'P001' => ['paper_id' => 'P001', 'author_id' => 'A001', 'field_id' => 'F001', 'paper_title' => 'Deep Learning Advances', 'abstract_text' => 'A study on neural networks.', 'keywords' => 'AI, Neural Networks', 'submission_date' => '01-09-2026', 'status' => 'Under Review', 'file_path' => '/uploads/p001.pdf', 'version_number' => 1],
        'P002' => ['paper_id' => 'P002', 'author_id' => 'A002', 'field_id' => 'F002', 'paper_title' => 'Data Mining Techniques', 'abstract_text' => 'A review of data mining.', 'keywords' => 'Data, Mining', 'submission_date' => '15-09-2026', 'status' => 'Submitted', 'file_path' => '/uploads/p002.pdf', 'version_number' => 1]
    ];
    $_SESSION['review'] = [
        'RV001' => ['review_id' => 'RV001', 'paper_id' => 'P001', 'reviewer_id' => 'R001', 'review_date' => '10-09-2026', 'review_score' => '8', 'recommendation' => 'Accept', 'reviewer_comments' => 'Excellent paper, well researched.']
    ];
    $_SESSION['revision'] = [];
    $_SESSION['final_decision'] = [];
    

    $_SESSION['seeded'] = true;
}

// FORCE default passwords in case of old session cache
if (isset($_SESSION['reviewer']['R001']) && empty($_SESSION['reviewer']['R001']['password'])) {
    $_SESSION['reviewer']['R001']['password'] = 'reviewer123';
}
if (isset($_SESSION['reviewer']['R002']) && empty($_SESSION['reviewer']['R002']['password'])) {
    $_SESSION['reviewer']['R002']['password'] = 'reviewer123';
}
if (isset($_SESSION['administrator']['ADMIN1']) && empty($_SESSION['administrator']['ADMIN1']['password'])) {
    $_SESSION['administrator']['ADMIN1']['password'] = 'admin123';
}
?>