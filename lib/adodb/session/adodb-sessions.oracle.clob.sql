-- $CVSHeader: phpframework/webroot/src/includes/libs/adodb/session/adodb-sessions.oracle.clob.sql,v 1.1 2009-01-15 12:07:47 bkahle Exp $

DROP TABLE adodb_sessions;

CREATE TABLE sessions (
	sesskey		CHAR(32)	DEFAULT '' NOT NULL,
	expiry		INT		DEFAULT 0 NOT NULL,
	expireref	VARCHAR(64)	DEFAULT '',
	data		CLOB		DEFAULT '',
	PRIMARY KEY	(sesskey)
);

CREATE INDEX ix_expiry ON sessions (expiry);

QUIT;
