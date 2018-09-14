/* In a real-world database environment, I would likely use lookup/reference tables instead of 
enum fields. Since this is just a fun project working with a static dataset, I thought creating 20 
additional tables would add too much unnecessary complexity.

Also - while the parking data is stored in a separate CSV, it turns out that all the restaurants in the 
restaurant file only have one value for this field. Therefore I'm converting it into another column on
the restaurant table. */

CREATE TABLE RESTAURANT (
	placeID INTEGER NOT NULL,
	latitude DOUBLE NOT NULL,
	longitude DOUBLE NOT NULL,
	name VARCHAR(55) NOT NULL,
	address VARCHAR(50) NULL,
	city VARCHAR(20) NULL,
	state VARCHAR(20) NULL,
	zip VARCHAR(10) NULL,
	alcohol ENUM('No_Alcohol_Served','Wine-Beer','Full_Bar') NOT NULL,
	smoking_area ENUM('none','only at bar','permitted','section','not permitted') NOT NULL,
	dress_code ENUM('informal','casual','formal') NOT NULL,
	accessibility ENUM('no_accessibility','completely','partially') NOT NULL,
	price ENUM('medium','low','high') NOT NULL,
	Rambience ENUM('familiar','quiet') NOT NULL,
	franchise BIT NOT NULL,
	area ENUM('open','closed') NOT NULL,
	other_services ENUM('none','internet','variety') NOT NULL,
    parking_lot ENUM('public','none','yes','valet_parking','free','street','validated_parking') NOT NULL,
	CONSTRAINT restaurantPK PRIMARY KEY (placeID)
);

CREATE TABLE RESTAURANT_HOURS (
	hoursID INTEGER NOT NULL AUTO_INCREMENT,
    placeID INTEGER NOT NULL,
	hours VARCHAR(50) NOT NULL,
	days ENUM('Weekdays','Sat','Sun') NOT NULL,
	CONSTRAINT hoursPK PRIMARY KEY (hoursID),
	CONSTRAINT hoursRestFK FOREIGN KEY (placeID)
		REFERENCES RESTAURANT(placeID)
		ON UPDATE NO ACTION
		ON DELETE CASCADE,
	CONSTRAINT parkingUnique UNIQUE (placeID, days)
);

CREATE TABLE CUSTOMER (
	userID INTEGER NOT NULL,
	latitude DOUBLE NOT NULL,
	longitude DOUBLE NOT NULL,
	smoker BIT NULL,
	drink_level ENUM('abstemious','social drinker','casual drinker') NOT NULL,
	dress_preference ENUM('informal','formal','no preference','elegant') NULL,
	ambience ENUM('family','friends','solitary') NULL,
	transport ENUM('on foot','public','car owner') NULL,
	marital_status ENUM('single','married','widow') NULL,
	hijos ENUM('independent','kids','dependent') NULL,
	birth_year INTEGER NOT NULL,
	interest ENUM('variety','technology','none','retro','eco-friendly') NOT NULL,
	personality ENUM('thrifty-protector','hunter-ostentatious','hard-worker','conformist') NOT NULL,
	religion ENUM('none','Catholic','Christian','Mormon','Jewish') NOT NULL,
	activity ENUM('student','professional','unemployed','working-class') NULL,
	color ENUM('black','red','blue','green','purple','orange','yellow','white') NOT NULL,
	weight INTEGER NOT NULL,
	budget ENUM('medium','low','high') NULL,
	height DOUBLE NOT NULL,
	CONSTRAINT customerPK PRIMARY KEY (userID)
);

CREATE TABLE RATING (
	userID INTEGER NOT NULL,
	placeID INTEGER NOT NULL,
	rating INTEGER NOT NULL,
	food_rating INTEGER NOT NULL,
	service_rating INTEGER NOT NULL,
	CONSTRAINT ratingPK PRIMARY KEY (userID, placeID),
	CONSTRAINT ratingCustFK FOREIGN KEY (userID)
		REFERENCES CUSTOMER(userID)
		ON UPDATE NO ACTION
		ON DELETE NO ACTION,
	CONSTRAINT ratingRestFK FOREIGN KEY (placeID)
		REFERENCES RESTAURANT(placeID)
		ON UPDATE NO ACTION
		ON DELETE NO ACTION
);

/* Counter to the note above, I did separate the cuisine and payment data into lookup tables - but primarily
because the data is related to both the restaurant and the customer. If those two tables had shared any other
attributes, I would also have created a separate lookup table for them. */

CREATE TABLE CUISINE (
	cuisineID INTEGER NOT NULL AUTO_INCREMENT,
	cuisine VARCHAR(20),
	CONSTRAINT cuisinePK PRIMARY KEY (cuisineID)
);

CREATE TABLE RESTAURANT_CUISINE (
	placeID INTEGER NOT NULL,
	cuisineID INTEGER NOT NULL,
	CONSTRAINT resCuisinePK PRIMARY KEY (placeID,cuisineID),
	CONSTRAINT cuisineRestFK FOREIGN KEY (placeID)
		REFERENCES RESTAURANT(placeID)
		ON UPDATE NO ACTION
		ON DELETE CASCADE,
	CONSTRAINT cuisineFK1 FOREIGN KEY (cuisineID)
		REFERENCES CUISINE(cuisineID)
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

CREATE TABLE CUSTOMER_CUISINE (
	userID INTEGER NOT NULL,
	cuisineID INTEGER NOT NULL,
	CONSTRAINT custCuisinePK PRIMARY KEY (userID,cuisineID),
	CONSTRAINT cuisineCustFK FOREIGN KEY (userID)
		REFERENCES CUSTOMER(userID)
		ON UPDATE NO ACTION
		ON DELETE CASCADE,
	CONSTRAINT cuisineFK2 FOREIGN KEY (cuisineID)
		REFERENCES CUISINE(cuisineID)
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

CREATE TABLE PAYMENT (
	paymentID INTEGER NOT NULL AUTO_INCREMENT,
	payment VARCHAR(19) NOT NULL,
	CONSTRAINT paymentPK PRIMARY KEY (paymentID)
);

CREATE TABLE RESTAURANT_PAYMENT (
	placeID INTEGER NOT NULL,
	paymentID INTEGER NOT NULL,
	CONSTRAINT resPaymentPK PRIMARY KEY (placeID,paymentID),
	CONSTRAINT paymentRestFK FOREIGN KEY (placeID)
		REFERENCES RESTAURANT(placeID)
		ON UPDATE NO ACTION
		ON DELETE CASCADE,
	CONSTRAINT paymentFK1 FOREIGN KEY (paymentID)
		REFERENCES PAYMENT(paymentID)
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

CREATE TABLE CUSTOMER_PAYMENT (
	userID INTEGER NOT NULL,
	paymentID INTEGER NOT NULL,
	CONSTRAINT custPaymentPK PRIMARY KEY (userID,paymentID),
    CONSTRAINT paymentCustFK FOREIGN KEY (userID)
		REFERENCES CUSTOMER(userID)
		ON UPDATE NO ACTION
		ON DELETE CASCADE,
	CONSTRAINT paymentFK2 FOREIGN KEY (paymentID)
		REFERENCES PAYMENT(paymentID)
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);