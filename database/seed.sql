-- Research Paper Review Management System
-- Oracle 10g Mock Data Seed

-- Clear existing data (if running repeatedly)
-- DELETE FROM Final_Decision;
-- DELETE FROM Revision;
-- DELETE FROM Review;
-- DELETE FROM Research_Paper;
-- DELETE FROM Research_Field;
-- DELETE FROM Administrator;
-- DELETE FROM Reviewer;
-- DELETE FROM Author;

-- 1. Authors
INSERT INTO Author (author_id, author_name, email, affiliation, phone_number, password) 
VALUES ('A001', 'Alice Author', 'alice@example.com', 'Tech University', '1234567890', 'author123');
INSERT INTO Author (author_id, author_name, email, affiliation, phone_number, password) 
VALUES ('A002', 'Bob Smith', 'bob@example.com', 'State College', '0987654321', 'author456');

-- 2. Reviewers
INSERT INTO Reviewer (reviewer_id, reviewer_name, email, expertise, designation, affiliation, phone_number, password) 
VALUES ('R001', 'Dr. Reviewer One', 'rev1@example.com', 'Machine Learning', 'Professor', 'Global Institute', '1112223333', 'reviewer123');
INSERT INTO Reviewer (reviewer_id, reviewer_name, email, expertise, designation, affiliation, phone_number, password) 
VALUES ('R002', 'Dr. Reviewer Two', 'rev2@example.com', 'Data Science', 'Associate Prof.', 'National University', '4445556666', 'reviewer456');

-- 3. Administrators
INSERT INTO Administrator (admin_id, admin_name, email, admin_role, password) 
VALUES ('ADMIN1', 'Super Admin', 'admin@example.com', 'Super Admin', 'admin123');

-- 4. Research Fields
INSERT INTO Research_Field (field_id, field_name, description) VALUES ('F001', 'Artificial Intelligence', 'Study of intelligent agents and machine learning.');
INSERT INTO Research_Field (field_id, field_name, description) VALUES ('F002', 'Data Science', 'Extracting insights from structured and unstructured data.');
INSERT INTO Research_Field (field_id, field_name, description) VALUES ('F003', 'Cybersecurity', 'Protection of computer systems and networks.');
INSERT INTO Research_Field (field_id, field_name, description) VALUES ('F004', 'Quantum Computing', 'Computation using quantum-mechanical phenomena.');

-- 5. Research Papers (using TO_DATE for Oracle dates)
INSERT INTO Research_Paper (paper_id, author_id, field_id, paper_title, abstract_text, keywords, submission_date, status, file_path, version_number) 
VALUES ('P001', 'A001', 'F001', 'Deep Learning in Medicine', 'An overview of neural networks in healthcare.', 'AI, Health, Neural Networks', TO_DATE('15-08-2026', 'DD-MM-YYYY'), 'Accepted', '/uploads/p001.pdf', 1);

INSERT INTO Research_Paper (paper_id, author_id, field_id, paper_title, abstract_text, keywords, submission_date, status, file_path, version_number) 
VALUES ('P002', 'A002', 'F002', 'Big Data Analytics', 'Scalable processing of large datasets.', 'Big Data, Analytics, Hadoop', TO_DATE('20-08-2026', 'DD-MM-YYYY'), 'Under Review', '/uploads/p002.pdf', 1);

INSERT INTO Research_Paper (paper_id, author_id, field_id, paper_title, abstract_text, keywords, submission_date, status, file_path, version_number) 
VALUES ('P003', 'A001', 'F003', 'Zero Trust Architecture', 'Modern network security principles.', 'Security, Zero Trust, Networking', TO_DATE('01-09-2026', 'DD-MM-YYYY'), 'Revision Required', '/uploads/p003.pdf', 1);

-- 6. Reviews
INSERT INTO Review (review_id, paper_id, reviewer_id, review_date, review_score, recommendation, reviewer_comments, assignment_status) 
VALUES ('RV001', 'P001', 'R001', TO_DATE('20-08-2026', 'DD-MM-YYYY'), 9, 'Accept', 'Excellent application of ML in healthcare.', 'Completed');

INSERT INTO Review (review_id, paper_id, reviewer_id, review_date, review_score, recommendation, reviewer_comments, assignment_status) 
VALUES ('RV002', 'P003', 'R002', TO_DATE('10-09-2026', 'DD-MM-YYYY'), 6, 'Major Revision', 'Needs more real-world implementation examples.', 'Completed');

-- 7. Revisions
INSERT INTO Revision (revision_id, paper_id, version_number, upload_date, revised_file, remarks) 
VALUES ('REV001', 'P003', 2, TO_DATE('15-09-2026', 'DD-MM-YYYY'), '/uploads/p003_v2.pdf', 'Added case studies for enterprise networks.');

-- 8. Final Decisions
INSERT INTO Final_Decision (decision_id, paper_id, admin_id, final_status, decision_date, comments) 
VALUES ('D001', 'P001', 'ADMIN1', 'Accepted', TO_DATE('25-08-2026', 'DD-MM-YYYY'), 'Outstanding research. Approved for publication.');

COMMIT;

