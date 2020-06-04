A)
Tables 

team
id UNSIGNED int(11) primary key autoincrement
name varchar(100)

player
id UNSIGNED int(11) primary key autoincrement
name varchar(100) unique index
team_id  UNSIGNED int(11) foreign key not null

game
id UNSIGNED int(11) primary key autoincrement
team1_id UNSIGNED int(11) not null foreign key
team2_id  UNSIGNED int(11) not null foreign key
team1_nr_points UNSIGNED int(11) not null defaut 0 
team2_nr_points UNSIGNED int(11) not null defaut 0 
status ENUM (0,1,2) 0 equal 1 win 2 lose
date Datetime not null

player_statistics
player_id UNSIGNED int(11) not null foreign key
game_id UNSIGNED int(11)  not null foreign key
nr_points UNSIGNED int(11) not null
primary key(player_id, game_id);

B)
Using this recursively linux command it will delete all files which are starting with 0aH from folder test
find test -type f -name 0aH\* -exec rm -rf {} \;
You can also use php for this eg: exec('find test -type f -name 0aH\* -exec rm -rf {} \;');

C)

For sorting those small numbers I chose the fastest algoritym used in php ->usort see Usort::testSortExecutionFiveTimesFor11Numbers();
Also I did some tests using usort and compare with my timsort method -> see TimSort::testSortExecutionFiveTimesFor11Numbers();

Expected results
0.0000050545s - 0.0000054359s on 16GB RAM with windows system -> this is for 11 elem for one function call

50545s - 54359s aprox 14-15h on 16GB RAM with windows system -> this is for 11 elem for 10^10 function call

A typical server would have RAM in the range of 16 GB - 6TB

if you are using a normal server let's say 256GB will be executed more than 16 times faster


D)

aprox 0.0053100586s on local machine(16GB RAM with windows system) using usort and 10000 powers see Usort::testSortExecutionFor10000Powers();
