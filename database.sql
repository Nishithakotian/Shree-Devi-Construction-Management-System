CREATE DATABASE cms_system;
USE cms_system;
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    type VARCHAR(50),
    location VARCHAR(100),
    start_date DATE,
    end_date DATE,
    status VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    type VARCHAR(50),
    quantity INT,
    cost_per_unit DECIMAL(10,2),
    supplier VARCHAR(100),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE budget (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    total_budget DECIMAL(12,2),
    spent_amount DECIMAL(12,2),
    remaining_amount DECIMAL(12,2),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    task_name VARCHAR(100),
    due_date DATE,
    priority VARCHAR(20),
    status VARCHAR(50),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    progress_percent INT,
    status VARCHAR(50),
    remarks TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    role VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);