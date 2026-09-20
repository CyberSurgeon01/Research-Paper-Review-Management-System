-- Research Paper Review Management System
-- Oracle 10g SQL Schema

-- 1. Create Author Table
CREATE TABLE Author (
    author_id VARCHAR2(20) PRIMARY KEY,
    author_name VARCHAR2(100) NOT NULL,
    email VARCHAR2(100) UNIQUE NOT NULL,
    affiliation VARCHAR2(150),
    phone_number VARCHAR2(20),
    password VARCHAR2(100) NOT NULL
);

-- 2. Create Reviewer Table
CREATE TABLE Reviewer (
    reviewer_id VARCHAR2(20) PRIMARY KEY,
    reviewer_name VARCHAR2(100) NOT NULL,
    email VARCHAR2(100) UNIQUE NOT NULL,
    expertise VARCHAR2(255),
    designation VARCHAR2(100),
    affiliation VARCHAR2(150),
    phone_number VARCHAR2(20),
    password VARCHAR2(100) NOT NULL
);

-- 3. Create Administrator Table
CREATE TABLE Administrator (
    admin_id VARCHAR2(20) PRIMARY KEY,
    admin_name VARCHAR2(100) NOT NULL,
    email VARCHAR2(100) UNIQUE NOT NULL,
    admin_role VARCHAR2(50),
    password VARCHAR2(100) NOT NULL
);

-- 4. Create Research Field Table
CREATE TABLE Research_Field (
    field_id VARCHAR2(20) PRIMARY KEY,
    field_name VARCHAR2(100) NOT NULL,
    description VARCHAR2(500)
);

-- 5. Create Research Paper Table
CREATE TABLE Research_Paper (
    paper_id VARCHAR2(20) PRIMARY KEY,
    author_id VARCHAR2(20) NOT NULL,
    field_id VARCHAR2(20) NOT NULL,
    paper_title VARCHAR2(255) NOT NULL,
    abstract_text CLOB,
    keywords VARCHAR2(255),
    submission_date DATE,
    status VARCHAR2(50) DEFAULT 'Submitted',
    file_path VARCHAR2(255),
    version_number NUMBER DEFAULT 1,
    CONSTRAINT fk_paper_author FOREIGN KEY (author_id) REFERENCES Author(author_id) ON DELETE CASCADE,
    CONSTRAINT fk_paper_field FOREIGN KEY (field_id) REFERENCES Research_Field(field_id) ON DELETE SET NULL
);

-- 6. Create Review Table
CREATE TABLE Review (
    review_id VARCHAR2(20) PRIMARY KEY,
    paper_id VARCHAR2(20) NOT NULL,
    reviewer_id VARCHAR2(20) NOT NULL,
    review_date DATE,
    review_score NUMBER,
    recommendation VARCHAR2(50),
    reviewer_comments CLOB,
    assignment_status VARCHAR2(50) DEFAULT 'Invited',
    CONSTRAINT fk_review_paper FOREIGN KEY (paper_id) REFERENCES Research_Paper(paper_id) ON DELETE CASCADE,
    CONSTRAINT fk_review_reviewer FOREIGN KEY (reviewer_id) REFERENCES Reviewer(reviewer_id) ON DELETE CASCADE
);

-- 7. Create Revision Table
CREATE TABLE Revision (
    revision_id VARCHAR2(20) PRIMARY KEY,
    paper_id VARCHAR2(20) NOT NULL,
    version_number NUMBER NOT NULL,
    upload_date DATE,
    revised_file VARCHAR2(255),
    remarks CLOB,
    CONSTRAINT fk_revision_paper FOREIGN KEY (paper_id) REFERENCES Research_Paper(paper_id) ON DELETE CASCADE
);

-- 8. Create Final Decision Table
CREATE TABLE Final_Decision (
    decision_id VARCHAR2(20) PRIMARY KEY,
    paper_id VARCHAR2(20) NOT NULL UNIQUE,
    admin_id VARCHAR2(20) NOT NULL,
    final_status VARCHAR2(50) NOT NULL,
    decision_date DATE,
    comments CLOB,
    CONSTRAINT fk_decision_paper FOREIGN KEY (paper_id) REFERENCES Research_Paper(paper_id) ON DELETE CASCADE,
    CONSTRAINT fk_decision_admin FOREIGN KEY (admin_id) REFERENCES Administrator(admin_id) ON DELETE SET NULL
);

