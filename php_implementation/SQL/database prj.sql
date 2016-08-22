CREATE DATABASE SchoolScreen;

CREATE TABLE User
(
PCN 				int				primary key,
Password 			varChar(100)	NOT NULL,
User_type			int				NOT NULL,
);

CREATE TABLE Product
(
Id 					int 			primary key,
Photo		 		varChar(255)	NOT NULL,
Rent_price			int				NOT NULL,
Year 				int				NOT NULL,
Title				varChar(255)	NOT NULL,
Director			varChar(100)	NOT NULL,
Genre				varChar(255)	NOT NULL,
Duration			varChar(100)	NOT NULL,
Description			varChar(255)	NOT NULL,
);

CREATE TABLE Reservation
(
PCN 				int				primary key,
Product_id			varChar(100)	primary key,
Note				varChar(255)	NULL,
foreign key (PCN) references User (PCN)
foreign key (Product_id) references Product (Id)
);

CREATE TABLE lease
(
Id					int				primary key,
PCN					int				primary key,
Product_id			varChar(100)	primary key,
End_date			date			NOT NULL,
foreign key (PCN) references User (PCN)
foreign key (Product_id) references Product (Id)
);

ALTER TABLE User(
CHECK(User_type IN('1', '2', '3'))
);
	
