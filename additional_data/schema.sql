CREATE TABLE administrator (
  id    INT AUTO_INCREMENT NOT NULL,
  name  VARCHAR(255)       NOT NULL,
  cdate DATETIME           NOT NULL,
  mdate DATETIME           NOT NULL,
  UNIQUE INDEX administrator_id_key (id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE client (
  id     INT AUTO_INCREMENT NOT NULL,
  name   VARCHAR(255)       NOT NULL,
  email  VARCHAR(255)       NOT NULL,
  phone  VARCHAR(255)       NOT NULL,
  status VARCHAR(255)       NOT NULL,
  cdate  DATETIME           NOT NULL,
  mdate  DATETIME           NOT NULL,
  UNIQUE INDEX UNIQ_C7440455E7927C74 (email),
  UNIQUE INDEX client_id_key (id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE document (
  id               INT AUTO_INCREMENT NOT NULL,
  administrator_id INT                NOT NULL,
  client_id        INT DEFAULT NULL,
  task_id          INT DEFAULT NULL,
  name             VARCHAR(255)       NOT NULL,
  cdate            DATETIME           NOT NULL,
  mdate            DATETIME           NOT NULL,
  INDEX IDX_D8698A764B09E92C (administrator_id),
  INDEX IDX_D8698A7619EB6921 (client_id),
  INDEX IDX_D8698A768DB60186 (task_id),
  UNIQUE INDEX document_id_key (id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE task (
  id               INT AUTO_INCREMENT NOT NULL,
  administrator_id INT DEFAULT NULL,
  name             VARCHAR(255)       NOT NULL,
  description      LONGTEXT           NOT NULL,
  status           VARCHAR(255)       NOT NULL,
  cdate            DATETIME           NOT NULL,
  mdate            DATETIME           NOT NULL,
  INDEX IDX_527EDB254B09E92C (administrator_id),
  UNIQUE INDEX task_id_key (id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
ALTER TABLE document ADD CONSTRAINT FK_D8698A764B09E92C FOREIGN KEY (administrator_id) REFERENCES administrator (id);
ALTER TABLE document ADD CONSTRAINT FK_D8698A7619EB6921 FOREIGN KEY (client_id) REFERENCES client (id);
ALTER TABLE document ADD CONSTRAINT FK_D8698A768DB60186 FOREIGN KEY (task_id) REFERENCES task (id);
ALTER TABLE task ADD CONSTRAINT FK_527EDB254B09E92C FOREIGN KEY (administrator_id) REFERENCES administrator (id);
