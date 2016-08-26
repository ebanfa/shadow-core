DROP TABLE IF EXISTS contentorder;
DROP TABLE IF EXISTS writingstyle;
DROP TABLE IF EXISTS academiclevel;
DROP TABLE IF EXISTS subjectarea;
DROP TABLE IF EXISTS noofpages;
DROP TABLE IF EXISTS urgency;
DROP TABLE IF EXISTS documenttype;
DROP TABLE IF EXISTS contactus;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS notificationlevel;
DROP TABLE IF EXISTS notificationstatus;
DROP TABLE IF EXISTS notificationtype;
DROP TABLE IF EXISTS messagefiles;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS conversationuser;
DROP TABLE IF EXISTS conversation;
DROP TABLE IF EXISTS billingaccount;
DROP TABLE IF EXISTS partyprofile;
DROP TABLE IF EXISTS person;
DROP TABLE IF EXISTS partygroup;
DROP TABLE IF EXISTS partyrelationship;
DROP TABLE IF EXISTS relationshipstatus;
DROP TABLE IF EXISTS relationshiptype;
DROP TABLE IF EXISTS partyrole;
DROP TABLE IF EXISTS party;
DROP TABLE IF EXISTS roletype;
DROP TABLE IF EXISTS partytype;
DROP TABLE IF EXISTS partycategory;
DROP TABLE IF EXISTS businessunit;
DROP TABLE IF EXISTS business;
DROP TABLE IF EXISTS location;
DROP TABLE IF EXISTS locationtype;
DROP TABLE IF EXISTS currency;


CREATE TABLE currency  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	symbol   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE locationtype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE location  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	location_type   int(11) NOT NULL,
   	location   int(11) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
 	FOREIGN KEY (location_type) REFERENCES locationtype (id), 
 	FOREIGN KEY (location) REFERENCES location (id), 
	PRIMARY KEY( id )
);

CREATE TABLE business  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	currency   int(11) NOT NULL,
	user_name   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
 	FOREIGN KEY (currency) REFERENCES currency (id), 
	PRIMARY KEY( id )
);

CREATE TABLE businessunit  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	parent_unit   int(11) NULL,
   	currency   int(11) NULL,
	name   		varchar(35) NOT NULL,
	address_1   		varchar(35) NOT NULL,
	address_2   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
   	business   int(11) NULL,
 	FOREIGN KEY (parent_unit) REFERENCES businessunit (id), 
 	FOREIGN KEY (currency) REFERENCES currency (id), 
 	FOREIGN KEY (business) REFERENCES business (id), 
	PRIMARY KEY( id )
);

CREATE TABLE partycategory  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE partytype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	party_category   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
 	FOREIGN KEY (party_category) REFERENCES partycategory (id), 
	PRIMARY KEY( id )
);

