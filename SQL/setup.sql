CREATE TABLE users (
    email VARCHAR(255) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL,
    PRIMARY KEY (email)
);

CREATE TABLE casUsers (
    userID VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (userID)
);

CREATE TABLE failedLogins (
    id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
    ipAddr VARBINARY(16) DEFAULT NULL,
    attemptedAt DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE teams (
    id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
    teamName VARCHAR(255) NOT NULL,
    totalPoints INT(11) DEFAULT 0,
    PRIMARY KEY (id)
);

CREATE TABLE players (
    email VARCHAR(255) NOT NULL UNIQUE,
    playerName VARCHAR(255) NOT NULL,
    currentHandicap INT(11) DEFAULT 0,
    PRIMARY KEY (email)
);

CREATE TABLE matches (
    id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
    matchTime DATETIME DEFAULT NULL,
    matchLocation VARCHAR(255) DEFAULT NULL,
    team1 INT(10) UNSIGNED DEFAULT NULL,
    team2 INT(10) UNSIGNED DEFAULT NULL,
    team1Points INT(11) DEFAULT 0,
    team2Points INT(11) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (team1)
    REFERENCES teams(id),
    FOREIGN KEY (team2)
    REFERENCES teams(id)
);

CREATE TABLE teamMembers (
    playerEmail VARCHAR(255) NOT NULL,
    teamId INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (playerEmail, teamId),
    FOREIGN KEY (playerEmail)
    REFERENCES players(email),
    FOREIGN KEY (teamId)
    REFERENCES teams(id)
);

CREATE TABLE matchScores (
    matchId INT(10) UNSIGNED NOT NULL,
    teamId INT(10) UNSIGNED NOT NULL,
    playerEmail VARCHAR(255) NOT NULL,
    handicap INT(11) DEFAULT NULL,
    game1Score INT(11) DEFAULT NULL,
    game2Score INT(11) DEFAULT NULL,
    game3Score INT(11) DEFAULT NULL,
    isBlind BOOLEAN DEFAULT 0,
    PRIMARY KEY (matchId, teamId, playerEmail),
    FOREIGN KEY (matchId)
    REFERENCES matches(id),
    FOREIGN KEY (teamId)
    REFERENCES teams(id),
    FOREIGN KEY (playerEmail)
    REFERENCES players(email)
);