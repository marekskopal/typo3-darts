CREATE TABLE tx_msdarts_domain_model_group (
    title varchar(255) DEFAULT '' NOT NULL,
    actual tinyint(4) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_msdarts_domain_model_team (
    groups int(11) unsigned DEFAULT '0' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    place varchar(255) DEFAULT '' NOT NULL,
    address varchar(255) DEFAULT '' NOT NULL,
    login_code varchar(255) DEFAULT '' NOT NULL,
    playing_day tinyint(1) unsigned DEFAULT '1' NOT NULL,
    players int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_msdarts_team_group_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_msdarts_domain_model_player (
    team int(11) unsigned DEFAULT '0' NOT NULL,
    first_name varchar(255) DEFAULT '' NOT NULL,
    last_name varchar(255) DEFAULT '' NOT NULL,
    phone varchar(255) DEFAULT '' NOT NULL,
    email varchar(255) DEFAULT '' NOT NULL,
    images int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_msdarts_domain_model_matchscore (
    matchgroup int(11) unsigned DEFAULT '0' NOT NULL,
    round int(11) unsigned DEFAULT '0' NOT NULL,
    match_date int(11) unsigned DEFAULT '0' NOT NULL,
    team1 int(11) unsigned DEFAULT '0' NOT NULL,
    team2 int(11) unsigned DEFAULT '0' NOT NULL,
    leg1 int(3) unsigned DEFAULT '0' NOT NULL,
    leg2 int(3) unsigned DEFAULT '0' NOT NULL,
    points1 int(3) DEFAULT '0' NOT NULL,
    points2 int(3) DEFAULT '0' NOT NULL,
    score_manual tinyint(4) unsigned DEFAULT '0' NOT NULL,
    score1 int(3) DEFAULT '0' NOT NULL,
    score2 int(3) DEFAULT '0' NOT NULL
);
