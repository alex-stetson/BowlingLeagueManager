USE bowling_league_database;

# Login user - password is 123456
INSERT INTO `bowling_league_database`.`users` (`email`, `pass`) VALUES ('test@test.com', '$2y$10$cfzj5BtqO.BqL1i1Uwc1dupvLWuPyT1W5Qt0h/Jx6roBojwdKn9Qi');

# Players
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test1@test.com', 'test1');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test2@test.com', 'test2');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test3@test.com', 'test3');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test4@test.com', 'test4');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test5@test.com', 'test5');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test6@test.com', 'test6');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test7@test.com', 'test7');
INSERT INTO `bowling_league_database`.`players` (`email`, `playerName`) VALUES ('test8@test.com', 'test8');

# Teams
INSERT INTO `bowling_league_database`.`teams` (`teamName`) VALUES ('team1');
INSERT INTO `bowling_league_database`.`teams` (`teamName`) VALUES ('team2');

# Team Members
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test1@test.com', '2');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test2@test.com', '2');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test3@test.com', '2');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test4@test.com', '2');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test5@test.com', '3');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test6@test.com', '3');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test7@test.com', '3');
INSERT INTO `bowling_league_database`.`teammembers` (`playerEmail`, `teamId`) VALUES ('test8@test.com', '3');

# Matches
INSERT INTO `bowling_league_database`.`matches` (`team1`, `team2`) VALUES ('2', '3');

# Match Scores
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test1@test.com', '50', '150', '143', '162');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test2@test.com', '32', '160', '150', '182');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test3@test.com', '58', '140', '132', '160');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test4@test.com', '51', '150', '156', '144');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '3', 'test5@test.com', '84', '120', '135', '110');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '3', 'test6@test.com', '30', '200', '150', '165');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '3', 'test7@test.com', '51', '150', '121', '169');
INSERT INTO `bowling_league_database`.`matchscores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '3', 'test8@test.com', '58', '140', '150', '147');