CREATE TABLE roletype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE party  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	party_type   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	user_name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (party_type) REFERENCES partytype (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE partyrole  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	party   int(11) NOT NULL,
   	parent_prole   int(11) NULL,
   	role   int(11) NOT NULL,
   	parent_unit   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (parent_prole) REFERENCES partyrole (id), 
 	FOREIGN KEY (role) REFERENCES roletype (id), 
 	FOREIGN KEY (parent_unit) REFERENCES businessunit (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE relationshiptype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE relationshipstatus  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE partyrelationship  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	rel_type   int(11) NOT NULL,
   	from_role   int(11) NOT NULL,
   	to_role   int(11) NOT NULL,
   	status   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	from_date   		date NOT NULL,
	to_date   		date NOT NULL,
	description   		varchar(255) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (rel_type) REFERENCES relationshiptype (id), 
 	FOREIGN KEY (from_role) REFERENCES partyrole (id), 
 	FOREIGN KEY (to_role) REFERENCES partyrole (id), 
 	FOREIGN KEY (status) REFERENCES relationshipstatus (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE partygroup  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	party   int(11) NOT NULL,
   	business_unit   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	pin   		INT(6) NOT NULL,
	description   		varchar(255) NOT NULL,
	date_created   		date NOT NULL,
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE person  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	party   int(11) NOT NULL,
   	business_unit   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	first_name   		varchar(35) NOT NULL,
	last_name   		varchar(35) NOT NULL,
	gender   		varchar(35) NOT NULL,
	date_of_birth   		date NOT NULL,
	id_number   		varchar(35) NOT NULL,
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE partyprofile  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	party   int(11) NOT NULL,
   	default_unit   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	display_name   		varchar(35) NOT NULL,
	date_created   		date NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (default_unit) REFERENCES businessunit (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE billingaccount  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	party   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	balance   		decimal(38,0) NULL,
	date_created   		date NULL,
	description   		varchar(255) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE conversation  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE conversationuser  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	conversation   int(11) NOT NULL,
   	con_user   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	create_date   		date NULL,
	description   		varchar(255) NOT NULL,
 	FOREIGN KEY (conversation) REFERENCES conversation (id), 
 	FOREIGN KEY (con_user) REFERENCES party (id), 
	PRIMARY KEY( id )
);

CREATE TABLE message  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	conversation   int(11) NOT NULL,
   	owner   int(11) NOT NULL,
   	counter_party   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	message   		varchar(255) NOT NULL,
	message_date   		date NULL,
 	FOREIGN KEY (conversation) REFERENCES conversation (id), 
 	FOREIGN KEY (owner) REFERENCES party (id), 
 	FOREIGN KEY (counter_party) REFERENCES party (id), 
	PRIMARY KEY( id )
);

CREATE TABLE messagefiles  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
   	message   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	file_url   		varchar(35) NOT NULL,
	file_size   		varchar(35) NOT NULL,
 	FOREIGN KEY (message) REFERENCES message (id), 
	PRIMARY KEY( id )
);

CREATE TABLE notificationtype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	title_template   		varchar(35) NOT NULL,
	message_template   		varchar(35) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE notificationstatus  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE notificationlevel  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE notification  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NULL,
   	n_owner   int(11) NOT NULL,
   	n_type   int(11) NOT NULL,
   	status   int(11) NOT NULL,
   	log_level   int(11) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (n_owner) REFERENCES party (id), 
 	FOREIGN KEY (n_type) REFERENCES notificationtype (id), 
 	FOREIGN KEY (status) REFERENCES notificationstatus (id), 
 	FOREIGN KEY (log_level) REFERENCES notificationlevel (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);

CREATE TABLE contactus  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	subject   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	email   		varchar(35) NOT NULL,
	b_name   		varchar(35) NOT NULL,
	message   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE documenttype  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE urgency  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	date_value   		varchar(35) NOT NULL,
	date_unit   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE noofpages  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE subjectarea  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE academiclevel  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE writingstyle  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	description   		varchar(255) NOT NULL,
	PRIMARY KEY( id )
);

CREATE TABLE contentorder  ( 
	id   	int(11) AUTO_INCREMENT NOT NULL,
	entity_code   		varchar(35) NOT NULL,
	name   		varchar(35) NOT NULL,
	email   		varchar(35) NOT NULL,
   	document_type   int(11) NOT NULL,
   	urgency   int(11) NOT NULL,
   	numpages   int(11) NOT NULL,
   	subject_area   int(11) NOT NULL,
   	academic_level   int(11) NOT NULL,
   	writing_style   int(11) NOT NULL,
	description   		varchar(255) NOT NULL,
	total   		decimal(38,0) NULL,
   	party   int(11) NOT NULL,
   	business_unit   int(11) NOT NULL,
 	FOREIGN KEY (document_type) REFERENCES documenttype (id), 
 	FOREIGN KEY (urgency) REFERENCES urgency (id), 
 	FOREIGN KEY (numpages) REFERENCES noofpages (id), 
 	FOREIGN KEY (subject_area) REFERENCES subjectarea (id), 
 	FOREIGN KEY (academic_level) REFERENCES academiclevel (id), 
 	FOREIGN KEY (writing_style) REFERENCES writingstyle (id), 
 	FOREIGN KEY (party) REFERENCES party (id), 
 	FOREIGN KEY (business_unit) REFERENCES businessunit (id), 
	PRIMARY KEY( id )
);
