# Login user - password is 123456
INSERT INTO `users` (`email`, `pass`) VALUES ('test@test.com', '$2y$10$cfzj5BtqO.BqL1i1Uwc1dupvLWuPyT1W5Qt0h/Jx6roBojwdKn9Qi');

# Players
INSERT INTO `players` (`email`, `playerName`) VALUES ('test1@test.com', 'test1');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test2@test.com', 'test2');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test3@test.com', 'test3');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test4@test.com', 'test4');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test5@test.com', 'test5');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test6@test.com', 'test6');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test7@test.com', 'test7');
INSERT INTO `players` (`email`, `playerName`) VALUES ('test8@test.com', 'test8');

# Teams
INSERT INTO `teams` (`teamName`) VALUES ('team1');
INSERT INTO `teams` (`teamName`) VALUES ('team2');

# Team Members
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test1@test.com', '1');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test2@test.com', '1');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test3@test.com', '1');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test4@test.com', '1');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test5@test.com', '2');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test6@test.com', '2');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test7@test.com', '2');
INSERT INTO `teamMembers` (`playerEmail`, `teamId`) VALUES ('test8@test.com', '2');

# Matches
INSERT INTO `matches` (`team1`, `team2`) VALUES ('1', '2');

# Match Scores
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '1', 'test1@test.com', '50', '150', '143', '162');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '1', 'test2@test.com', '32', '160', '150', '182');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '1', 'test3@test.com', '58', '140', '132', '160');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '1', 'test4@test.com', '51', '150', '156', '144');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test5@test.com', '84', '120', '135', '110');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test6@test.com', '30', '200', '150', '165');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test7@test.com', '51', '150', '121', '169');
INSERT INTO `matchScores` (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES ('1', '2', 'test8@test.com', '58', '140', '150', '147');
