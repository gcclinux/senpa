CREATE DATABASE senpa;

\c senpa

CREATE TABLE senpa_activation (
  status character varying(4) NULL,
  user_email character varying(64) NOT NULL,
  type character varying(12) NULL,
  token character varying(64) NOT NULL,
  created timestamp(0) without time zone NULL,
  user_id smallint NULL,
  act_id SERIAL PRIMARY KEY
);

CREATE TABLE senpa_admins (
  failed smallint NULL,
  crypt_key character varying(24) NULL,
  modified character varying(24) NULL,
  created timestamp(0) without time zone NULL,
  last_login character varying(24) NULL,
  user_lang character varying(2) NOT NULL,
  user_status character varying(12) NOT NULL,
  user_pass character varying(256) NOT NULL,
  user_email character varying(64) NOT NULL,
  user_name character varying(64) NOT NULL,
  user_id SERIAL PRIMARY KEY
);

CREATE TABLE senpa_passwd (
  comments text NULL,
  totp_lengh smallint NULL,
  totp_time smallint NULL,
  totp_name character varying(128) NULL,
  site_url character varying(256) NOT NULL,
  login_pass character varying(256) NOT NULL,
  login_name character varying(64) NOT NULL,
  site_name character varying(64) NOT NULL,
  group_name character varying(12) NOT NULL,
  expiration character varying(12) NOT NULL,
  modified timestamp(0) without time zone NULL,
  created timestamp(0) without time zone NULL,
  user_id smallint NOT NULL,
  pass_id SERIAL PRIMARY KEY
);
