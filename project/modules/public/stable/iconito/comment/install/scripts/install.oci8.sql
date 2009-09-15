CREATE TABLE "comment" (
  author_cmt varchar2(50) default '' NOT NULL ,
  date_cmt varchar2(8) default '' NOT NULL ,
  id_cmt varchar2(255) default '' NOT NULL ,
  textformat_cmt varchar2(50) default 'text' NOT NULL ,
  type_cmt varchar2(255) default '' NOT NULL ,
  content_cmt clob NOT NULL,
  position_cmt number(11) default '0' NOT NULL ,
  title_cmt varchar(255) default NULL
);
