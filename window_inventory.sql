CREATE DATABASE window_inventory;
USE window_inventory;

-- Tabela materijali
CREATE TABLE materials (
    materials_id INT AUTO_INCREMENT PRIMARY KEY,
    mat_name VARCHAR(100) NOT NULL,
    mat_type VARCHAR(50),
    manufacturer VARCHAR(50),
    quantity DECIMAL(10,2) NOT NULL,
    min_quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) NOT NULL
);

-- Tabela projekti
CREATE TABLE projects (
    projects_id INT AUTO_INCREMENT PRIMARY KEY,
    proj_name VARCHAR(100) NOT NULL,
    client VARCHAR(100),
    date_start DATE,
    date_end DATE
);

-- Materijali po projektu (potrošnja)
CREATE TABLE project_materials (
    project_materialsid INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    material_id INT NOT NULL,
    quantity_used DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(projects_id),
    FOREIGN KEY (material_id) REFERENCES materials(materials_id)
);
