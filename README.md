# API-Inlamning-David

Welcome to Davids API system! Before using this service please go to your database editor and create a databse with the sql question that you can find below! After you have done that you can start using the service by following the following instructions: 
1. Go to your webb browser and type http://localhost/API_Inla%CC%88mning/v1/
2. Then you can use the php functions that are in the v1 folder by adding them after the url above in the webbrowser! The functions differ between user related, product related and cart related!
3. You will have to add different kinds of information according to what function you have selected in order for them to work! This is done by setting GET variables in the url after the selected function!
4. Now you should have all means to user all the existing functions with ease :) but keep in mind that the prefered order to use this API is if you start by registering, then logging in, then adding a few products and at the end fullfill a purchase by adding and removing products from your cart and then checkout wanted items. Enjoy!
5. I just want to add that if something goes wrong you will get an error message (codewise) and if anything is successfull you will get a response!


SQL questions starts here: 

DROP DATABASE IF EXISTS APIendpoints;
CREATE DATABASE APIendpoints;

DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;


CREATE TABLE users (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(30) NOT NULL,
email VARCHAR(100),
password VARCHAR(150) NOT NULL,
role VARCHAR(5) DEFAULT 'user' 
) ENGINE = InnoDB;

CREATE TABLE products (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(30) NOT NULL,
description VARCHAR(100) NOT NULL,
image VARCHAR(100),
category VARCHAR(50) NOT NULL,
price INT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE cart (
productId INT NOT NULL,
userId INT NOT NULL,
PRIMARY KEY (productId, userId),
token VARCHAR(100) NOT NULL,
quantity INT NOT NULL,
orderdate DATE NOT NULL,
CONSTRAINT FKproductsId FOREIGN KEY(productId) REFERENCES products(id),
CONSTRAINT FKusersId FOREIGN KEY(userId) REFERENCES users(id)
) ENGINE = innoDB;

CREATE TABLE sessions (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
userId INT NOT NULL,
token text NOT NULL,
last_used INT NOT NULL,
CONSTRAINT FKuserId FOREIGN KEY(userId) REFERENCES users(id)
) ENGINE = InnoDB;
