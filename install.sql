-- label
DROP TABLE IF EXISTS wcf1_label;
CREATE TABLE wcf1_label (
	labelID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	groupID INT(10) NOT NULL,
	label VARCHAR(80) NOT NULL,
	cssClassName VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS wcf1_label_group;
CREATE TABLE wcf1_label_group (
	groupID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	groupName VARCHAR(80) NOT NULL
);

DROP TABLE IF EXISTS wcf1_label_group_to_object;
CREATE TABLE wcf1_label_group_to_object (
	groupID INT(10) NOT NULL,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NULL
);

DROP TABLE IF EXISTS wcf1_label_object;
CREATE TABLE wcf1_label_object (
	labelID INT(10) NOT NULL,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	
	KEY (objectTypeID, labelID),
	KEY (objectTypeID, objectID)
);

ALTER TABLE wcf1_label ADD FOREIGN KEY (groupID) REFERENCES wcf1_label_group (groupID) ON DELETE CASCADE;

ALTER TABLE wcf1_label_group_to_object ADD FOREIGN KEY (groupID) REFERENCES wcf1_label_group (groupID) ON DELETE CASCADE;
ALTER TABLE wcf1_label_group_to_object ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;

ALTER TABLE wcf1_label_object ADD FOREIGN KEY (labelID) REFERENCES wcf1_label (labelID) ON DELETE CASCADE;
ALTER TABLE wcf1_label_object ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;