<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$seed_version = 4;
if (!isset($_SESSION['seeded']) || !isset($_SESSION['seed_version']) || $_SESSION['seed_version'] < $seed_version) {
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
        'P001' => ['paper_id' => 'P001', 'author_id' => 'A001', 'field_id' => 'F001', 'paper_title' => 'Deep Learning Advances', 'abstract_text' => 'A study on neural networks.', 'keywords' => 'AI, Neural Networks', 'submission_date' => '01-09-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p001.pdf', 'version_number' => 1],
        'P002' => ['paper_id' => 'P002', 'author_id' => 'A002', 'field_id' => 'F002', 'paper_title' => 'Data Mining Techniques', 'abstract_text' => 'A review of data mining.', 'keywords' => 'Data, Mining', 'submission_date' => '15-09-2026', 'status' => 'Rejected', 'file_path' => '/uploads/p002.pdf', 'version_number' => 1],
        'P003' => ['paper_id' => 'P003', 'author_id' => 'A001', 'field_id' => 'F003', 'paper_title' => 'Network Intrusion Detection', 'abstract_text' => 'ML-based intrusion detection.', 'keywords' => 'Security, IDS', 'submission_date' => '05-08-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p003.pdf', 'version_number' => 1],
        'P004' => ['paper_id' => 'P004', 'author_id' => 'A002', 'field_id' => 'F004', 'paper_title' => 'Quantum Error Correction', 'abstract_text' => 'Surface codes for fault tolerance.', 'keywords' => 'Quantum, Error Correction', 'submission_date' => '20-07-2026', 'status' => 'Revision Required', 'file_path' => '/uploads/p004.pdf', 'version_number' => 2],
        'P005' => ['paper_id' => 'P005', 'author_id' => 'A001', 'field_id' => 'F005', 'paper_title' => 'Genome Sequencing Pipelines', 'abstract_text' => 'Efficient NGS data processing.', 'keywords' => 'Bioinformatics, NGS', 'submission_date' => '12-06-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p005.pdf', 'version_number' => 1],
        'P006' => ['paper_id' => 'P006', 'author_id' => 'A002', 'field_id' => 'F006', 'paper_title' => 'Smart Contract Vulnerabilities', 'abstract_text' => 'Security analysis of Solidity.', 'keywords' => 'Blockchain, Solidity', 'submission_date' => '01-06-2026', 'status' => 'Rejected', 'file_path' => '/uploads/p006.pdf', 'version_number' => 1],
        'P007' => ['paper_id' => 'P007', 'author_id' => 'A001', 'field_id' => 'F007', 'paper_title' => 'Edge Computing for IoT', 'abstract_text' => 'Latency optimization in IoT.', 'keywords' => 'IoT, Edge', 'submission_date' => '18-05-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p007.pdf', 'version_number' => 1],
        'P008' => ['paper_id' => 'P008', 'author_id' => 'A002', 'field_id' => 'F008', 'paper_title' => 'Microservices Architecture Patterns', 'abstract_text' => 'Design patterns for scalable systems.', 'keywords' => 'Microservices, DevOps', 'submission_date' => '10-05-2026', 'status' => 'Revision Required', 'file_path' => '/uploads/p008.pdf', 'version_number' => 3],
        'P009' => ['paper_id' => 'P009', 'author_id' => 'A001', 'field_id' => 'F009', 'paper_title' => 'Real-Time Object Detection', 'abstract_text' => 'YOLO-based detection benchmarks.', 'keywords' => 'Computer Vision, YOLO', 'submission_date' => '25-04-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p009.pdf', 'version_number' => 1],
        'P010' => ['paper_id' => 'P010', 'author_id' => 'A002', 'field_id' => 'F010', 'paper_title' => 'Accessible Web Interfaces', 'abstract_text' => 'WCAG compliance study.', 'keywords' => 'HCI, Accessibility', 'submission_date' => '15-04-2026', 'status' => 'Accepted', 'file_path' => '/uploads/p010.pdf', 'version_number' => 1]
    ];
    $_SESSION['review'] = [
        'RV001' => ['review_id' => 'RV001', 'paper_id' => 'P001', 'reviewer_id' => 'R001', 'review_date' => '10-09-2026', 'review_score' => '8', 'recommendation' => 'Accept', 'reviewer_comments' => 'Excellent paper, well researched.'],
        'RV002' => ['review_id' => 'RV002', 'paper_id' => 'P002', 'reviewer_id' => 'R001', 'review_date' => '12-09-2026', 'review_score' => '4', 'recommendation' => 'Reject', 'reviewer_comments' => 'Weak experimental design, lacks novelty.'],
        'RV003' => ['review_id' => 'RV003', 'paper_id' => 'P003', 'reviewer_id' => 'R001', 'review_date' => '08-09-2026', 'review_score' => '9', 'recommendation' => 'Accept', 'reviewer_comments' => 'Strong methodology and clear results.'],
        'RV004' => ['review_id' => 'RV004', 'paper_id' => 'P004', 'reviewer_id' => 'R001', 'review_date' => '05-09-2026', 'review_score' => '6', 'recommendation' => 'Major Revision', 'reviewer_comments' => 'Needs more simulation data and comparisons.'],
        'RV005' => ['review_id' => 'RV005', 'paper_id' => 'P005', 'reviewer_id' => 'R001', 'review_date' => '01-09-2026', 'review_score' => '7', 'recommendation' => 'Minor Revision', 'reviewer_comments' => 'Good work but missing related work section.'],
        'RV006' => ['review_id' => 'RV006', 'paper_id' => 'P006', 'reviewer_id' => 'R002', 'review_date' => '28-08-2026', 'review_score' => '3', 'recommendation' => 'Reject', 'reviewer_comments' => 'Threat model is poorly defined.'],
        'RV007' => ['review_id' => 'RV007', 'paper_id' => 'P007', 'reviewer_id' => 'R002', 'review_date' => '25-08-2026', 'review_score' => '9', 'recommendation' => 'Accept', 'reviewer_comments' => 'Impressive real-world evaluation on IoT devices.'],
        'RV008' => ['review_id' => 'RV008', 'paper_id' => 'P008', 'reviewer_id' => 'R002', 'review_date' => '20-08-2026', 'review_score' => '5', 'recommendation' => 'Major Revision', 'reviewer_comments' => 'Needs comparison with monolithic architecture.'],
        'RV009' => ['review_id' => 'RV009', 'paper_id' => 'P009', 'reviewer_id' => 'R002', 'review_date' => '15-08-2026', 'review_score' => '10', 'recommendation' => 'Accept', 'reviewer_comments' => 'State-of-the-art YOLO results, publish as is.'],
        'RV010' => ['review_id' => 'RV010', 'paper_id' => 'P010', 'reviewer_id' => 'R002', 'review_date' => '10-08-2026', 'review_score' => '8', 'recommendation' => 'Accept', 'reviewer_comments' => 'Solid WCAG compliance study with actionable insights.']
    ];
    $_SESSION['revision'] = [
        'REV001' => ['revision_id' => 'REV001', 'paper_id' => 'P004', 'version_number' => '2', 'upload_date' => '25-07-2026', 'revised_file' => '/uploads/p004_v2.pdf', 'remarks' => 'Added more simulation results as requested.'],
        'REV002' => ['revision_id' => 'REV002', 'paper_id' => 'P008', 'version_number' => '2', 'upload_date' => '15-05-2026', 'revised_file' => '/uploads/p008_v2.pdf', 'remarks' => 'Included monolithic vs microservices comparison.'],
        'REV003' => ['revision_id' => 'REV003', 'paper_id' => 'P008', 'version_number' => '3', 'upload_date' => '01-06-2026', 'revised_file' => '/uploads/p008_v3.pdf', 'remarks' => 'Final revision with all reviewer feedback addressed.'],
        'REV004' => ['revision_id' => 'REV004', 'paper_id' => 'P004', 'version_number' => '3', 'upload_date' => '10-08-2026', 'revised_file' => '/uploads/p004_v3.pdf', 'remarks' => 'Extended error correction benchmarks.'],
        'REV005' => ['revision_id' => 'REV005', 'paper_id' => 'P001', 'version_number' => '2', 'upload_date' => '20-09-2026', 'revised_file' => '/uploads/p001_v2.pdf', 'remarks' => 'Minor formatting and citation fixes.']
    ];
    $_SESSION['final_decision'] = [
        'D001' => ['decision_id' => 'D001', 'paper_id' => 'P001', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '15-09-2026', 'comments' => 'Outstanding research with strong methodology.'],
        'D002' => ['decision_id' => 'D002', 'paper_id' => 'P002', 'admin_id' => 'ADMIN1', 'final_status' => 'Rejected', 'decision_date' => '16-09-2026', 'comments' => 'Insufficient experimental validation.'],
        'D003' => ['decision_id' => 'D003', 'paper_id' => 'P003', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '12-09-2026', 'comments' => 'Solid contribution to cybersecurity literature.'],
        'D004' => ['decision_id' => 'D004', 'paper_id' => 'P004', 'admin_id' => 'ADMIN1', 'final_status' => 'Further Revision', 'decision_date' => '10-09-2026', 'comments' => 'Promising work but needs more simulation results.'],
        'D005' => ['decision_id' => 'D005', 'paper_id' => 'P005', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '08-09-2026', 'comments' => 'Well-structured and novel approach to NGS pipelines.'],
        'D006' => ['decision_id' => 'D006', 'paper_id' => 'P006', 'admin_id' => 'ADMIN1', 'final_status' => 'Rejected', 'decision_date' => '05-09-2026', 'comments' => 'The threat model is not clearly defined.'],
        'D007' => ['decision_id' => 'D007', 'paper_id' => 'P007', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '01-09-2026', 'comments' => 'Excellent practical evaluation on real IoT devices.'],
        'D008' => ['decision_id' => 'D008', 'paper_id' => 'P008', 'admin_id' => 'ADMIN1', 'final_status' => 'Further Revision', 'decision_date' => '28-08-2026', 'comments' => 'Add comparative analysis with monolithic architectures.'],
        'D009' => ['decision_id' => 'D009', 'paper_id' => 'P009', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '25-08-2026', 'comments' => 'State-of-the-art results on COCO dataset.'],
        'D010' => ['decision_id' => 'D010', 'paper_id' => 'P010', 'admin_id' => 'ADMIN1', 'final_status' => 'Accepted', 'decision_date' => '20-08-2026', 'comments' => 'Valuable contribution to accessible design guidelines.']
    ];
    

    $_SESSION['seeded'] = true;
    $_SESSION['seed_version'] = $seed_version;
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