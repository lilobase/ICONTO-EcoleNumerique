CREATE TABLE comment (
author_cmt          varchar(50) NOT NULL DEFAULT '',
date_cmt            varchar(8)NOT NULL DEFAULT '',
id_cmt              varchar(255)NOT NULL DEFAULT '',
textformat_cmt      varchar(50)NOT NULL DEFAULT '',
type_cmt            varchar(255)NOT NULL DEFAULT '',
content_cmt         text NOT NULL,
position_cmt        integer NOT NULL DEFAULT 0,
title_cmt           varchar(255) DEFAULT NULL
);