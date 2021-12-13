<?php
require "heading-nav.php";
require "helperfunctions.php";
?>
<!-- This is where the leaderboard will be displayed -->
        <?php
        $sql="SELECT
        userboard.ids as username,
        COUNT(*) as games_played,
        SUM(userboard.won) as games_won,
        SUM(userboard.won) / COUNT(*) as win_loss_ratio
    FROM
        `userboard`
    GROUP BY
        `userboard`.ids
    ORDER BY
        win_loss_ratio DESC";
            //ids VARCHAR(30),
            // turns INT(30),
            // score INT(30),
            // won INT(6),
            // duration INT(19) 
        
        $results = $conn->query($sql);
        if($results->num_rows > 0){//function to set up the leaderboard from a fetch associative
            echo "<table> <tr><th>Username</th><th>games played</th><th>games won</th><th>Winloss</th></tr>";
            while($row = $results->fetch_assoc()){
                echo "<tr><td>" . $row['username'] . "</td><td>" . $row['games_played'] ."</td><td>" . $row['games_won'] ."</td><td>" . $row['win_loss_ratio'] . "</td></tr>"; 
            }
            echo "</table>"; 
        }
        else{
            echo "No users have played yet";
        }
        
        ?>
    </body>
</html>