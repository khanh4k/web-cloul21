
-- Tạo bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Tạo bảng món ăn
CREATE TABLE foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

-- Tạo bảng đơn hàng
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tạo bảng chi tiết đơn hàng
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (food_id) REFERENCES foods(id)
);

-- Thêm dữ liệu mẫu
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('user1', 'user123', 'user');

INSERT INTO foods (name, description, price, image) VALUES
('Chicken Burger', 'Bánh burger gà thơm ngon, mềm mại, đầy đặn', 115.00, 'img/menu-1.jpg'),
('Beef Pizza', 'Pizza bò nướng phô mai béo ngậy', 145.00, 'img/menu-2.jpg'),
('Fried Chicken', 'Gà rán giòn tan, đậm vị', 98.00, 'img/menu-3.jpg'),
('French Fries', 'Khoai tây chiên giòn, ăn kèm tương cà', 45.00, 'img/menu-4.jpg'),
('Coca Cola', 'Nước ngọt Coca lạnh', 20.00, 'img/drink-1.jpg');
